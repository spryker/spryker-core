<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesOrderMerchantSaveTransfer;
use Generated\Shared\Transfer\SalesOrderMerchantTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

interface SalesMerchantConnectorFacadeInterface
{
    /**
     * Specification:
     * - Adds OrderItemReference to SpySalesOrderItemEntityTransfer by generating the reference based on OrderItem ID
     * - If ItemTransfer.MerchantReference exists, it adds that to SpySalesOrderItemEntityTransfer as well
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandOrderItemWithReferences(
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity,
        ItemTransfer $itemTransfer
    ): SpySalesOrderItemEntityTransfer;

    /**
     * Specification:
     * - Create relation between order and merchant and save data to `spy_sales_order_merchant`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderMerchantSaveTransfer $salesOrderMerchantSaveTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer
     */
    public function createSalesOrderMerchant(SalesOrderMerchantSaveTransfer $salesOrderMerchantSaveTransfer): SalesOrderMerchantTransfer;
}
