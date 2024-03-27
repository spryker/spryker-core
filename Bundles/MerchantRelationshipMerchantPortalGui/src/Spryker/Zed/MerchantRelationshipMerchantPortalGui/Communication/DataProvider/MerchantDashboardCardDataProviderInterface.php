<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\DataProvider;

use Generated\Shared\Transfer\MerchantDashboardCardTransfer;

interface MerchantDashboardCardDataProviderInterface
{
    /**
     * @return \Generated\Shared\Transfer\MerchantDashboardCardTransfer
     */
    public function getMerchantRelationDashboardCard(): MerchantDashboardCardTransfer;
}
