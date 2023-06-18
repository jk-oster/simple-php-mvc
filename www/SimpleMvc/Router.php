<?php

namespace SimpleMvc;

/**
 * Class Router
 *
 * Registers the routes and the middlewares.
 *
 * Responsible for routing the request to the correct controller and method.
 * Dispatches controller action based on the request URI
 * - if a route is registered for the URI it is dispatched to the controller and method
 * - if no route is registered for the URI it is checked if a routeString view exists in the "views/www" folder
 * - if no routeString view exists a 404 response is sent
 *
 * @example
 * $router = Router::getInstance();
 * $router->addRoutes(...$routes);
 */
class Router
{

    private Request $request;
    private Response $response;

    private static ?Router $instance = null;
    private array $middlewares = [];
    private array $routes = [];
    private array $blacklist = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public static function getInstance(Request $request = null, Response $response = null): Router
    {
        if (self::$instance === null) {
            self::$instance = new self($request, $response);
        }

        return self::$instance;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getBlacklist(): array
    {
        return $this->blacklist;
    }

    public function findRoute(string $verb, string $route, string $action = 'default'): mixed
    {
        $routesCollection = Collection::collect($this->routes);
        return $routesCollection->filter(function ($currentRoute) use ($verb, $route, $action) {
            return $currentRoute->matches($verb, $route, $action);
        })->first();
    }

    /**
     * Routes that should not be matched
     * @param string $route
     * @return void
     */
    public function addBlacklist(string $route): void
    {
        $this->blacklist[] = $route;
    }

    /**
     * Add a routes to the router
     * @param Route $routes - array of Route objects
     * @return void
     */
    public function addRoutes(...$routes): void
    {
        foreach ($routes as $route) {
            $this->routes[$route->getIdentifier()] = $route;
        }
    }

    public function addMiddlewares(...$middlewares): void
    {
        foreach ($middlewares as $middleware) {
            $this->middlewares[] = $middleware;
        }
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares ?? [];
    }

    private function runMiddlewares(Request $request, Response $response): void
    {
        foreach ($this->middlewares as $middleware) {
            // Call the middleware
            $middleware::handle($request, $response);
        }
    }

    /**
     * @param string $identifier - e.g., 'GET:/home:default'
     * @return Route
     */
    public function getRouteByIdentifier(string $identifier): Route
    {
        return $this->routes[$identifier];
    }

    /**
     * Dispatches controller action based on the request URI
     *
     * Considers the following Request params:
     * - action
     * - controller
     * - ignore_template
     * - ignore_route
     *
     * Dispatching logic:
     * - if a route is registered for the URI it is dispatched to the controller and method
     * - if a controller and action is passed as params, and it exists it is dispatched to the controller and method
     * - if a public view template matches the URI it is rendered
     * - if nothing matches a 404 response is sent
     */
    public function handle(Request $request): void
    {
        $routeString = $request->route();
        $verb = $request->method();
        $action = $request->param('action', 'default');
        $controller = $request->param('controller', '\App\DefaultController');
        $ignore_template = $request->param('ignore_template', false);

        // check if the route is blacklisted
        if ($this->isBlacklisted($routeString)) {
            $this->sendErrorResponse('Route blacklisted: ' . $routeString);
        }

        // run the global middleware
        $this->runMiddlewares($request, $this->response);

        // check if the route exists
        $route = $this->findRoute($verb, $routeString, $action);
        if ($route) {
            // Dispatch the route -> Instantiate the controller and call the method
            $route->dispatch($request, $this->response);
            return;
        }

        // ?controller=\App\Controllers\WebController&action=test&ignore_template=true

        // check if the controller and action exists
        if ($this->checkControllerActionExists($controller, $action)) {
            // call the controller action directly
            $controller = new $controller($request, $this->response);
            $controller->$action();
            return;
        }

        // check if the public template exists
        $templateName = $this->getPublicTemplateNameFromRoute($routeString);
        if (!$ignore_template && $this->checkPublicTemplateExists($routeString)) {
            // render the public template
            $this->response->sendView('www/' . $templateName, $request->all());
            return;
        }

        $this->sendErrorResponse("No Route, PublicTemplate or ControllerAction found: '$routeString' with controller '$controller', action '$action' and routeString '$templateName'");
    }

    private function isBlacklisted(string $route): bool
    {
        return in_array($route, $this->blacklist, true);
    }

    /**
     * Check if the controller and action exists
     * @param string $controller
     * @param string $action
     * @return bool
     */
    private function checkControllerActionExists(string $controller, string $action = 'index'): bool
    {
        return class_exists($controller) && method_exists($controller, $action);
    }

    /**
     * Check if the routeString exists in the "views/www" folder,
     * if is empty or '/' it will check for the "index.php" routeString in the "views/www" folder
     * @param string $routeString
     * @return bool
     */
    private function checkPublicTemplateExists(string $routeString): bool
    {
        return file_exists(PUBLIC_VIEWS_PATH . '/' . $this->getPublicTemplateNameFromRoute($routeString) . '.php');
    }

    private function getPublicTemplateNameFromRoute(string $routeString): string
    {
        if ($routeString === '/' || $routeString === '') {
            return 'index';
        }
        return $routeString;
    }

    private function sendErrorResponse(string $message, $code = 404): void
    {
        if ($this->request->header('Accept') === 'application/json') {
            $this->response->sendJsonError($code, $message);
        } else {
            $this->response->sendError($code, $message);
        }
    }
}
