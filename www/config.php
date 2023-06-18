<?php
const ENVIRONMENT = 'development';
const PROJECT_ROOT_PATH =  __DIR__;
const PUBLIC_PATH = PROJECT_ROOT_PATH . '/public';
const PUBLIC_VIEWS_PATH = PROJECT_ROOT_PATH . '/views/www';
const VERSION = '0.1.0';
const APP_NAME = 'Simple MVC Framework';
const BASE_URI = '/hue5mvc2';
const BASE_URL = 'http://localhost' . BASE_URI;
const DEFAULT_LANGUAGE_LOCAL = 'de_DE';

// Database
const DB_HOST = 'localhost';
const DB_USER = 'swpue';
const DB_PASSWORD = 'swpue';
const DB_NAME = 'swp4_ue05_osterberger';

// Page
$GLOBALS['PAGE_CONFIG'] = SimpleMvc\PageConfig::from([
    'title' => APP_NAME,
    'language' => DEFAULT_LANGUAGE_LOCAL,
    'logo' => BASE_URL . '/public/images/logo.png',
    'meta_description' => 'Simple MVC Framework',
    'meta_keywords' => [
        'mvc',
        'SimpleMvc',
        'php',
    ],
    'meta_author' => 'JKOSTER',
    'styles' => [
        'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css',
        BASE_URL . '/public/css/style.css',
    ],
    'scripts' => [
        BASE_URL . '/public/js/script.js',
    ],
    'header_navigation' => [
        [
            'url' => BASE_URL . '/',
            'label' => 'Home',
        ],
        [
            'url' => BASE_URL . '/about',
            'label' => 'About',
        ],
        [
            'url' => BASE_URL . '/contact',
            'label' => 'Contact',
        ],
    ],
    'footer_navigation' => [
        [
            'url' => BASE_URL . '/',
            'label' => 'Home',
        ],
        [
            'url' => BASE_URL . '/about',
            'label' => 'About',
        ],
        [
            'url' => BASE_URL . '/contact',
            'label' => 'Contact',
        ],
    ],
]);

// Templating
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
