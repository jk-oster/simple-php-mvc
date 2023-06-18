<?php

namespace SimpleMvc;

/**
 * Class View
 *
 * @example
 * View::render('home', ['title' => 'Home'], 'layout');
 *
 * View::startBuffer();
 * echo 'Hello World!';
 * View::renderBuffer('containerLayout');
 */
class View
{
    protected static array $data = [];
    protected static array $blocks = [];

    public static function getPageData(): void
    {
        self::$data = array_merge(self::$data, ['page' => $GLOBALS['PAGE_CONFIG']]);
    }

    public static function setData(array $data): void
    {
        self::$data = $data;
    }

    public static function assign(array $data): void
    {
        self::$data = array_merge(self::$data, $data);
    }

    public static function getData(): array
    {
        return self::$data;
    }

    /**
     * @param string $view - the view file to render
     * @param array $data - the data to pass to the view
     * @param $layout - the layout file to render the view in
     * @return string - returns the page content
     */
    public static function render(string $view, array $data = [], $layout = null): string
    {
        self::getPageData();
        $variables = array_merge(self::$data, $data);
        return _render($view, $variables, $layout);
    }

    /**
     *
     * @param string $view - the view file to render
     * @param array $data - the data to pass to the view
     * @param $layout - the layout file to render the view in
     * @return string - returns the page content
     */
    public static function renderBufferInto(string $view, array $data = [], $layout = null): string
    {
        self::getPageData();
        $variables = array_merge(self::$data, $data, ['content' => ob_get_clean()]);
        return _render($view, $variables, $layout);
    }

    /**
     * @return array - returns the data array that is used to render the page
     */
    public static function startBuffer(): array
    {
        self::getPageData();
        ob_start();
        return self::$data;
    }

    public static function startBlock(string $name): array
    {
        self::getPageData();
        ob_start();
        self::$blocks[$name] = '';
        return self::$data;
    }

    public static function endBlock(string $name): void
    {
        self::$blocks[$name] = ob_get_clean();
    }

    public static function getBlock(string $name): string
    {
        return self::$blocks[$name];
    }

    public static function renderBlock(string $name, string $layout = ''): void
    {
        echo self::$blocks[$name];
    }
}