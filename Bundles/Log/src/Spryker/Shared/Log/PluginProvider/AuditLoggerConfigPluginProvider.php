<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\PluginProvider;

use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface;

class AuditLoggerConfigPluginProvider implements AuditLoggerConfigPluginProviderInterface
{
    /**
     * @var list<\Spryker\Shared\Log\Strategy\AuditLoggerConfigPluginProviderStrategyInterface>
     */
    protected array $auditLoggerConfigPluginProviderStrategies;

    /**
     * @var \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface
     */
    protected AuditLoggerConfigPluginInterface $defaultAuditLoggerConfigPlugin;

    /**
     * @param list<\Spryker\Shared\Log\Strategy\AuditLoggerConfigPluginProviderStrategyInterface> $auditLoggerConfigPluginProviderStrategies
     * @param \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface $defaultAuditLoggerConfigPlugin
     */
    public function __construct(
        array $auditLoggerConfigPluginProviderStrategies,
        AuditLoggerConfigPluginInterface $defaultAuditLoggerConfigPlugin
    ) {
        $this->auditLoggerConfigPluginProviderStrategies = $auditLoggerConfigPluginProviderStrategies;
        $this->defaultAuditLoggerConfigPlugin = $defaultAuditLoggerConfigPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
     *
     * @return \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface
     */
    public function getAuditLoggerConfigPlugin(
        AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
    ): AuditLoggerConfigPluginInterface {
        foreach ($this->auditLoggerConfigPluginProviderStrategies as $auditLoggerConfigPluginProviderStrategy) {
            if (!$auditLoggerConfigPluginProviderStrategy->isApplicable()) {
                continue;
            }

            $plugin = $auditLoggerConfigPluginProviderStrategy->providePlugin($auditLoggerConfigCriteriaTransfer);

            if ($plugin !== null) {
                return $plugin;
            }
        }

        return $this->defaultAuditLoggerConfigPlugin;
    }
}
