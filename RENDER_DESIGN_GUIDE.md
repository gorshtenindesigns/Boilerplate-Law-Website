# Render Design Guide

## Purpose

This guide now covers the neutral base rendering model.

## Content Blocks

Parsed sections should render through:

- `boilerplate_render_base_content_block($section)`

If a new section renderer is created later, it should still begin from:

- `components/content-blocks/base-content-block/`

## Layout Components

Use layout components for non-semantic page shells such as:

- hero wrappers
- section intros
- footer shells
- archive intros

All new layout components must start from:

- `components/layout/base-layout-block/`

## UI Components

Use UI components for small reusable pieces such as:

- buttons
- chips
- tags
- modal triggers

All new UI components must start from:

- `components/ui/base-ui-component/`

## Generator Workflow

Use `node generate.js` and choose:

- `layout`
- `content-block`
- `ui`

The generator writes the new component into the correct directory and scaffolds its `register.php`.
