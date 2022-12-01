<?php

const PROJECT_ROOT_PATH =  __DIR__;
const PROJECT_NAME = 'SimpleMvc';
const PROJECT_NAME_PATH = PROJECT_ROOT_PATH  . "/" . PROJECT_NAME . "/";
const SIMPLE_MVC_DEV_MODE = true;

define('DEFAULT_ACTION', $_SERVER['PHP_SELF']);

const HTTP_STATUS_CODE_MAPPING = [
    200 => 'HTTP/1.1 200 OK',
    400 => 'HTTP/1.1 400 Bad Request',
    401 => 'HTTP/1.1 401 Unauthorized',
    403 => 'HTTP/1.1 403 Forbidden',
    404 => 'HTTP/1.1 404 Not Found',
    405 => 'HTTP/1.1 405 Method Not Allowed',
    422 => 'HTTP/1.1 422 Unprocessable Entity',
    500 => 'HTTP/1.1 500 Internal Server Error',
    501 => 'HTTP/1.1 501 Not Implemented',
];

// Global error variable
$GLOBALS['aErrors'] = [];