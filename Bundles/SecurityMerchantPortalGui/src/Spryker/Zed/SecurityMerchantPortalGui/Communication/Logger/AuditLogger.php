<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Logger;

use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Spryker\Shared\Log\AuditLoggerTrait;

class AuditLogger implements AuditLoggerInterface
{
    use AuditLoggerTrait;

    /**
     * @uses \Spryker\Shared\Log\LogConfig::AUDIT_LOGGER_CHANNEL_NAME_SECURITY
     *
     * @var string
     */
    protected const AUDIT_LOGGER_CHANNEL_NAME_SECURITY = 'security';

    /**
     * @uses \Spryker\Shared\Log\Handler\TagFilterBufferedStreamHandler::RECORD_KEY_CONTEXT_TAGS
     *
     * @var string
     */
    protected const AUDIT_LOGGER_RECORD_KEY_CONTEXT_TAGS = 'tags';

    /**
     * @return void
     */
    public function addFailedLoginAuditLog(): void
    {
        $this->addAuditLog('Failed Login', ['failed_login']);
    }

    /**
     * @return void
     */
    public function addSuccessfulLoginAuditLog(): void
    {
        $this->addAuditLog('Successful Login', ['successful_login']);
    }

    /**
     * @return void
     */
    public function addPasswordResetRequestedAuditLog(): void
    {
        $this->addAuditLog('Password Reset Requested', ['password_reset_requested']);
    }

    /**
     * @return void
     */
    public function addPasswordUpdatedAfterResetAuditLog(): void
    {
        $this->addAuditLog('Password Updated after Reset', ['password_updated_after_reset']);
    }

    /**
     * @param string $action
     * @param list<string> $tags
     *
     * @return void
     */
    protected function addAuditLog(string $action, array $tags): void
    {
        $this->getAuditLogger(
            (new AuditLoggerConfigCriteriaTransfer())->setChannelName(static::AUDIT_LOGGER_CHANNEL_NAME_SECURITY),
        )->info($action, [static::AUDIT_LOGGER_RECORD_KEY_CONTEXT_TAGS => $tags]);
    }
}
