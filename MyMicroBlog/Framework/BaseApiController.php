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
        // https://localhost/index.php/{MODULE_NAME}/{METHOD_NAME}?search={SEARCH_VALUE}
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->uriSegments = explode('/', $uri);
        parse_str($_SERVER['QUERY_STRING'], $this->queryParams);

        try {
            $this->jsonPostData = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
        }
        catch (\JsonException $e) {
            $this->jsonPostData = [];
        }

        // Initialize controller
        $this->initialize();

        // execute hook 'beforeDispatch'
        if (method_exists($this, 'beforeDispatch')) {
            $this->beforeDispatch();
        }

        // execute 'hook' canAccessDispatch
        if ($this->canAccessDispatch()) {
            // Select controller action corresponding to request action and execute it
            $this->dispatch();
        }

        // execute hook 'afterDispatch'
        if (method_exists($this, 'afterDispatch')) {
            $this->afterDispatch();
        }
    }

    // Executes controller action matching request action
    // If no action defined executes defaultAction
    public function dispatch($actionFnName = ''): void
    {
        if(!array_key_exists(4,$this->uriSegments) && method_exists($this, 'defaultAction')) {
            $this->defaultAction();
        }
        else {
            $actionFunctionName = $actionFnName === '' ? $this->uriSegments[4] . "Action" : $actionFnName;
            $this->{$actionFunctionName}();
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
        $this->sendOutput('', 404, ['HTTP/1.1 404 Not Found']);
    }

    /**
     * Send API output .returnJsonHttpResponse
     */
    protected function sendOutput(mixed $data = null, int $statusCode = 200, array $httpHeaders = ['HTTP/1.1 200 OK']): void
    {
        // remove any string that could create an invalid JSON
        // such as PHP Notice, Warning, logs...
        ob_clean();

        // this will clean up any previously added headers, to start clean
        header_remove();

        // Hook to add http headers
        if (function_exists('add_http_header')) {
            add_http_header();
        }

        // Add argument headers
        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }

        // Set your HTTP response code, 2xx = SUCCESS,
        // anything else will be error, refer to HTTP documentation
        http_response_code($statusCode);

        // encode your PHP Object or Array into a JSON string.
        // stdClass or array
        echo $data;

        // making sure nothing is added
        exit();
    }
}