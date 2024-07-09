<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Logger;

use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Spryker\Shared\Log\AuditLoggerTrait;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Logger\DataProvider\AuditLoggerUserProviderInterface;

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
     * @var \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Logger\DataProvider\AuditLoggerUserProviderInterface
     */
    protected AuditLoggerUserProviderInterface $auditLoggerUserProvider;

    /**
     * @param \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Logger\DataProvider\AuditLoggerUserProviderInterface $auditLoggerUserProvider
     */
    public function __construct(AuditLoggerUserProviderInterface $auditLoggerUserProvider)
    {
        $this->auditLoggerUserProvider = $auditLoggerUserProvider;
    }

    /**
     * @return void
     */
    public function addAgentFailedLoginAuditLog(): void
    {
        $this->addAuditLog('Failed Login (Agent)', [static::AUDIT_LOGGER_RECORD_KEY_CONTEXT_TAGS => ['agent_failed_login']]);
    }

    /**
     * @return void
     */
    public function addAgentSuccessfulLoginAuditLog(): void
    {
        $this->addAuditLog('Successful Login (Agent)', [static::AUDIT_LOGGER_RECORD_KEY_CONTEXT_TAGS => ['agent_successful_login']]);
    }

    /**
     * @return void
     */
    public function addImpersonationStartedAuditLog(): void
    {
        $context = $this->addOriginalUserContext([static::AUDIT_LOGGER_RECORD_KEY_CONTEXT_TAGS => ['impersonation_started']]);

        $this->addAuditLog('Impersonation Started', $context);
    }

    /**
     * @return void
     */
    public function addImpersonationEndedAuditLog(): void
    {
        $context = $this->addOriginalUserContext([static::AUDIT_LOGGER_RECORD_KEY_CONTEXT_TAGS => ['impersonation_ended']]);

        $this->addAuditLog('Impersonation Ended', $context);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    protected function addOriginalUserContext(array $context): array
    {
        $userTransfer = $this->auditLoggerUserProvider->findOriginalUser();
        if ($userTransfer) {
            $context['original_user_uuid'] = $userTransfer->getUuid();
            $context['original_username'] = $userTransfer->getUsername();
        }

        return $context;
    }

    /**
     * @param string $action
     * @param array<string, mixed> $context
     *
     * @return void
     */
    protected function addAuditLog(string $action, array $context): void
    {
        $this->getAuditLogger(
            (new AuditLoggerConfigCriteriaTransfer())->setChannelName(static::AUDIT_LOGGER_CHANNEL_NAME_SECURITY),
        )->info($action, $context);
    }
}
