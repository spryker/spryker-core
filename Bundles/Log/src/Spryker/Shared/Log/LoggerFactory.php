<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log;

use Monolog\Logger as MonologLogger;
use Spryker\Shared\Log\Config\LoggerConfigInterface;
use Spryker\Shared\Log\LoggerConfig\LoggerConfigLoader;
use Spryker\Shared\Log\LoggerConfig\LoggerConfigLoaderDefault;
use Spryker\Shared\Log\LoggerConfig\LoggerConfigLoaderYves;
use Spryker\Shared\Log\LoggerConfig\LoggerConfigLoaderZed;

class LoggerFactory
{
    /**
     * @var array
     */
    protected static $loggers = [];

    /**
     * @var \Spryker\Shared\Log\Config\LoggerConfigInterface|null
     */
    protected static $loggerConfig = null;

    /**
     * @param \Spryker\Shared\Log\Config\LoggerConfigInterface|null $loggerConfig
     *
     * @return \Psr\Log\LoggerInterface|null
     */
    public static function getInstance(?LoggerConfigInterface $loggerConfig = null)
    {
        if ($loggerConfig === null) {
            if (!static::$loggerConfig) {
                static::$loggerConfig = static::createLoggerConfig();
            }

            $loggerConfig = static::$loggerConfig;
        }

        return static::createInstanceIfNotExists($loggerConfig);
    }

    /**
     * @param \Spryker\Shared\Log\Config\LoggerConfigInterface $loggerConfig
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected static function createInstanceIfNotExists(LoggerConfigInterface $loggerConfig)
    {
        $channelName = $loggerConfig->getChannelName();

        if (!isset(static::$loggers[$channelName])) {
            $logger = new MonologLogger($channelName, $loggerConfig->getHandlers(), $loggerConfig->getProcessors());

            static::$loggers[$channelName] = $logger;
        }

        return static::$loggers[$channelName];
    }

    /**
     * @return \Spryker\Shared\Log\Config\LoggerConfigInterface
     */
    protected static function createLoggerConfig()
    {
        $loggerConfigLoader = new LoggerConfigLoader([
            static::createLoggerConfigLoaderYves(),
            static::createLoggerConfigLoaderZed(),
            static::createLoggerConfigLoaderDefault(),
        ]);

        return $loggerConfigLoader->getLoggerConfig();
    }

    /**
     * @return \Spryker\Shared\Log\LoggerConfig\LoggerConfigLoaderInterface|\Spryker\Shared\Log\LoggerConfig\LoggerConfigLoaderYves
     */
    protected static function createLoggerConfigLoaderYves()
    {
        return new LoggerConfigLoaderYves();
    }

    /**
     * @return \Spryker\Shared\Log\LoggerConfig\LoggerConfigLoaderInterface|\Spryker\Shared\Log\LoggerConfig\LoggerConfigLoaderZed
     */
    protected static function createLoggerConfigLoaderZed()
    {
        return new LoggerConfigLoaderZed();
    }

    /**
     * @return \Spryker\Shared\Log\LoggerConfig\LoggerConfigLoaderInterface|\Spryker\Shared\Log\LoggerConfig\LoggerConfigLoaderDefault
     */
    protected static function createLoggerConfigLoaderDefault()
    {
        return new LoggerConfigLoaderDefault();
    }
}
