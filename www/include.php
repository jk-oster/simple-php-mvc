<?php

use MyMicroBlog\Model\Domain\User;
use MyMicroBlog\Framework\DataBase;

$currentUser = [];

// Global function to access users
function getUserById($id): User|null
{
    return User::objectFrom((new Database())->getRow("SELECT * FROM user WHERE id= '$id';"));
}

// Global function isLoggedIn
function isLoggedIn(): bool
{
    return isset($_SESSION['user'], $_SESSION['role']);
}

// Global function getCurrentUser
function getCurrentUser(): User|null
{
    global $currentUser;
    if (!$currentUser) {
        $currentUser = getUserById($_SESSION['id']) ?: null;
    }
    return $currentUser;
}

/**
 * Simple PHP Templating function
 *
 * @param $names  - string|array Template names
 * @param $args   - Associative array of variables to pass to the template file.
 * @return string - Output of the template file. Likely HTML.
 */
function _template(mixed $names, array $args = array())
{
    // allow for single file names
    if (!is_array($names)) {
        $names = array($names);
    }
    // try to find the templates
    $template_found = false;
    foreach ($names as $name) {
        $file = PROJECT_NAME_PATH .  "Templates/" . $name . '.php';
        if (file_exists($file)) {
            $template_found = $file;
            // stop after the first template is found
            break;
        }
    }
    // fail if no template file is found
    if (!$template_found) {
        return '';
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

function _attr(string $tagOrType, array $args = [])
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

function _controller_action(string $action)
{
    return "<input type='hidden' name='action' value='$action'>";
}

function _tag(string $tag, array $args, bool $closingTag = true)
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
 */
function _html(string $format, array $args = []): string
{
    return htmlspecialchars(
        $args
            ? vsprintf(_($format), $args)
            : _($format)
    );
}

function _json($data): void
{
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
}

function _js_var($varname, $data, $type = "let"): void
{
    $json = json_encode($data);
    echo "<script>$type $varname =  $json;</script>";
}

function _code($code)
{
    echo "<pre style='white-space: pre-wrap;'>" . highlight_string($code, true) . "</pre>";
}

function _form_open($method = 'POST', $additionalAttributes = [])
{
    echo _tag('form', array_merge(['method' => $method], $additionalAttributes), false);
}

function _input($type, $additionalAttributes = [])
{
    echo _tag('input', array_merge($additionalAttributes, DEFAULT_INPUT_ATTRIBUTES[$type]), false);
}

/**
 * HTML dumper für PHP variables
 */
function _debug($mixed, bool $extended = false): void
{
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
}

function _print_var_name($var)
{
    // read backtrace
    $bt   = debug_backtrace();
    // read file
    $file = file($bt[0]['file']);
    // select exact print_var_name($varname) line
    $src  = $file[$bt[0]['line'] - 1];
    // search pattern
    $pat = '#(.*)' . __FUNCTION__ . ' *?\( *?(.*) *?\)(.*)#i';
    // extract $varname from match no 2
    $var  = preg_replace($pat, '$2', $src);
    // print to browser
    return trim($var);
}

function _sendResponse(int $statusCode = 200, mixed $data = null, array $additionalHttpHeaders = []): void
{
    // remove any string that could create an invalid JSON
    // such as PHP Notice, Warning, logs...
    if (ob_get_contents()) ob_clean();

    // this will clean up any previously added headers, to start clean
    header_remove();

    // Hook to add http headers
    if (function_exists('add_http_header')) {
        add_http_header();
    }

    header(HTTP_STATUS_CODE_MAPPING[$statusCode]);

    // Add argument headers
    if (is_array($additionalHttpHeaders) && count($additionalHttpHeaders)) {
        foreach ($additionalHttpHeaders as $httpHeader) {
            header($httpHeader);
        }
    }

    http_response_code($statusCode);

    // encode your PHP Object or Array into a JSON string.
    if ($data) {
        $encoded = "";
        try {
            $encoded = json_encode($data, JSON_THROW_ON_ERROR);
        }
        catch (\JsonException $e) {
            header_remove();
            header(HTTP_STATUS_CODE_MAPPING[500]);
            $encoded = json_encode(['error' => 'Something went wrong! Please contact support: ' . $e->getMessage()]);
        }
        header('Content-Type: application/json');
        echo $encoded;
    }

    // making sure nothing is added
    exit();
}
