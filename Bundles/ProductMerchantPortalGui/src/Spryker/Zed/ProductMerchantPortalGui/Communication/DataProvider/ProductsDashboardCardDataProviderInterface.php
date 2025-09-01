<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider;

use Generated\Shared\Transfer\MerchantDashboardCardTransfer;

interface ProductsDashboardCardDataProviderInterface
{
    /**
     * @return \Generated\Shared\Transfer\MerchantDashboardCardTransfer
     */
    public function getProductsCard(): MerchantDashboardCardTransfer;
}
