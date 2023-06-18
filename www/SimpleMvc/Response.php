<?php

namespace SimpleMvc;

/**
 * Class Response
 *
 * This class represents the HTTP response.
 *
 * @example
 * $response = Response::getInstance();
 * $response->setStatusCode(200);
 * $response->addHeader('Location: /');
 * $response->setContentType('text/html');
 * $response->output('Hello world!');
 * $response->sendError(404, 'Page not found');
 */
class Response
{
    private static ?Response $instance = null;

    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    public function addHeader(string $header): void
    {
        header($header);
    }

    public function setContentType(string $type): void
    {
        header('Content-Type: ' . $type);
    }

    public function sendError($code, $message): void
    {
        $this->setStatusCode($code);
        $this->setContentType('text/html');
        $this->output($message);
        exit();
    }

    public function sendJsonError($code, $message): void
    {
        $this->setStatusCode($code);
        $this->setContentType('application/json');
        $this->output(json_encode(['error' => $message]));
        exit();
    }

    public function sendJson($data, $code = 200): void
    {
        $this->setStatusCode($code);
        $this->setContentType('application/json');

        $encoded = "";
        try {
            $encoded = json_encode($data, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->sendJsonError(500, $e->getMessage());
        }
        $this->output($encoded);
        exit();
    }

    public function sendHtml($data, $code = 200): void
    {
        $this->setStatusCode($code);
        $this->setContentType('text/html');
        $this->output($data);
        exit();
    }

    public function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit();
    }

    public function output(string $content): void
    {
        echo $content;
    }

    public function sendFile(string $path, string $type): void
    {
        $this->setContentType($type);
        $this->output(file_get_contents($path));
        exit();
    }

    public function sendFileDownload(string $path, string $type, string $filename): void
    {
        $this->setContentType($type);
        $this->addHeader("Content-Disposition: attachment; filename=$filename");
        $this->output(file_get_contents($path));
        exit();
    }

    public function sendView(string $view, array $data = [], $layout = null): void
    {
        $this->sendHtml(\SimpleMvc\View::render($view, $data, $layout));
    }

    public static function getInstance(): Response
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}