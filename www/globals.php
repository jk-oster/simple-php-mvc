<?php

use SimpleMvc\Collection;

function _collect(array $array = []): Collection
{
    return new Collection($array);
}

function _get_public_url(string $path): string
{
    // if string starts with (./) or (/) or (../) or (http://) or (https://) turn it into a '/'
    preg_replace('/^(\.\/|\/|\.+\/|https?:\/\/)/', '/', $path);
    return BASE_URL . $path;
}

/**
 * Simple PHP Templating function
 *
 * @param $names  - string|array Template names
 * @param $args   - Associative array of variables to pass to the template file.
 * @return string - Output of the template file. Likely HTML.
 */
function _template(mixed $names, array $args = array()): string|false
{
    // allow for single file names
    if (!is_array($names)) {
        $names = array($names);
    }
    // try to find the templates
    $template_found = false;
    foreach ($names as $name) {
        $file = PROJECT_ROOT_PATH . "/views/" . $name . '.php';
        if (file_exists($file)) {
            $template_found = $file;
            // stop after the first template is found
            break;
        }
    }
    // fail if no template file is found
    if (!$template_found) {
        _debug($args);
        throw new RuntimeException('No template file was found for: ' . implode(', ', $names));
    }
    // Make values in the associative array easier to access by extracting them
    if (is_array($args)) {
        extract($args);
    }
    // buffer the output (including the file is "output")
    ob_start();
    include $template_found;
    return ob_get_clean();
}

/**
 * Renders a template within a layout template
 * @param string $template
 * @param array $args
 * @param string|null $layout
 * @return string
 */
function _render(string $template, array $args = [], string $layout = null): string
{
    if ($layout) {
        return _template($layout, [...$args, 'content' => _template($template, $args)]);
    }
    return _template($template, $args);
}

function _attr(string $tagOrType, array $args = []): string
{
    $attributes = $args;
    if (array_key_exists($tagOrType, DEFAULT_TEMPLATING_ATTRIBUTES)) $attributes = array_unique(array_merge($attributes, DEFAULT_TEMPLATING_ATTRIBUTES[$tagOrType]));
    if (array_key_exists($tagOrType, DEFAULT_INPUT_ATTRIBUTES)) $attributes = array_unique(array_merge($attributes, DEFAULT_INPUT_ATTRIBUTES[$tagOrType]));
    $output = "";
    foreach ($attributes as $attr => $value) {
        if ($attr != 'html' && $attr != 'text') $output .= " $attr='$value' ";
        if ($attr == 'src' || $attr == 'href') $output .= " $attr='" . urlencode($value) . "' ";
    }
    return $output;
}

function _controller_action(string $action): string
{
    return "<input type='hidden' name='action' value='$action'>";
}

function _tag(string $tag, array $args, bool $closingTag = true): string
{
    $output = "<$tag ";
    $output .= _attr($tag, $args);
    $output .= $closingTag ? ">" : "/>";
    if (array_key_exists('text', $args)) $output .= _html($args['text']);
    if (array_key_exists('html', $args)) $output .= _html($args['html']);
    if ($closingTag) $output .= "</$tag>";
    return $output;
}

/**
 * Alias for `vsprintf`, with HTML escaping and translation
 * Usage: _html('Hello %s', ['World']); -> Hello World
 * @param string $format
 * @param array $args
 */
function _html(string $format, array $args = []): string
{
    return htmlspecialchars(
        $args
            ? vsprintf(_($format), $args)
            : _($format)
    );
}

function _js_var($varname, $data, $type = "let"): string
{
    $json = json_encode($data);
    return "<script>$type $varname =  $json;</script>";
}

function _code($code): string
{
    return "<pre style='white-space: pre-wrap;'>" . highlight_string($code, true) . "</pre>";
}

function _form_open($method = 'POST', $additionalAttributes = []): string
{
    return _tag('form', array_merge(['method' => $method], $additionalAttributes), false);
}

function _input($type, $additionalAttributes = []): string
{
    return _tag('input', array_merge($additionalAttributes, DEFAULT_INPUT_ATTRIBUTES[$type]), false);
}

/**
 * HTML dumper für PHP variables
 */
function _debug($mixed, bool $extended = false, bool $die = false): void
{
    if(ENVIRONMENT === 'production') {
        return;
    }

    $bt = debug_backtrace();
    $caller = array_shift($bt);
    echo "<details class='debug' style='margin: 1em 0; border: 1px solid red; background: white; color: #000; padding: 1em; max-width: 100vw; padding: 1em;'>
    <summary>Debug at line: <strong>" . $caller['line'] . "</strong> in file: <strong>" .  $caller['file'] . "</strong></summary><p>";
    echo ('<pre style="white-space: pre-wrap;">');
    echo htmlspecialchars(
        $extended
            ? var_export($mixed, true)
            : print_r($mixed, true)
    );
    echo ('</pre></p></details>');

    // Stop the script if needed
    if ($die) {
        die();
    }
}

// Debug and die
function _dd($mixed, bool $extended = false): void
{
    _debug($mixed, $extended, true);
}