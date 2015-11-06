<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Application;

use SprykerEngine\Shared\Config;
use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Shared\Application\ApplicationConfig;
use SprykerFeature\Shared\Library\Autoloader;
use SprykerFeature\Shared\Library\Error\ErrorHandler;
use SprykerFeature\Shared\Library\TestAutoloader;
use SprykerFeature\Shared\System\SystemConfig;

class Environment
{

    /**
     * @var array
     */
    private static $fatalErrors = [
        E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR,
    ];

    /**
     * @param string $application
     * @param bool $disableApplicationCheck
     *
     * @throws \Exception
     *
     * @return void
     */
    public static function initialize($application, $disableApplicationCheck = false)
    {
        if (!file_exists(APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . '.class_map')) {
            echo 'Can\'t find a class map file. Please run "$ vendor/bin/build-class-map" to create a fresh class map.';
            exit;
        }

        self::defineEnvironment();
        self::defineStore();

        date_default_timezone_set('UTC');

        if (!defined('APPLICATION_SOURCE_DIR')) {
            if (!getenv('APPLICATION_SOURCE_DIR')) {
                define('APPLICATION_SOURCE_DIR', APPLICATION_ROOT_DIR . '/src');
            } else {
                define('APPLICATION_SOURCE_DIR', getenv('APPLICATION_SOURCE_DIR'));
            }
        }

        if (!defined('APPLICATION_STATIC_DIR')) {
            if (!getenv('APPLICATION_STATIC_DIR')) {
                define('APPLICATION_STATIC_DIR', APPLICATION_ROOT_DIR . '/static');
            } else {
                define('APPLICATION_STATIC_DIR', getenv('APPLICATION_STATIC_DIR'));
            }
        }

        if (!defined('APPLICATION_VENDOR_DIR')) {
            if (!getenv('APPLICATION_VENDOR_DIR')) {
                define('APPLICATION_VENDOR_DIR', APPLICATION_ROOT_DIR . '/vendor');
            } else {
                define('APPLICATION_VENDOR_DIR', getenv('APPLICATION_VENDOR_DIR'));
            }
        }

        if (!defined('APPLICATION_DATA')) {
            if (!getenv('APPLICATION_DATA')) {
                define('APPLICATION_DATA', APPLICATION_ROOT_DIR . '/data');
            } else {
                define('APPLICATION_DATA', getenv('APPLICATION_DATA'));
            }
        }

        self::initializeErrorHandler();

        require_once APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/Library/src/SprykerFeature/Shared/Library/Autoloader.php';

        Autoloader::unregister();
        Autoloader::register(APPLICATION_VENDOR_DIR . '/spryker/spryker', APPLICATION_VENDOR_DIR, $application, $disableApplicationCheck);
        TestAutoloader::register(APPLICATION_VENDOR_DIR . '/spryker/spryker', APPLICATION_VENDOR_DIR, $application, $disableApplicationCheck);

        $coreNamespaces = Config::get(SystemConfig::CORE_NAMESPACES);

        foreach ($coreNamespaces as $namespace) {
            Autoloader::allowNamespace($namespace);
        }

        ini_set('display_errors', Config::get(ApplicationConfig::DISPLAY_ERRORS, false));

        $store = Store::getInstance();
        $locale2 = $store->getCurrentLocale();
        $locale1 = $locale2 . '.UTF-8';

        // We set LC_NUMERIC hard to en_US so numeric conversion is always the same to avoid decimal point problems
        setlocale(LC_COLLATE, $locale1, $locale2);
        setlocale(LC_CTYPE, $locale1, $locale2);
        setlocale(LC_MONETARY, $locale1, $locale2);
        setlocale(LC_TIME, $locale1, $locale2);
        setlocale(LC_MESSAGES, $locale1, $locale2);
        setlocale(LC_NUMERIC, 'en_US.UTF-8', 'en_US');

        mb_internal_encoding('UTF-8');
        mb_regex_encoding('UTF-8');

        date_default_timezone_set($store->getTimezone());
    }

    /**
     * @return void
     */
    private static function defineEnvironment()
    {
        if (!defined('APPLICATION_ENV')) {
            $env = getenv('APPLICATION_ENV');
            if (!$env) {
                if (file_exists(APPLICATION_ROOT_DIR . '/config/Shared/console_env_local.php')) {
                    $env = require APPLICATION_ROOT_DIR . '/config/Shared/console_env_local.php';
                }
            }
            if (!$env) {
                echo 'Environment variable APPLICATION_ENV must be set. You can do this by adding e.g. APPLICATION_ENV=development in front of this command or by adding a file "config/Shared/console_env_local.php" containing the current environment.';
                exit(1);
            }
            define('APPLICATION_ENV', $env);
        }
    }

    /**
     * @return void
     */
    private static function defineStore()
    {
        if (!defined('APPLICATION_STORE')) {
            $store = getenv('APPLICATION_STORE');
            if (!$store) {
                if (file_exists(APPLICATION_ROOT_DIR . '/config/Shared/default_store.php')) {
                    $store = require APPLICATION_ROOT_DIR . '/config/Shared/default_store.php';
                }
            }
            if (!$store) {
                echo 'Environment variable APPLICATION_STORE must be set. You can do this by adding e.g. APPLICATION_STORE=DE in front of this command or by adding a file "config/Shared/default_store.php" containing the current store.';
                exit(1);
            }
            define('APPLICATION_STORE', $store);
        }
    }

    /**
     * ErrorHandler is initialized lazy as in most cases
     * we will not use it
     *
     * @return void
     */
    protected static function initializeErrorHandler()
    {
        $initErrorHandler = function () {
            require_once __DIR__ . '/../Error/ErrorHandler.php';

            return ErrorHandler::initialize();
        };

        set_error_handler(
            function ($errno, $errstr, $errfile, $errline) use ($initErrorHandler) {
                throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
            }
        );

        set_exception_handler(
            function (\Exception $e) use ($initErrorHandler) {
                $initErrorHandler()->handleException($e);
            }
        );

        register_shutdown_function(
            function () use ($initErrorHandler) {
                $lastError = error_get_last();
                if ($lastError && in_array($lastError['type'], self::$fatalErrors)) {
                    $initErrorHandler()->handleFatal();
                }
            }
        );

        assert_options(
            ASSERT_CALLBACK,
            function ($script, $line, $message) {
                $parsedMessage = trim(preg_replace('~^.*/\*(.*)\*/~i', '$1', $message));
                $message = $parsedMessage ?: 'Assertion failed: ' . $message;
                throw new \ErrorException($message, 0, 0, $script, $line);
            }
        );
    }

}
