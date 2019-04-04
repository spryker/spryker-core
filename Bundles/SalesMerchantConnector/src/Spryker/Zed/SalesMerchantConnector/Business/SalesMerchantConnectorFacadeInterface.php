<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

interface SalesMerchantConnectorFacadeInterface
{
    /**
     * Specification:
     * - Adds merchant order reference to sales order item based on config string.
     * - If there is no fkMerchant in ItemTransfer, returns $salesOrderItemEntity without changes
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function addMerchantOrderReferenceToSalesOrderItem(
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity,
        ItemTransfer $itemTransfer
    ): SpySalesOrderItemEntityTransfer;
}
