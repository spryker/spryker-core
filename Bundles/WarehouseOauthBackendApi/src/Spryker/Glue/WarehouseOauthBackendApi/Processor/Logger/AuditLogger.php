<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi\Processor\Logger;

use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
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
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addWarehouseUserFailedLoginAuditLog(GlueRequestTransfer $glueRequestTransfer): void
    {
        $context = $this->addGlueRequestContext(
            [static::AUDIT_LOGGER_RECORD_KEY_CONTEXT_TAGS => ['warehouse_user_failed_login']],
            $glueRequestTransfer,
        );

        $this->addAuditLog('Failed Login (Warehouse User)', $context);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addWarehouseUserSuccessfulLoginAuditLog(GlueRequestTransfer $glueRequestTransfer): void
    {
        $context = $this->addGlueRequestContext(
            [static::AUDIT_LOGGER_RECORD_KEY_CONTEXT_TAGS => ['warehouse_user_successful_login']],
            $glueRequestTransfer,
        );

        $this->addAuditLog('Successful Login (Warehouse User)', $context);
    }

    /**
     * @param array<string, mixed> $context
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return array<string, mixed>
     */
    protected function addGlueRequestContext(array $context, GlueRequestTransfer $glueRequestTransfer): array
    {
        $context['user_uuid'] = $glueRequestTransfer->getRequestUserOrFail()->getNaturalIdentifier();

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
