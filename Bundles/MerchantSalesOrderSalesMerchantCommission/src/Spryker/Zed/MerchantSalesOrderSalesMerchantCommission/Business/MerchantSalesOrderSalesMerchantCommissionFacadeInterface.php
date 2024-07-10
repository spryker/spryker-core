<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Business;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface MerchantSalesOrderSalesMerchantCommissionFacadeInterface
{
    /**
     * Specification:
     * - Requires `MerchantOrderTransfer.idMerchantOrder` to be set.
     * - Requires `MerchantOrderTransfer.idOrder` to be set.
     * - Requires `MerchantOrderTransfer.merchantReference` to be set.
     * - Requires `MerchantOrderTransfer.merchantOrderItems.orderItem` to be set.
     * - Summarizes merchant commission amounts for the merchant order taken from `MerchantOrderTransfer.merchantOrderItems.orderItem`.
     * - Persists `TotalsTransfer.merchantCommissionTotal` and `TotalsTransfer.merchantCommissionRefundedTotal`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return void
     */
    public function saveMerchantCommissionToMerchantOrderTotals(MerchantOrderTransfer $merchantOrderTransfer): void;

    /**
     * Specification:
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Requires `OrderTransfer.items.merchantReference` to be provided.
     * - Summarizes merchant commission amounts for the merchant order taken from `OrderTransfer.items`.
     * - Updates `TotalsTransfer.merchantCommissionTotal` and `TotalsTransfer.merchantCommissionRefundedTotal`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return void
     */
    public function updateMerchantCommissionToMerchantOrderTotals(OrderTransfer $orderTransfer, array $itemTransfers): void;
}
