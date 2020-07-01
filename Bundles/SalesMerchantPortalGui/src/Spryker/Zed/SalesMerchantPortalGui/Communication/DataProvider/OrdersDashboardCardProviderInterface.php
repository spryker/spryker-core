<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\DataProvider;

use Generated\Shared\Transfer\DashboardCardTransfer;

interface OrdersDashboardCardProviderInterface
{
    /**
     * @return \Generated\Shared\Transfer\DashboardCardTransfer
     */
    public function getDashboardCard(): DashboardCardTransfer;
}
