<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Communication\Plugin\SalesMerchantCommission;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesMerchantCommissionExtension\Dependency\Plugin\PostRefundMerchantCommissionPluginInterface;

/**
 * @method \Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Business\MerchantSalesOrderSalesMerchantCommissionFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\MerchantSalesOrderSalesMerchantCommissionConfig getConfig()
 */
class UpdateMerchantCommissionTotalsPostRefundMerchantCommissionPlugin extends AbstractPlugin implements PostRefundMerchantCommissionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Expects `OrderTransfer.items.merchantReference` to be provided.
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
    public function execute(OrderTransfer $orderTransfer, array $itemTransfers): void
    {
        $this->getFacade()->updateMerchantCommissionToMerchantOrderTotals($orderTransfer, $itemTransfers);
    }
}
