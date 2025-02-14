<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Business\Logger;

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
    public function addSuccessfulEmailUpdateAuditLog(): void
    {
        $this->getAuditLogger(
            (new AuditLoggerConfigCriteriaTransfer())->setChannelName(static::AUDIT_LOGGER_CHANNEL_NAME_SECURITY),
        )->info('Successful Email Update (Customer)', [static::AUDIT_LOGGER_RECORD_KEY_CONTEXT_TAGS => ['successful_email_update']]);
    }
}
