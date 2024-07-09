<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Logger;

interface AuditLoggerInterface
{
    /**
     * @return void
     */
    public function addAgentFailedLoginAuditLog(): void;

    /**
     * @return void
     */
    public function addAgentSuccessfulLoginAuditLog(): void;

    /**
     * @return void
     */
    public function addImpersonationStartedAuditLog(): void;

    /**
     * @return void
     */
    public function addImpersonationEndedAuditLog(): void;
}
