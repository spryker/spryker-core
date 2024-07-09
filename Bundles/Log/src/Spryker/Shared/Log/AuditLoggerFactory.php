<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log;

use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;
use Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface;

class AuditLoggerFactory
{
    /**
     * @var array<string, \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface>
     */
    protected static array $auditLoggerConfigPlugins;

    /**
     * @var array<string, \Psr\Log\LoggerInterface>
     */
    protected static array $loggers = [];

    /**
     * @param \Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
     *
     * @return \Psr\Log\LoggerInterface
     */
    public static function getInstance(
        AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
    ): LoggerInterface {
        $channelName = $auditLoggerConfigCriteriaTransfer->getChannelName();

        if (!isset(static::$auditLoggerConfigPlugins[$channelName])) {
            static::$auditLoggerConfigPlugins[$channelName] = AuditLoggerConfigPluginProviderFactory::getAuditLoggerConfigPlugin($auditLoggerConfigCriteriaTransfer);
        }

        $loggerConfig = static::$auditLoggerConfigPlugins[$channelName];

        return static::createInstanceIfNotExists($loggerConfig);
    }

    /**
     * @param \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface $auditLoggerConfigPlugin
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected static function createInstanceIfNotExists(AuditLoggerConfigPluginInterface $auditLoggerConfigPlugin): LoggerInterface
    {
        $channelName = $auditLoggerConfigPlugin->getChannelName();

        if (!isset(static::$loggers[$channelName])) {
            $logger = new MonologLogger($channelName, $auditLoggerConfigPlugin->getHandlers(), $auditLoggerConfigPlugin->getProcessors());

            static::$loggers[$channelName] = $logger;
        }

        return static::$loggers[$channelName];
    }
}
