<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log;

use Monolog\Logger as MonologLogger;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Log\Config\DefaultLoggerConfig;
use Spryker\Shared\Log\Config\LoggerConfigInterface;

class LoggerFactory
{
    /**
     * @var array
     */
    protected static $loggers = [];

    /**
     * @param \Spryker\Shared\Log\Config\LoggerConfigInterface|null $loggerConfig
     *
     * @return \Psr\Log\LoggerInterface|null
     */
    public static function getInstance(LoggerConfigInterface $loggerConfig = null)
    {
        if ($loggerConfig === null) {
            $loggerClassName = static::getLoggerClassName();
            $loggerConfig = new $loggerClassName;
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

        if (!array_key_exists($channelName, static::$loggers)) {
            $logger = new MonologLogger($channelName, $loggerConfig->getHandlers(), $loggerConfig->getProcessors());

            static::$loggers[$channelName] = $logger;
        }

        return static::$loggers[$channelName];
    }

    /**
     * @return string
     */
    protected static function getLoggerClassName()
    {
        return Config::get(LogConstants::LOGGER_CONFIG, DefaultLoggerConfig::class);
    }
}
