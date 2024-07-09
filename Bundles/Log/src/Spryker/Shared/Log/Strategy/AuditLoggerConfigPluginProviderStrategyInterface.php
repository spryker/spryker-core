<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\Strategy;

use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface;

interface AuditLoggerConfigPluginProviderStrategyInterface
{
    /**
     * @return bool
     */
    public function isApplicable(): bool;

    /**
     * @param \Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
     *
     * @return \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface|null
     */
    public function providePlugin(
        AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
    ): ?AuditLoggerConfigPluginInterface;
}
