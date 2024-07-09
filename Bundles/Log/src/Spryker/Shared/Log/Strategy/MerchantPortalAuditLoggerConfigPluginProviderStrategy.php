<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\Strategy;

use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Log\LogConstants;
use Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface;

class MerchantPortalAuditLoggerConfigPluginProviderStrategy extends AbstractAuditLoggerConfigPluginProviderStrategy
{
    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        return $this->isMerchantPortalApplication() && Config::hasKey(LogConstants::AUDIT_LOGGER_CONFIG_PLUGINS_MERCHANT_PORTAL);
    }

    /**
     * @param \Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
     *
     * @return \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface|null
     */
    public function providePlugin(
        AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
    ): ?AuditLoggerConfigPluginInterface {
        $auditLoggerConfigPluginClassNames = Config::get(LogConstants::AUDIT_LOGGER_CONFIG_PLUGINS_MERCHANT_PORTAL);

        return $this->resolvePlugin($auditLoggerConfigPluginClassNames, $auditLoggerConfigCriteriaTransfer);
    }

    /**
     * @return bool
     */
    protected function isMerchantPortalApplication(): bool
    {
        return APPLICATION === 'MERCHANT_PORTAL';
    }
}
