<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Persistence;

use Generated\Shared\Transfer\MerchantOrderCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderCountsTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer;

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
     * @param \Generated\Shared\Transfer\MerchantOrderItemTableCriteriaTransfer $merchantOrderItemTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer
     */
    public function getMerchantOrderItemTableData(
        MerchantOrderItemTableCriteriaTransfer $merchantOrderItemTableCriteriaTransfer
    ): MerchantOrderItemCollectionTransfer;

    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCountsTransfer
     */
    public function getMerchantOrderCounts(int $idMerchant): MerchantOrderCountsTransfer;
}
