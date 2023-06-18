<?php

namespace SimpleMvc;

/**
 * Class Request
 *
 * This class represents the HTTP request object.
 *
 * @example
 * $request = Request::getInstance();
 * $request->get('id');
 * $request->post('name');
 * $request->files('avatar');
 * $request->server('REQUEST_METHOD');
 * $request->header('Content-Type');
 * $request->all();
 */
class Request implements \JsonSerializable
{
    private static $instance;
    private $get;
    private $post;
    private $cookies;
    private $files;
    private $headers;
    private $server;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->server = $_SERVER;
        $this->headers = getallheaders();
        $this->cookies = $_COOKIE;
    }

    public function server($key, $default = null)
    {
        return $this->server[$key] ?? $default;
    }

    public function get($key, $default = null)
    {
        return $this->get[$key] ?? $default;
    }

    // return request headers by key
    public function header($key, $default = null)
    {
//        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
//        return $this->server[$key] ?? $default;
        return $this->headers[$key] ?? $default;
    }

    public function cookie($key, $default = null)
    {
        return $this->cookies[$key] ?? $default;
    }

    public function post($key, $default = null)
    {
        return $this->post[$key] ?? $default;
    }

    public function files($key, $default = null)
    {
        return $this->files[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->get, $this->post);
    }

    public function param($key, $default = null)
    {
        return $this->all()[$key] ?? $default;
    }

    public function has($key): bool
    {
        return isset($this->all()[$key]);
    }

    public function only(array $keys): array
    {
        $params = $this->all();
        $filtered = [];
        foreach ($keys as $key) {
            $filtered[$key] = $params[$key] ?? null;
        }
        return $filtered;
    }

    public function except(array $keys): array
    {
        $params = $this->all();
        $filtered = [];
        foreach ($params as $key => $value) {
            if (!in_array($key, $keys)) {
                $filtered[$key] = $value;
            }
        }
        return $filtered;
    }

    public function method(): string
    {
        return $this->server('REQUEST_METHOD');
    }

    public function uri(): string
    {
        $uri = $this->server('REQUEST_URI');
        if (empty($uri)) {
            $uri = '/';
        }
        return str_replace(BASE_URI, '', $uri); // remove the base uri (if any)
    }

    public function route(): string
    {
        $uri = $this->uri();
        // Check if URI starts with a slash, if not prepend one
        $uri = str_starts_with($uri, '/') ? $uri : '/' . $uri;
        return explode('?', $uri)[0];
    }

    public static function getInstance(): Request
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function jsonSerialize(): array
    {
        return array_merge([
            'route' => $this->route(),
            'method' => $this->method(),
            'params' => $this->all(),
        ], get_object_vars($this));
    }
}