<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi\Processor\Logger;

use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
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
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return void
     */
    public function addAgentFailedLoginAuditLog(OauthRequestTransfer $oauthRequestTransfer): void
    {
        $context = $this->addOauthRequestContext(
            [static::AUDIT_LOGGER_RECORD_KEY_CONTEXT_TAGS => ['agent_failed_login']],
            $oauthRequestTransfer,
        );

        $this->addAuditLog('Failed Login (Agent)', $context);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return void
     */
    public function addAgentSuccessfulLoginAuditLog(OauthRequestTransfer $oauthRequestTransfer): void
    {
        $context = $this->addOauthRequestContext(
            [static::AUDIT_LOGGER_RECORD_KEY_CONTEXT_TAGS => ['agent_successful_login']],
            $oauthRequestTransfer,
        );

        $this->addAuditLog('Successful Login (Agent)', $context);
    }

    /**
     * @param array<string, mixed> $context
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return array<string, mixed>
     */
    protected function addOauthRequestContext(array $context, OauthRequestTransfer $oauthRequestTransfer): array
    {
        $context['username'] = $oauthRequestTransfer->getUsername();

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
