<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log;

use Monolog\Logger;
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
