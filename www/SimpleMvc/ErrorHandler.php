<?php

namespace SimpleMvc;

/**
 * Class ErrorHandler
 *
 * This class handles errors and exceptions and needs to be initialized in the index.php
 * It is used by the application to display errors and exceptions in a user-friendly and readable way.
 */
class ErrorHandler {

    private Request $request;
    private Response $response;

    private static ?ErrorHandler $instance = null;
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;

        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
    }

    public static function getInstance(Request $request = null, Response $response = null): ErrorHandler
    {
        if (self::$instance === null) {
            self::$instance = new self($request, $response);
        }

        return self::$instance;
    }

    public function handleError($errno, $errstr, $errfile, $errline): void
    {
        $this->handleException(new \ErrorException($errstr, 0, $errno, $errfile, $errline));
    }

    public function handleException($e): void
    {
        $this->logError($e);
        $this->displayError($e);
        exit();
    }

    private function logError($e): void
    {
        error_log($e);
    }

    private function getHtmlError($e): string
    {
        $html = '<h1>Something went wrong</h1>';
        $html .= '<p>' . $e->getMessage() . '</p>';
        $html .= '<p>' . $e->getFile() . ' on line ' . $e->getLine() . '</p>';
        $html .= '<pre>' . $e->getTraceAsString() . '</pre>';
        return $html;
    }

    private function getJsonError($e): array
    {
        return [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ];

    }

    private function displayError($e): void
    {
        if (ENVIRONMENT === 'development') {
            $this->sendErrorResponse($e);
        } else {
            echo 'Something went wrong';
        }
    }

    private function sendErrorResponse($e): void
    {
        if($this->request->header('Accept') === 'application/json') {
            $this->response->sendJsonError(404, $this->getJsonError($e));
        } else {
            $this->response->sendError(500, $this->getHtmlError($e));
        }
    }
}