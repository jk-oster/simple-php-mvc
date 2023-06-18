<?php

namespace SimpleMvc;

/**
 * Class BaseController
 *
 * This class represents the controller class to be used by the router and specific routes.
 * The controller is responsible for handling the request, processing it and returning a response.
 */
abstract class BaseController
{
    protected Request $request;
    protected Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;

        $this->initialize();
    }

    // Load necessary repositories
    abstract public function initialize():void;

}