<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Persistence;

interface SalesMerchantPortalGuiRepositoryInterface
{
    /**
     * @param int $idMerchant
     *
     * @return int[][]
     */
    public function getOrdersStoresCountData(int $idMerchant): array;

    /**
     * @param int $idMerchant
     *
     * @return int[]
     */
    public function getOrdersDashboardCardCounts(int $idMerchant): array;
}
