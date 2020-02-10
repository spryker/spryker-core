<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferSales\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

interface ProductOfferSalesFacadeInterface
{
    /**
     * Specification:
     * - Expands SpySalesOrderItemEntityTransfer with ItemTransfer::getProductOfferReference().
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandOrderItemWithProductOffer(
        SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer,
        ItemTransfer $itemTransfer
    ): SpySalesOrderItemEntityTransfer;
}
