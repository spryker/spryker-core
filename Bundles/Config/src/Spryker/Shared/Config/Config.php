<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Config;

use ArrayObject;
use Exception;
use Spryker\Shared\Kernel\Store;

class Config
{

    const CONFIG_FILE_PREFIX = '/config/Shared/config_';
    const CONFIG_FILE_SUFFIX = '.php';

    /**
     * @var \ArrayObject|null
     */
    protected static $config = null;

    /**
     * @var self
     */
    private static $instance;

    /**
     * @return \Spryker\Shared\Config\Config
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $key
     * @param mixed|null $default
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

        if (!self::hasValue($key) && $default !== null) {
            return $default;
        }

        if (!self::hasValue($key)) {
            throw new Exception(sprintf('Could not find config key "%s" in "%s"', $key, __CLASS__));
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
     * @param string $key
     *
     * @return bool
     */
    public static function hasKey($key)
    {
        return array_key_exists($key, self::$config);
    }

    /**
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

        $config = new ArrayObject();

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
         * e.g. config_local_test.php
         */
        self::buildConfig('local_test', $config);

        /*
         * e.g. config_local.php
         */
        self::buildConfig('local', $config);

        /*
         * e.g. config_local_DE.php
         */
        self::buildConfig('local_' . $storeName, $config);

        /*
         * e.g. config_propel.php
         */
        self::buildConfig('propel', $config);

        self::$config = $config;
    }

    /**
     * @param string $type
     * @param \ArrayObject $config
     *
     * @return \ArrayObject
     */
    protected static function buildConfig($type, ArrayObject $config)
    {
        $fileName = APPLICATION_ROOT_DIR . self::CONFIG_FILE_PREFIX . $type . self::CONFIG_FILE_SUFFIX;
        if (file_exists($fileName)) {
            include $fileName;
        }

        return $config;
    }

}
