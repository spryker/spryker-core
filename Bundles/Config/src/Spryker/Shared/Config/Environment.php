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
    /**
     * @var string
     */
    public const DEFAULT_ENVIRONMENT = 'production';

    /**
     * @var string
     */
    public const PRODUCTION = 'production';

    /**
     * @var string
     */
    public const STAGING = 'staging';

    /**
     * @var string
     */
    public const DEVELOPMENT = 'development';

    /**
     * @var string
     */
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
        if (!static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    /**
     * @return bool
     */
    public static function isProduction()
    {
        return (static::$environment === static::PRODUCTION);
    }

    /**
     * @return bool
     */
    public static function isNotProduction()
    {
        return (static::$environment !== static::PRODUCTION);
    }

    /**
     * @return bool
     */
    public static function isStaging()
    {
        return (static::$environment === static::STAGING);
    }

    /**
     * @return bool
     */
    public static function isNotStaging()
    {
        return (static::$environment !== static::STAGING);
    }

    /**
     * @return bool
     */
    public static function isDevelopment()
    {
        return (static::$environment === static::DEVELOPMENT);
    }

    /**
     * @return bool
     */
    public static function isNotDevelopment()
    {
        return (static::$environment !== static::DEVELOPMENT);
    }

    /**
     * @return bool
     */
    public static function isTesting()
    {
        return (static::$environment === static::TESTING);
    }

    /**
     * @return bool
     */
    public static function isNotTesting()
    {
        return (static::$environment !== static::TESTING);
    }

    /**
     * @return string
     */
    public static function getEnvironment()
    {
        return static::$environment;
    }

    /**
     * @param string $environment
     *
     * @return void
     */
    public static function setEnvironment($environment)
    {
        static::$environment = $environment;
    }
}
