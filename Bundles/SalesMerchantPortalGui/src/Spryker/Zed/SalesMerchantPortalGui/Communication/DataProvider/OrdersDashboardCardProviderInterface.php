<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\DataProvider;

use Generated\Shared\Transfer\MerchantDashboardCardTransfer;

interface OrdersDashboardCardProviderInterface
{
    /**
     * @return \Generated\Shared\Transfer\MerchantDashboardCardTransfer
     */
    public function getDashboardCard(): MerchantDashboardCardTransfer;
}
