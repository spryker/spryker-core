<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DummyMarketplacePayment;

use Codeception\Actor;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\Collection;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\DummyMarketplacePayment\Business\DummyMarketplacePaymentFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class DummyMarketplacePaymentBusinessTester extends Actor
{
    use _generated\DummyMarketplacePaymentBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function haveSalesOrderWithItems(SaveOrderTransfer $saveOrderTransfer): SpySalesOrder
    {
        $salesOrder = (new SpySalesOrder())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        $orderItemsCollection = new Collection();
        foreach ($saveOrderTransfer->getOrderItems() as $orderItemTransfer) {
            $orderItemsCollection->append(
                (new SpySalesOrderItem())
                ->setIdSalesOrderItem($orderItemTransfer->getIdSalesOrderItem())
                ->setFkSalesOrder($salesOrder->getIdSalesOrder())
            );
        }
        $salesOrder->setItems($orderItemsCollection);

        return $salesOrder;
    }
}
