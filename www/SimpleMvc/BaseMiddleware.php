<?php

namespace SimpleMvc;

/**
 * Interface BaseMiddleware
 *
 * This interface represents the base middleware class to be used by the router and specific routes.
 */
interface BaseMiddleware
{

    // to be implemented -> e.g., throws an exception if the request is not valid or unauthorized
    public static function handle(Request $request, Response $response): void;
}