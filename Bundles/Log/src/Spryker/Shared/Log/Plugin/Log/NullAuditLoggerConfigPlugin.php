<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\Plugin\Log;

use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Monolog\Handler\NullHandler;
use Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface;

/**
 * This plugin is used as a default fallback.
 */
class NullAuditLoggerConfigPlugin implements AuditLoggerConfigPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getChannelName(): string
    {
        return '';
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return list<\Monolog\Handler\HandlerInterface>
     */
    public function getHandlers(): array
    {
        return [
            new NullHandler(),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return list<\Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface>
     */
    public function getProcessors(): array
    {
        return [];
    }
}
