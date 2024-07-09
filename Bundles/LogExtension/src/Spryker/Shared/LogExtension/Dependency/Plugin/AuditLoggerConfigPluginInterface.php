<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\LogExtension\Dependency\Plugin;

use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;

/**
 * Interface is used to provide configuration for audit logging.
 */
interface AuditLoggerConfigPluginInterface
{
    /**
     * Specification:
     * - Determines if the configuration is applicable based on the given criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer): bool;

    /**
     * Specification:
     * - Retrieves the name of the logging channel.
     *
     * @api
     *
     * @return string
     */
    public function getChannelName(): string;

    /**
     * Specification:
     * - Retrieves the handlers for the logger.
     *
     * @api
     *
     * @return list<\Spryker\Shared\Log\Dependency\Plugin\LogHandlerPluginInterface>
     */
    public function getHandlers(): array;

    /**
     * Specification:
     * - Retrieves the processors for the logger.
     *
     * @api
     *
     * @return list<\Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface>
     */
    public function getProcessors(): array;
}
