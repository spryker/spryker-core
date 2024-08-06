<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Communication\Plugin\Oms\Condition;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * @method \Spryker\Zed\SalesPaymentMerchant\Business\SalesPaymentMerchantFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesPaymentMerchant\Communication\SalesPaymentMerchantCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig getConfig()
 */
class IsMerchantPayoutReversedConditionPlugin extends AbstractPlugin implements ConditionInterface
{
    /**
     * {@inheritDoc}
     * - Checks if the used payment method is configured to support reverse payout.
     * - Requires the `ItemTransfer.merchantReference` property to be set.
     * - Expects the `OrderTransfer.orderReference` property to be set.
     * - If the `ItemTransfer.merchantReference` is not set, returns true.
     * - If the transfer endpoint URL is not found, returns true.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        $salesOrderMapper = $this->getFactory()->createSalesOrderMapper();
        $orderTransfer = $salesOrderMapper->mapSalesOrderToOrderTransfer(
            $orderItem->getOrder(),
            new OrderTransfer(),
        );

        $salesOrderItemTransfer = $salesOrderMapper->mapSalesOrderItemEntityToItemTransfer(
            $orderItem,
            new ItemTransfer(),
        );

        return $this->getFacade()->isPayoutReversalSupportedForPaymentMethodUsedForOrder($salesOrderItemTransfer, $orderTransfer);
    }
}
