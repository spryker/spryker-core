<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Log;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Spryker\Shared\Log\Config\DefaultLoggerConfig;
use Spryker\Shared\Log\Config\LoggerConfigInterface;

class LoggerFactory
{

    /**
     * @var array
     */
    protected static $loggers = [];

    /**
     * @param \Spryker\Shared\Log\Config\LoggerConfigInterface $loggerConfig
     *
     * @return LoggerInterface|null
     */
    public static function getInstance(LoggerConfigInterface $loggerConfig = null)
    {
        if ($loggerConfig === null) {
            $loggerConfig = new DefaultLoggerConfig();
        }

        return self::createInstanceIfNotExists($loggerConfig);
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
            $logger = new Logger($channelName, $loggerConfig->getHandlers(), $loggerConfig->getProcessors());

            static::$loggers[$channelName] = $logger;
        }

        return self::$loggers[$channelName];
    }

}
