<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Config;

/**
 * @deprecated Will be removed without replacement. The usage should be replaced with specific parameter defined in the configiration.
 */
class Environment
{
    public const DEFAULT_ENVIRONMENT = 'production';

    public const PRODUCTION = 'production';
    public const STAGING = 'staging';
    public const DEVELOPMENT = 'development';
    public const TESTING = 'devtest';

    /**
     * @var string
     */
    protected static $environment = APPLICATION_ENV;

    /**
     * @var self|null
     */
    protected static $instance;

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return bool
     */
    public static function isProduction()
    {
        return (self::$environment === self::PRODUCTION);
    }

    /**
     * @return bool
     */
    public static function isNotProduction()
    {
        return (self::$environment !== self::PRODUCTION);
    }

    /**
     * @return bool
     */
    public static function isStaging()
    {
        return (self::$environment === self::STAGING);
    }

    /**
     * @return bool
     */
    public static function isNotStaging()
    {
        return (self::$environment !== self::STAGING);
    }

    /**
     * @return bool
     */
    public static function isDevelopment()
    {
        return (self::$environment === self::DEVELOPMENT);
    }

    /**
     * @return bool
     */
    public static function isNotDevelopment()
    {
        return (self::$environment !== self::DEVELOPMENT);
    }

    /**
     * @return bool
     */
    public static function isTesting()
    {
        return (self::$environment === self::TESTING);
    }

    /**
     * @return bool
     */
    public static function isNotTesting()
    {
        return (self::$environment !== self::TESTING);
    }

    /**
     * @return string
     */
    public static function getEnvironment()
    {
        return self::$environment;
    }

    /**
     * @param string $environment
     *
     * @return void
     */
    public static function setEnvironment($environment)
    {
        self::$environment = $environment;
    }
}
