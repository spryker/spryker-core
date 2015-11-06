<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library;

use SprykerEngine\Shared\Kernel\Store;

/**
 * Runtime-Config for Yves and Zed
 *
 * @deprecated Please use SprykerEngine\Shared\Config
 */
class Config
{

    /**
     * @var array
     */
    protected static $config = null;

    const CONFIG_FILE_PREFIX = '/config/Shared/config_';
    const CONFIG_FILE_SUFFIX = '.php';

    /**
     * @param string $key
     * @param null $default
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        if (empty(self::$config)) {
            self::init();
        }

        if (!self::hasValue($key) && null !== $default) {
            self::$config[$key] = $default;
        }

        if (!self::hasValue($key)) {
            throw new \Exception(sprintf('Could not find config key "%s" in "%s"', $key, __CLASS__));
        }

        return self::$config[$key];
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public static function hasValue($key)
    {
        return isset(self::$config[$key]);
    }

    /**
     * Loads config_default and config_local and merges them
     *
     * @param string|null $environment
     *
     * @return void
     */
    public static function init($environment = null)
    {
        if ($environment === null) {
            $environment = Environment::getInstance()->getEnvironment();
        }

        $storeName = Store::getInstance()->getStoreName();

        $config = new \ArrayObject();

        /*
         * e.g. config_default.php
         */
        self::buildConfig('default', $config);

        /*
         * e.g. config_default-production.php
         */
        self::buildConfig('default-' . $environment, $config);

        /*
         * e.g. config_default_DE.php
         */
        self::buildConfig('default_' . $storeName, $config);

        /*
         * e.g. config_default-production_DE.php
         */
        self::buildConfig('default-' . $environment . '_' . $storeName, $config);

        /*
         * e.g. config_local.php
         */
        self::buildConfig('local', $config);

        /*
         * e.g. config_local_DE.php
         */
        self::buildConfig('local_' . $storeName, $config);

        self::$config = $config;
    }

    /**
     * @param string $type
     * @param \ArrayObject $config
     *
     * @return \ArrayObject
     */
    protected static function buildConfig($type, \ArrayObject $config)
    {
        $fileName = APPLICATION_ROOT_DIR . self::CONFIG_FILE_PREFIX . $type . self::CONFIG_FILE_SUFFIX;
        if (file_exists($fileName)) {
            include $fileName;
        }

        return $config;
    }

}
