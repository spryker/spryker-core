<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Logger\DataProvider;

use Generated\Shared\Transfer\UserTransfer;

interface AuditLoggerUserProviderInterface
{
    /**
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findOriginalUser(): ?UserTransfer;
}
