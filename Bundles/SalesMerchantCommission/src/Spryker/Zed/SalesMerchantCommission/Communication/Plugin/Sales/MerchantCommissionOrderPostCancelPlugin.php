<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Communication\Plugin\Sales;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostCancelPluginInterface;

/**
 * @method \Spryker\Zed\SalesMerchantCommission\SalesMerchantCommissionConfig getConfig()
 * @method \Spryker\Zed\SalesMerchantCommission\Business\SalesMerchantCommissionFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesMerchantCommission\Communication\SalesMerchantCommissionCommunicationFactory getFactory()
 */
class MerchantCommissionOrderPostCancelPlugin extends AbstractPlugin implements OrderPostCancelPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ItemTransfer.idSalesOrderItem` to be set.
     * - Requires `OrderTransfer.idSalesOrder` to be set.
     * - Retrieves sales merchant commissions for provided items from Persistence.
     * - Updates `SalesMerchantCommissionTransfer.refundedAmount` with refunded amount.
     * - Persists sales merchant commissions with new refunded amounts.
     * - Recalculates order.
     * - Updates order totals and order items with merchant commissions amount.
     * - Returns `OrderTransfer` with updated merchant commissions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function postCancel(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFacade()->refundMerchantCommissions(
            $orderTransfer,
            $orderTransfer->getItems()->getArrayCopy(),
        );
    }
}
