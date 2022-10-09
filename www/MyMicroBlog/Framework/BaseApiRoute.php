<?php

namespace MyMicroBlog\Framework;

class BaseApiRoute
{
    public function __construct($method)
    {
        try {
            $data = [];
            $data = $this->{strtolower($method)}();
            $json = json_encode($data, JSON_THROW_ON_ERROR);
            _sendResponse(200, $json,  ['Content-Type: application/json']);
        } catch (\JsonException $e) {
            $errorJson = json_encode(
                ['error' => $e->getMessage() . 'Something went wrong! Please contact support.'],
                JSON_THROW_ON_ERROR
            );
            _sendResponse(500, $errorJson);
        }
    }

    public function get()
    {
        _sendResponse(405);
    }

    public function post()
    {
        _sendResponse(405);
    }

    public function put()
    {
        _sendResponse(405);
    }

    public function patch()
    {
        _sendResponse(405);
    }

    public function delete()
    {
        _sendResponse(405);
    }
}
