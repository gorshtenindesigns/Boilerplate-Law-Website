<?php

namespace LLG\Boilerplate\Content;

class ContentContext
{
    /**
     * @var array<string, mixed>
     */
    private $hero = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    private $sections = [];

    /**
     * @var string
     */
    private $layout = '';

    /**
     * @var array<int, string>
     */
    private $injectComponents = [];

    /**
     * @var int|null
     */
    private $postId = null;

    public function __construct(array $data = [])
    {
        $this->setHero($data['hero'] ?? []);
        $this->setSections($data['sections'] ?? []);
        $this->setLayout($data['layout'] ?? '');
        $this->setInjectComponents($data['injectComponents'] ?? []);
        $this->setPostId($data['postId'] ?? null);
    }

    public function getHero()
    {
        return $this->hero;
    }

    public function setHero($hero)
    {
        $this->hero = is_array($hero) ? $hero : [];

        return $this;
    }

    public function getSections()
    {
        return $this->sections;
    }

    public function setSections($sections)
    {
        $this->sections = is_array($sections) ? array_values($sections) : [];

        return $this;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function setLayout($layout)
    {
        $this->layout = is_string($layout) ? sanitize_key($layout) : '';

        return $this;
    }

    public function getInjectComponents()
    {
        return $this->injectComponents;
    }

    public function setInjectComponents($injectComponents)
    {
        $components = is_array($injectComponents) ? $injectComponents : [];
        $this->injectComponents = array_values(array_filter(array_map(function ($component) {
            return is_string($component) ? sanitize_title($component) : '';
        }, $components)));

        return $this;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function setPostId($postId)
    {
        $postId = is_numeric($postId) ? (int) $postId : 0;
        $this->postId = $postId > 0 ? $postId : null;

        return $this;
    }
}
