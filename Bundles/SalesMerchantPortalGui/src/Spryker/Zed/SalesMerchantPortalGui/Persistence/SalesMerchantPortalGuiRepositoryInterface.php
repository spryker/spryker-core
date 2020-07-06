<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Persistence;

use Generated\Shared\Transfer\MerchantOrderCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderCountsTransfer;

interface SalesMerchantPortalGuiRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCollectionTransfer
     */
    public function getMerchantOrderTableData(
        MerchantOrderTableCriteriaTransfer $merchantOrderTableCriteriaTransfer
    ): MerchantOrderCollectionTransfer;

    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCountsTransfer
     */
    public function getMerchantOrderCounts(int $idMerchant): MerchantOrderCountsTransfer;
}
