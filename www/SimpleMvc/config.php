<?php

const PROJECT_ROOT_PATH =  __DIR__;
const PROJECT_NAME_PATH = PROJECT_ROOT_PATH . '\/SimpleMvc\/';

const DEFAULT_LANGUAGE_LOCAL = 'de_DE';
const DEFAULT_SESSION_ID = 'jkoster';

define('DEFAULT_ACTION', $_SERVER['PHP_SELF']);

const DEFAULT_TEMPLATING_ATTRIBUTES = [
    'img' => [
        'alt' => '',
        'loading' => 'lazy',
        'height' => '',
        'width' => '',
    ],
    'a' => [
        'target' => '',
        'title' => '',
        'href' => '#',
    ],
    'form' => [
        'action' => DEFAULT_ACTION,
        'method' => 'POST',
        'autocomplete' => 'on',
    ],
];

const DEFAULT_INPUT_ATTRIBUTES = [
    'email' => [
        'type' => 'email',
        'pattern' => '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$',
        'placeholder' => 'hello@mail.com',
        'autocomplete' => 'username'
    ],
    'password' => [
        'type' => 'password',
        'pattern' => '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}',
        'autocomplete' => 'current-password',
        'title' => 'Your password must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters to be secure'
    ],
    'tel' => [
        'type' => 'tel',
        'pattern' => '[0-9]{2}-[0-9]{3,}-[0-9]{4,}',
        'placeholder' => '43-7717-7890',
        'autocomplete' => 'tel',
        'title' => 'The phone number must look like the following pattern: 43-664-30584727 or 43-7727-5729'
    ],
    'range' => [
        'type' => 'range',
        'min'=>"0", 
        'max'=>"50"
    ],
    'number' => [
        'type' => 'number',
        'min' => "0", 
        'max' => "100", 
        'step' => "1",
    ],
    'url' => [
        'type' => 'url',
        'autocomplete'=>"url", 
        'placeholder'=>"https://website.com", 
        'pattern'=>"https?://.+",
        'title' => 'The url must start with https:// or http://'
    ],

];

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
