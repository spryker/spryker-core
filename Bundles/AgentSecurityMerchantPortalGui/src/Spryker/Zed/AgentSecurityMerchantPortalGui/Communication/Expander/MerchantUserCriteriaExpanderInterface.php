<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Expander;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;

interface MerchantUserCriteriaExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserCriteriaTransfer
     */
    public function expand(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): MerchantUserCriteriaTransfer;
}
