<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\PluginProvider;

use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface;

interface AuditLoggerConfigPluginProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
     *
     * @return \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface
     */
    public function getAuditLoggerConfigPlugin(
        AuditLoggerConfigCriteriaTransfer $auditLoggerConfigCriteriaTransfer
    ): AuditLoggerConfigPluginInterface;
}
