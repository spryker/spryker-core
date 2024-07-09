<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\Strategy;

use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface;

abstract class AbstractAuditLoggerConfigPluginProviderStrategy implements AuditLoggerConfigPluginProviderStrategyInterface
{
    /**
     * @var array<string, \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface>
     */
    protected static array $auditLoggerConfigPlugins = [];

    /**
     * @return bool
     */
    abstract public function isApplicable(): bool;

    /**
     * @param \Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
     *
     * @return \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface|null
     */
    abstract public function providePlugin(
        AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
    ): ?AuditLoggerConfigPluginInterface;

    /**
     * @param list<string> $auditLoggerConfigPluginClassNames
     * @param \Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
     *
     * @return \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface|null
     */
    protected function resolvePlugin(
        array $auditLoggerConfigPluginClassNames,
        AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
    ): ?AuditLoggerConfigPluginInterface {
        /** @phpstan-var class-string<\Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface> $auditLoggerConfigPluginClassName */
        foreach ($auditLoggerConfigPluginClassNames as $auditLoggerConfigPluginClassName) {
            if (!isset(static::$auditLoggerConfigPlugins[$auditLoggerConfigPluginClassName])) {
                static::$auditLoggerConfigPlugins[$auditLoggerConfigPluginClassName] = new $auditLoggerConfigPluginClassName();
            }

            $auditLoggerConfigPlugin = static::$auditLoggerConfigPlugins[$auditLoggerConfigPluginClassName];

            if ($auditLoggerConfigPlugin->isApplicable($auditLoggerConfigCriteriaTransfer)) {
                return $auditLoggerConfigPlugin;
            }
        }

        return null;
    }
}
