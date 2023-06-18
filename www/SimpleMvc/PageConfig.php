<?php

namespace SimpleMvc;

/**
 * Class PageData
 *
 * Contains all configuration data that is needed for the page to be rendered
 *
 * @example
 * $page = new \SimpleMvc\PageData();
 * $page->title = 'Home';
 */
class PageConfig extends BaseModel
{
    public int $id;
    public string $title;
    public string $url;
    public string $language;
    public string $logo;
    public string $meta_description;
    public array $meta_keywords;
    public string $meta_author;

    public array $styles;
    public array $scripts;
    public array $header_navigation;
    public array $footer_navigation;

    public function getCurrentNavigation(string $type = 'header_navigation'): array
    {
        $current = \SimpleMvc\Request::getInstance()->route();
        foreach ($this->{$type} as $item) {
            if ($item['url'] === $current) {
                return $item;
            }
        }
        return [];
    }

    public function addScript(string $scriptPath): void
    {
        if (!in_array($scriptPath, $this->scripts) && file_exists($scriptPath)) {
            $this->scripts[] = $scriptPath;
            return;
        }
        throw new \RuntimeException('Script not found');
    }

    public function addStyle(string $stylePath): void
    {
        if (!in_array($stylePath, $this->styles) && file_exists($stylePath)) {
            $this->styles[] = $stylePath;
            return;
        }
        throw new \RuntimeException('Style not found');
    }
}