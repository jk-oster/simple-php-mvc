<?php

namespace MyMicroBlog\Framework;

abstract class BaseApiController
{
    protected string $reqMethod = '';
    protected array $uriSegments = [];
    protected array $queryParams = [];
    protected array $jsonPostData = [];

    public function __construct()
    {
        $this->reqMethod = $_SERVER['REQUEST_METHOD'];
        // https://localhost/api.php/{MODULE_NAME}/{METHOD_NAME}?search={SEARCH_VALUE}
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->uriSegments = explode('/', $uri);
        parse_str($_SERVER['QUERY_STRING'], $this->queryParams);

        $phpInput = file_get_contents('php://input');
        if ($phpInput) {
            try {
                $this->jsonPostData = json_decode($phpInput, true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                $this->sendResponse(400);
            }
        }

        // Initialize controller
        $this->initialize();

        // execute hook 'beforeDispatch'
        if (method_exists($this, 'beforeDispatch')) {
            $this->{'beforeDispatch'}();
        }

        // execute 'hook' canAccessDispatch
        if ($this->canAccessDispatch()) {
            // Select controller action corresponding to request action and execute it
            $this->dispatch();
        }

        // execute hook 'afterDispatch'
        if (method_exists($this, 'afterDispatch')) {
            $this->{'afterDispatch'}();
        }
    }

    // Executes controller action matching request action
    // If no action defined executes defaultAction
    public function dispatch($routeFnName = ''): void
    {
        if (!array_key_exists(3, $this->uriSegments) && method_exists($this, 'baseRoute')) {
            $this->baseRoute();
        } else {
            $routeFunctionName = ($routeFnName === '' ? $this->uriSegments[3] : $routeFnName) . "Route";
            $this->{$routeFunctionName}();
        }
    }

    /**
     * Overwrite to handle access limitations
     * @return bool if dispatch is allowed
     */
    protected function canAccessDispatch(): bool
    {
        return true;
    }

    /**
     * Initialize controller variables here
     * @return void
     */
    abstract protected function initialize(): void;

    /**
     * __call magic method.
     * called when you try to call a method that doesn't exist
     */
    public function __call($name, $arguments)
    {
        $this->sendResponse(404);
    }

    /**
     * Send API output .returnJsonHttpResponse
     */
    protected function sendResponse(int $statusCode = 200, mixed $data = null, array $additionalHttpHeaders = [])
    {
        _sendResponse($statusCode, $data, $additionalHttpHeaders);
    }
}
