<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Communication\Plugin\MerchantSalesOrder;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderPostCreatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Business\MerchantSalesOrderSalesMerchantCommissionFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\MerchantSalesOrderSalesMerchantCommissionConfig getConfig()
 */
class UpdateMerchantCommissionTotalsMerchantOrderPostCreatePlugin extends AbstractPlugin implements MerchantOrderPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
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
    public function postCreate(MerchantOrderTransfer $merchantOrderTransfer): void
    {
        $this->getFacade()->saveMerchantCommissionToMerchantOrderTotals($merchantOrderTransfer);
    }
}
