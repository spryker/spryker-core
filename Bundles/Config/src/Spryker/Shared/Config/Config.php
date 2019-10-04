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
    public const CONFIG_FILE_PREFIX = '/config/Shared/config_';
    public const CONFIG_FILE_SUFFIX = '.php';

    /**
     * @var \ArrayObject|null
     */
    protected static $config = null;

    /**
     * @var self|null
     */
    private static $instance;

    /**
     * @var \Spryker\Shared\Config\Profiler|null
     */
    private static $profiler;

    /**
     * @var bool
     */
    private static $isProfilerEnabled;

    /**
     * @return \Spryker\Shared\Config\Config
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
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
        if (empty(static::$config)) {
            static::init();
        }

        if (!static::hasValue($key) && $default !== null) {
            static::addProfileData($key, $default, null);

            return $default;
        }

        if (!static::hasValue($key)) {
            throw new Exception(sprintf('Could not find config key "%s" in "%s"', $key, self::class));
        }

        $value = static::$config[$key];

        static::addProfileData($key, $default, $value);

        return $value;
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @param mixed|null $value
     *
     * @return void
     */
    protected static function addProfileData($key, $default, $value)
    {
        if (!static::isProfilerEnabled()) {
            return;
        }

        if (!static::$profiler) {
            static::$profiler = new Profiler();
        }

        static::$profiler->add($key, $default, $value);
    }

    /**
     * @return bool
     */
    protected static function isProfilerEnabled()
    {
        if (static::$isProfilerEnabled === null) {
            static::$isProfilerEnabled = (static::hasValue(ConfigConstants::ENABLE_WEB_PROFILER)) ? static::$config[ConfigConstants::ENABLE_WEB_PROFILER] : false;
        }

        return static::$isProfilerEnabled;
    }

    /**
     * @return array
     */
    public static function getProfileData()
    {
        return static::$profiler->getProfileData();
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public static function hasValue($key)
    {
        return isset(static::$config[$key]);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public static function hasKey($key)
    {
        if (static::$config === null) {
            return false;
        }

        return static::$config->offsetExists($key);
    }

    /**
     * @param string|null $environmentName
     *
     * @return void
     */
    public static function init(?string $environmentName = null): void
    {
        $config = new ArrayObject();
        $environmentName = $environmentName ?? static::getEnvironmentName();
        $storeName = static::getStore()->getStoreName();

        /*
         * e.g. config_default.php
         */
        static::buildConfig('default', $config);

        /*
         * e.g. config_default-production.php
         */
        static::buildConfig('default-' . $environmentName, $config);

        /*
         * e.g. config_default_DE.php
         */
        static::buildConfig('default_' . $storeName, $config);

        /*
         * e.g. config_default-production_DE.php
         */
        static::buildConfig('default-' . $environmentName . '_' . $storeName, $config);

        /*
         * e.g. config_local_test.php
         */
        static::buildConfig('local_test', $config);

        /*
         * e.g. config_local.php
         */
        static::buildConfig('local', $config);

        /*
         * e.g. config_local_DE.php
         */
        static::buildConfig('local_' . $storeName, $config);

        /*
         * e.g. config_propel.php
         */
        static::buildConfig('propel', $config);

        static::$config = $config;
    }

    /**
     * @param string $type
     * @param \ArrayObject $config
     *
     * @return \ArrayObject
     */
    protected static function buildConfig($type, ArrayObject $config)
    {
        $fileName = APPLICATION_ROOT_DIR . static::CONFIG_FILE_PREFIX . $type . static::CONFIG_FILE_SUFFIX;
        if (file_exists($fileName)) {
            include $fileName;
        }

        return $config;
    }

    /**
     * @return string
     */
    private static function getEnvironmentName(): string
    {
        return APPLICATION_ENV;
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    private static function getStore(): Store
    {
        return Store::getInstance();
    }
}
