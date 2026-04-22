const fs = require("fs");
const path = require("path");
const readline = require("readline");

const ROOT = __dirname;
const COMPONENT_ROOT = path.join(ROOT, "components");

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout,
});

const ask = (question) =>
  new Promise((resolve) => {
    rl.question(question, (answer) => resolve(answer.trim()));
  });

const toKebabCase = (value) =>
  value
    .trim()
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "");

const toSnakeCase = (value) => toKebabCase(value).replace(/-/g, "_");

const toTitleCase = (value) =>
  toKebabCase(value)
    .split("-")
    .filter(Boolean)
    .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
    .join(" ");

const ensureDirectory = (directoryPath) => {
  fs.mkdirSync(directoryPath, { recursive: true });
};

const readFile = (filePath) => fs.readFileSync(filePath, "utf8");

const writeFile = (filePath, content) => {
  fs.writeFileSync(filePath, content, "utf8");
};

const componentTypeMap = {
  layout: {
    folder: "layout",
    baseName: "base-layout-block",
    basePhpFunction: null,
    shortcodeTemplate: (kebabName, shortcodeSlug) => `<?php

add_shortcode('${shortcodeSlug}', function ($atts, $content = null) {
    $attributes = shortcode_atts([
        'variant' => 'default',
        'eyebrow' => '',
        'title' => '',
        'body' => '',
    ], $atts);

    if ($content !== null && trim((string) $content) !== '') {
        $attributes['body'] = wpautop($content);
    } elseif ($attributes['body'] !== '') {
        $attributes['body'] = wpautop($attributes['body']);
    }

    ob_start();
    get_template_part('components/layout/${kebabName}/${kebabName}', null, $attributes);
    return ob_get_clean();
});
`,
  },
  "content-block": {
    folder: "content-blocks",
    baseName: "base-content-block",
    basePhpFunction: "boilerplate_render_base_content_block",
    shortcodeTemplate: (kebabName, shortcodeSlug, snakeName) => `<?php

require_once __DIR__ . '/${kebabName}.php';

boilerplate_register_content_layout('${kebabName}', [
    'label' => '${toTitleCase(kebabName)}',
    'render_function' => 'boilerplate_render_${snakeName}',
    'signature' => 'section',
    'fallback_layout' => 'base-content-block',
]);

add_shortcode('${shortcodeSlug}', function ($atts, $content = null) {
    $attributes = shortcode_atts([
        'title' => '${toTitleCase(kebabName)}',
    ], $atts);

    $section = [
        'h2' => $attributes['title'],
        'blocks' => [],
    ];

    if ($content !== null && trim((string) $content) !== '') {
        $section['blocks'][] = [
            'type' => 'text',
            'content' => wpautop($content),
        ];
    }

    ob_start();
    boilerplate_render_${snakeName}($section);
    return ob_get_clean();
});
`,
  },
  ui: {
    folder: "ui",
    baseName: "base-ui-component",
    basePhpFunction: null,
    shortcodeTemplate: (kebabName, shortcodeSlug) => `<?php

add_shortcode('${shortcodeSlug}', function ($atts, $content = null) {
    $attributes = shortcode_atts([
        'label' => '',
        'variant' => 'default',
        'url' => '',
        'tag' => 'div',
    ], $atts);

    if ($attributes['label'] === '' && $content !== null) {
        $attributes['label'] = trim(wp_strip_all_tags((string) $content));
    }

    ob_start();
    get_template_part('components/ui/${kebabName}/${kebabName}', null, $attributes);
    return ob_get_clean();
});
`,
  },
};

const replaceTokens = (template, replacements) => {
  let output = template;

  Object.entries(replacements).forEach(([search, replace]) => {
    output = output.split(search).join(replace);
  });

  return output;
};

const buildComponentFiles = ({ typeKey, kebabName, snakeName, shortcodeSlug }) => {
  const typeConfig = componentTypeMap[typeKey];
  const baseDir = path.join(COMPONENT_ROOT, typeConfig.folder, typeConfig.baseName);
  const targetDir = path.join(COMPONENT_ROOT, typeConfig.folder, kebabName);
  const basePhp = readFile(path.join(baseDir, `${typeConfig.baseName}.php`));
  const baseCss = readFile(path.join(baseDir, `${typeConfig.baseName}.css`));
  const baseJs = readFile(path.join(baseDir, `${typeConfig.baseName}.js`));

  const phpReplacements = {
    [typeConfig.baseName]: kebabName,
    "Base Layout Block": toTitleCase(kebabName),
    "Base Content Block": toTitleCase(kebabName),
    "Base Ui Component": toTitleCase(kebabName),
  };

  if (typeConfig.basePhpFunction) {
    phpReplacements[typeConfig.basePhpFunction] = `boilerplate_render_${snakeName}`;
  }

  const targetPhp = replaceTokens(basePhp, phpReplacements);

  const targetCss = replaceTokens(baseCss, {
    [typeConfig.baseName]: kebabName,
  });

  const targetJs = replaceTokens(baseJs, {
    [typeConfig.baseName]: kebabName,
    "base-content-block-ready": `${kebabName}-ready`,
    "base-layout-block-ready": `${kebabName}-ready`,
    "base-ui-component-ready": `${kebabName}-ready`,
  });

  const targetRegister = typeConfig.shortcodeTemplate(kebabName, shortcodeSlug, snakeName);

  return {
    targetDir,
    files: [
      { name: `${kebabName}.php`, content: targetPhp },
      { name: `${kebabName}.css`, content: targetCss },
      { name: `${kebabName}.js`, content: targetJs },
      { name: "register.php", content: targetRegister },
    ],
  };
};

async function run() {
  console.log("\nBoilerplate Update Generator\n----------------------------");
  console.log("Component types: layout, content-block, ui\n");

  const rawType = await ask("Component type: ");
  const normalizedType =
    rawType === "content-block" || rawType === "content-blocks"
      ? "content-block"
      : rawType.toLowerCase();

  if (!componentTypeMap[normalizedType]) {
    console.error("\nUnsupported type. Use layout, content-block, or ui.");
    rl.close();
    process.exit(1);
  }

  const rawName = await ask("Component name: ");
  const kebabName = toKebabCase(rawName);

  if (!kebabName) {
    console.error("\nA valid kebab-case name is required.");
    rl.close();
    process.exit(1);
  }

  const rawShortcodeAnswer = (await ask("Create shortcode support? (y/n): ")).toLowerCase();
  const shouldCreateShortcode = rawShortcodeAnswer === "y" || rawShortcodeAnswer === "yes";
  const shortcodeSlug = shouldCreateShortcode
    ? toSnakeCase((await ask(`Shortcode slug [${toSnakeCase(kebabName)}]: `)) || kebabName)
    : toSnakeCase(kebabName);

  const snakeName = toSnakeCase(kebabName);
  const { targetDir, files } = buildComponentFiles({
    typeKey: normalizedType,
    kebabName,
    snakeName,
    shortcodeSlug,
  });

  if (fs.existsSync(targetDir)) {
    console.error(`\nTarget already exists: ${path.relative(ROOT, targetDir)}`);
    rl.close();
    process.exit(1);
  }

  ensureDirectory(targetDir);
  files.forEach((file) => writeFile(path.join(targetDir, file.name), file.content));

  console.log("\nCreated:");
  files.forEach((file) => {
    console.log(`- ${path.relative(ROOT, path.join(targetDir, file.name))}`);
  });

  console.log("\nNotes:");
  console.log(`- Base template source: components/${componentTypeMap[normalizedType].folder}/${componentTypeMap[normalizedType].baseName}/`);
  console.log(`- Direct template usage: get_template_part('components/${componentTypeMap[normalizedType].folder}/${kebabName}/${kebabName}', null, $args);`);

  if (normalizedType === "content-block") {
    console.log(`- Registered render function: boilerplate_render_${snakeName}($section)`);
    console.log(`- Layout key scaffolded: ${kebabName}`);
  }

  if (shouldCreateShortcode) {
    console.log(`- Shortcode scaffolded: [${shortcodeSlug}]`);
  }

  rl.close();
}

run().catch((error) => {
  console.error("\nGeneration failed.");
  console.error(error);
  rl.close();
  process.exit(1);
});
