<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Communication\Plugin\Refund;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\RefundExtension\Dependency\Plugin\RefundPostSavePluginInterface;

/**
 * @method \Spryker\Zed\SalesMerchantCommission\SalesMerchantCommissionConfig getConfig()
 * @method \Spryker\Zed\SalesMerchantCommission\Business\SalesMerchantCommissionFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesMerchantCommission\Communication\SalesMerchantCommissionCommunicationFactory getFactory()
 */
class MerchantCommissionRefundPostSavePlugin extends AbstractPlugin implements RefundPostSavePluginInterface
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
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function postSave(RefundTransfer $refundTransfer, OrderTransfer $orderTransfer): RefundTransfer
    {
        $this->getFacade()->refundMerchantCommissions($orderTransfer, $refundTransfer->getItems()->getArrayCopy());

        return $refundTransfer;
    }
}
