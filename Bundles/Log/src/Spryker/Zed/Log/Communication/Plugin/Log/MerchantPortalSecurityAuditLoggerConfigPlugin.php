<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Communication\Plugin\Log;

use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Spryker\Shared\Log\LogConfig;
use Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * This plugin is used to provide configuration for audit logging for Merchant portal application.
 *
 * @method \Spryker\Zed\Log\Communication\LogCommunicationFactory getFactory()
 * @method \Spryker\Zed\Log\LogConfig getConfig()
 * @method \Spryker\Zed\Log\Business\LogFacadeInterface getFacade()
 */
class MerchantPortalSecurityAuditLoggerConfigPlugin extends AbstractPlugin implements AuditLoggerConfigPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true for `security` channel name.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer): bool
    {
        return $auditLoggerConfigCriteriaTransfer->getChannelName() === $this->getChannelName();
    }

    /**
     * {@inheritDoc}
     * - Returns `security` channel name.
     *
     * @api
     *
     * @return string
     */
    public function getChannelName(): string
    {
        return LogConfig::AUDIT_LOGGER_CHANNEL_NAME_SECURITY;
    }

    /**
     * {@inheritDoc}
     * - Returns Merchant portal application security specific handlers.
     *
     * @api
     *
     * @return list<\Spryker\Shared\Log\Dependency\Plugin\LogHandlerPluginInterface>
     */
    public function getHandlers(): array
    {
        return $this->getFactory()->getMerchantPortalSecurityAuditLogHandlerPlugins();
    }

    /**
     * {@inheritDoc}
     * - Returns Merchant portal application security specific processors.
     *
     * @api
     *
     * @return list<\Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface>
     */
    public function getProcessors(): array
    {
        return $this->getFactory()->getMerchantPortalSecurityAuditLogProcessorPlugins();
    }
}
