<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Expander;

use Generated\Shared\Transfer\MerchantDashboardCardTransfer;

interface MerchantRelationRequestMerchantDashboardCardExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantDashboardCardTransfer $merchantDashboardCardTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantDashboardCardTransfer
     */
    public function expand(MerchantDashboardCardTransfer $merchantDashboardCardTransfer): MerchantDashboardCardTransfer;
}
