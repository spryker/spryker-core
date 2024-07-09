<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log;

use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Spryker\Shared\Log\Plugin\Log\NullAuditLoggerConfigPlugin;
use Spryker\Shared\Log\PluginProvider\AuditLoggerConfigPluginProvider;
use Spryker\Shared\Log\PluginProvider\AuditLoggerConfigPluginProviderInterface;
use Spryker\Shared\Log\Strategy\AuditLoggerConfigPluginProviderStrategyInterface;
use Spryker\Shared\Log\Strategy\GlueAuditLoggerConfigPluginProviderStrategy;
use Spryker\Shared\Log\Strategy\GlueBackendAuditLoggerConfigPluginProviderStrategy;
use Spryker\Shared\Log\Strategy\MerchantPortalAuditLoggerConfigPluginProviderStrategy;
use Spryker\Shared\Log\Strategy\YvesAuditLoggerConfigPluginProviderStrategy;
use Spryker\Shared\Log\Strategy\ZedAuditLoggerConfigPluginProviderStrategy;
use Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface;

class AuditLoggerConfigPluginProviderFactory
{
    /**
     * @param \Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
     *
     * @return \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface
     */
    public static function getAuditLoggerConfigPlugin(
        AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
    ): AuditLoggerConfigPluginInterface {
        return static::createAuditLoggerConfigPluginProvider()
            ->getAuditLoggerConfigPlugin($auditLoggerConfigCriteriaTransfer);
    }

    /**
     * @return \Spryker\Shared\Log\PluginProvider\AuditLoggerConfigPluginProviderInterface
     */
    protected static function createAuditLoggerConfigPluginProvider(): AuditLoggerConfigPluginProviderInterface
    {
        return new AuditLoggerConfigPluginProvider(
            static::getAuditLoggerConfigPluginProviderStrategies(),
            static::createDefaultAuditLoggerConfigPlugin(),
        );
    }

    /**
     * @return list<\Spryker\Shared\Log\Strategy\AuditLoggerConfigPluginProviderStrategyInterface>
     */
    protected static function getAuditLoggerConfigPluginProviderStrategies(): array
    {
        return [
            static::createYvesAuditLoggerConfigPluginProviderStrategy(),
            static::createZedAuditLoggerConfigPluginProviderStrategy(),
            static::createGlueAuditLoggerConfigPluginProviderStrategy(),
            static::createGlueBackendAuditLoggerConfigPluginProviderStrategy(),
            static::createMerchantPortalAuditLoggerConfigPluginProviderStrategy(),
        ];
    }

    /**
     * @return \Spryker\Shared\Log\Strategy\AuditLoggerConfigPluginProviderStrategyInterface
     */
    protected static function createYvesAuditLoggerConfigPluginProviderStrategy(): AuditLoggerConfigPluginProviderStrategyInterface
    {
        return new YvesAuditLoggerConfigPluginProviderStrategy();
    }

    /**
     * @return \Spryker\Shared\Log\Strategy\AuditLoggerConfigPluginProviderStrategyInterface
     */
    protected static function createZedAuditLoggerConfigPluginProviderStrategy(): AuditLoggerConfigPluginProviderStrategyInterface
    {
        return new ZedAuditLoggerConfigPluginProviderStrategy();
    }

    /**
     * @return \Spryker\Shared\Log\Strategy\AuditLoggerConfigPluginProviderStrategyInterface
     */
    protected static function createGlueAuditLoggerConfigPluginProviderStrategy(): AuditLoggerConfigPluginProviderStrategyInterface
    {
        return new GlueAuditLoggerConfigPluginProviderStrategy();
    }

    /**
     * @return \Spryker\Shared\Log\Strategy\AuditLoggerConfigPluginProviderStrategyInterface
     */
    protected static function createGlueBackendAuditLoggerConfigPluginProviderStrategy(): AuditLoggerConfigPluginProviderStrategyInterface
    {
        return new GlueBackendAuditLoggerConfigPluginProviderStrategy();
    }

    /**
     * @return \Spryker\Shared\Log\Strategy\AuditLoggerConfigPluginProviderStrategyInterface
     */
    protected static function createMerchantPortalAuditLoggerConfigPluginProviderStrategy(): AuditLoggerConfigPluginProviderStrategyInterface
    {
        return new MerchantPortalAuditLoggerConfigPluginProviderStrategy();
    }

    /**
     * @return \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface
     */
    protected static function createDefaultAuditLoggerConfigPlugin(): AuditLoggerConfigPluginInterface
    {
        return new NullAuditLoggerConfigPlugin();
    }
}
