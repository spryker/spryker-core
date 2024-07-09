<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Logger;

interface AuditLoggerInterface
{
    /**
     * @return void
     */
    public function addFailedLoginAuditLog(): void;

    /**
     * @return void
     */
    public function addSuccessfulLoginAuditLog(): void;

    /**
     * @return void
     */
    public function addPasswordResetRequestedAuditLog(): void;

    /**
     * @return void
     */
    public function addPasswordUpdatedAfterResetAuditLog(): void;
}
