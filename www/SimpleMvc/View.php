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

    /**
     * Merges the page configuration into the data array.
     * 
     * @return void
     */
    public static function getPageData(): void
    {
        self::$data = array_merge(self::$data, ['page' => $GLOBALS['PAGE_CONFIG']]);
    }

    /**
     * Sets the data for the view.
     * 
     * @param array $data - The data to be set for the view.
     * @return void
     */
    public static function setData(array $data): void
    {
        self::$data = $data;
    }

    /**
     * Merges the provided data into the existing data array.
     * 
     * @param array $data - The data to be merged into the existing data array.
     * @return void
     */
    public static function assign(array $data): void
    {
        self::$data = array_merge(self::$data, $data);
    }

    /**
     * Returns the data array.
     * 
     * @return array - The data array.
     */
    public static function getData(): array
    {
        return self::$data;
    }

    /**
     * Renders the view with the provided data and layout.
     * 
     * @param string $view - The view file to render.
     * @param array $data - The data to pass to the view.
     * @param $layout - The layout file to render the view in.
     * @return string - The rendered page content.
     */
    public static function render(string $view, array $data = [], $layout = null): string
    {
        self::getPageData();
        $variables = array_merge(self::$data, $data);
        return _render($view, $variables, $layout);
    }

    /**
     * Renders the view with the provided data and layout, and the current output buffer as content.
     * 
     * @param string $view - The view file to render.
     * @param array $data - The data to pass to the view.
     * @param $layout - The layout file to render the view in.
     * @return string - The rendered page content.
     */
    public static function renderBufferInto(string $view, array $data = [], $layout = null): string
    {
        self::getPageData();
        $variables = array_merge(self::$data, $data, ['content' => ob_get_clean()]);
        return _render($view, $variables, $layout);
    }

    /**
     * Starts output buffering and returns the data array.
     * 
     * @return array - The data array.
     */
    public static function startBuffer(): array
    {
        self::getPageData();
        ob_start();
        return self::$data;
    }

    /**
     * Starts a new output buffer block with the provided name and returns the data array.
     * 
     * @param string $name - The name of the block.
     * @return array - The data array.
     */
    public static function startBlock(string $name): array
    {
        self::getPageData();
        ob_start();
        self::$blocks[$name] = '';
        return self::$data;
    }

    /**
     * Ends the current output buffer block and stores its content in the block with the provided name.
     * 
     * @param string $name - The name of the block.
     * @return void
     */
    public static function endBlock(string $name): void
    {
        self::$blocks[$name] = ob_get_clean();
    }

    /**
     * Returns the content of the block with the provided name.
     * 
     * @param string $name - The name of the block.
     * @return string - The content of the block.
     */
    public static function getBlock(string $name): string
    {
        return self::$blocks[$name];
    }

    /**
     * Outputs the content of the block with the provided name.
     * 
     * @param string $name - The name of the block.
     * @param string $layout - The layout to render the block in.
     * @return void
     */
    public static function renderBlock(string $name, string $layout = ''): void
    {
        echo self::$blocks[$name];
    }
}