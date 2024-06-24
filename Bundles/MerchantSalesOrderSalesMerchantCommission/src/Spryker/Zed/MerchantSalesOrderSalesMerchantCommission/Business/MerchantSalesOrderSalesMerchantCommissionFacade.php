<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Business;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Business\MerchantSalesOrderSalesMerchantCommissionBusinessFactory getFactory()
 */
class MerchantSalesOrderSalesMerchantCommissionFacade extends AbstractFacade implements MerchantSalesOrderSalesMerchantCommissionFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return void
     */
    public function saveMerchantCommissionToMerchantOrderTotals(MerchantOrderTransfer $merchantOrderTransfer): void
    {
        $this->getFactory()
            ->createMerchantOrderWriter()
            ->saveMerchantCommissionToMerchantOrderTotals($merchantOrderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return void
     */
    public function updateMerchantCommissionToMerchantOrderTotals(OrderTransfer $orderTransfer, array $itemTransfers): void
    {
        $this->getFactory()
            ->createMerchantOrderWriter()
            ->updateMerchantCommissionToMerchantOrderTotals($orderTransfer, $itemTransfers);
    }
}
