<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\Logger;

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
    public function addFailedLoginAuditLog(OauthRequestTransfer $oauthRequestTransfer): void
    {
        $context = $this->addOauthRequestContext(
            [static::AUDIT_LOGGER_RECORD_KEY_CONTEXT_TAGS => ['failed_login']],
            $oauthRequestTransfer,
        );

        $this->addAuditLog('Failed Login', $context);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return void
     */
    public function addSuccessfulLoginAuditLog(OauthRequestTransfer $oauthRequestTransfer): void
    {
        $context = $this->addOauthRequestContext(
            [static::AUDIT_LOGGER_RECORD_KEY_CONTEXT_TAGS => ['successful_login']],
            $oauthRequestTransfer,
        );

        $this->addAuditLog('Successful Login', $context);
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
