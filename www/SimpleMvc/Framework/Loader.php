<?php

namespace SimpleMvc\Framework;

class Loader
{
    // Start PHP Session
    public static function initSession($id = DEFAULT_SESSION_ID) {
        session_id($id);
        session_start();
    }

    // Set language key for translation
    public static function initLanguageSettings($locales = DEFAULT_LANGUAGE_LOCAL) {
        setlocale(LC_MESSAGES, $locales);
    }

    // Run SimpleMvc
    public static function launchSimpleMvc() {
        // Global error variable
        $aErrors = [];
        // Load DB config
        require_once(__DIR__ . "/../../baseConfig.php");
        require_once(__DIR__ . "/../config.php");
        require_once(__DIR__ . "/GlobalFunctions.php");

        self::initSession();

        self::initLanguageSettings();

        self::loadFramework();

        // Load global functions
    }

    // Autoload Class Files from SimpleMvc Framework
    public static function loadFramework() {
        spl_autoload_register(
            static function ($pClassName) {
                if(str_contains($pClassName, 'SimpleMvc')){
                    // Change ClassPath to FilePath
                    require_once(PROJECT_ROOT_PATH . "/" . str_replace("\\", "/", $pClassName) . '.php');
                }
            }
        );
    }

    public static function load($filePaths = []) 
    {
        foreach($filePaths as $filePath) {
            require_once($filePath);
        }
    }
}

Loader::launchSimpleMvc();
