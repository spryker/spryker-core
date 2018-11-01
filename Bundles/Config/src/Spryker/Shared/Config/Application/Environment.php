<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Config\Application;

use Exception;
use Spryker\Shared\Kernel\Store;

class Environment
{
    /**
     * @return void
     */
    public static function initialize()
    {
        date_default_timezone_set('UTC');

        static::defineEnvironment();
        static::defineStore();
        static::defineApplication();
        static::defineApplicationRootDir();
        static::defineApplicationSourceDir();
        static::defineApplicationStaticDir();
        static::defineApplicationVendorDir();
        static::defineApplicationDataDir();

        $store = Store::getInstance();
        $locale = current($store->getLocales());

        self::initializeLocale($locale);
        mb_internal_encoding('UTF-8');
        mb_regex_encoding('UTF-8');
    }

    /**
     * @return void
     */
    protected static function defineEnvironment()
    {
        if (!defined('APPLICATION_ENV')) {
            $env = getenv('APPLICATION_ENV', true) ?: getenv('APPLICATION_ENV');
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
    protected static function defineStore()
    {
        if (!defined('APPLICATION_STORE')) {
            $store = getenv('APPLICATION_STORE', true) ?: getenv('APPLICATION_STORE');
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
     * @throws \Exception
     *
     * @return void
     */
    protected static function defineApplication()
    {
        if (!defined('APPLICATION')) {
            $application = getenv('APPLICATION', true) ?: getenv('APPLICATION');
            if (!$application) {
                throw new Exception('Can not get APPLICATION environment variable');
            }
            define('APPLICATION', $application);
        }
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    protected static function defineApplicationRootDir()
    {
        if (!defined('APPLICATION_ROOT_DIR')) {
            $applicationRootDir = getenv('APPLICATION_ROOT_DIR', true) ?: getenv('APPLICATION_ROOT_DIR');
            if (!$applicationRootDir) {
                throw new Exception('Can not get APPLICATION_ROOT_DIR environment variable');
            }
            define('APPLICATION_ROOT_DIR', $applicationRootDir);
        }
    }

    /**
     * @return void
     */
    protected static function defineApplicationSourceDir()
    {
        if (!defined('APPLICATION_SOURCE_DIR')) {
            $applicationSourceDir = getenv('APPLICATION_SOURCE_DIR', true) ?: getenv('APPLICATION_SOURCE_DIR');
            if (!$applicationSourceDir) {
                define('APPLICATION_SOURCE_DIR', APPLICATION_ROOT_DIR . '/src');
            } else {
                define('APPLICATION_SOURCE_DIR', $applicationSourceDir);
            }
        }
    }

    /**
     * @return void
     */
    protected static function defineApplicationStaticDir()
    {
        if (!defined('APPLICATION_STATIC_DIR')) {
            $applicationStaticDir = getenv('APPLICATION_STATIC_DIR', true) ?: getenv('APPLICATION_STATIC_DIR');
            if (!$applicationStaticDir) {
                define('APPLICATION_STATIC_DIR', APPLICATION_ROOT_DIR . '/static');
            } else {
                define('APPLICATION_STATIC_DIR', $applicationStaticDir);
            }
        }
    }

    /**
     * @return void
     */
    protected static function defineApplicationVendorDir()
    {
        if (!defined('APPLICATION_VENDOR_DIR')) {
            $applicationVendorDir = getenv('APPLICATION_VENDOR_DIR', true) ?: getenv('APPLICATION_VENDOR_DIR');
            if (!$applicationVendorDir) {
                define('APPLICATION_VENDOR_DIR', APPLICATION_ROOT_DIR . '/vendor');
            } else {
                define('APPLICATION_VENDOR_DIR', $applicationVendorDir);
            }
        }
    }

    /**
     * @return void
     */
    protected static function defineApplicationDataDir()
    {
        if (!defined('APPLICATION_DATA')) {
            $applicationData = getenv('APPLICATION_DATA', true) ?: getenv('APPLICATION_DATA');
            if (!$applicationData) {
                define('APPLICATION_DATA', APPLICATION_ROOT_DIR . '/data');
            } else {
                define('APPLICATION_DATA', $applicationData);
            }
        }
    }

    /**
     * We set LC_NUMERIC hard to en_US so numeric conversion is always the same to avoid decimal point problems
     *
     * @param string $currentLocale
     *
     * @return void
     */
    public static function initializeLocale($currentLocale)
    {
        $locale = $currentLocale . '.UTF-8';

        setlocale(LC_COLLATE, $locale, $currentLocale);
        setlocale(LC_CTYPE, $locale, $currentLocale);
        setlocale(LC_MONETARY, $locale, $currentLocale);
        setlocale(LC_TIME, $locale, $currentLocale);
        setlocale(LC_MESSAGES, $locale, $currentLocale);
        setlocale(LC_NUMERIC, 'en_US.UTF-8', 'en_US');
    }
}
