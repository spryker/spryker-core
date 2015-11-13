<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Log;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use SprykerFeature\Shared\Log\Config\DefaultLoggerConfig;
use SprykerFeature\Shared\Log\Config\LoggerConfigInterface;

class LoggerFactory
{

    /**
     * @var array
     */
    protected static $loggers = [];

    /**
     * @param LoggerConfigInterface $loggerConfig
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
     * @param LoggerConfigInterface $loggerConfig
     *
     * @return LoggerInterface
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
