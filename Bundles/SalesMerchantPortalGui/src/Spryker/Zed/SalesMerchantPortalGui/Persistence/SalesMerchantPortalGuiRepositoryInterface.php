<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Persistence;

use Generated\Shared\Transfer\MerchantOrderCountsTransfer;

interface SalesMerchantPortalGuiRepositoryInterface
{
    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCountsTransfer
     */
    public function getMerchantOrderCounts(int $idMerchant): MerchantOrderCountsTransfer;
}
