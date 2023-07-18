<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Communication\Plugin\Oms;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PickingStartedRequestTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * @method \Spryker\Zed\PickingList\Business\PickingListFacadeInterface getFacade()
 * @method \Spryker\Zed\PickingList\PickingListConfig getConfig()
 * @method \Spryker\Zed\PickingList\Communication\PickingListCommunicationFactory getFactory()
 */
class IsPickingStartedConditionPlugin extends AbstractPlugin implements ConditionInterface
{
    /**
     * {@inheritDoc}
     * - Requires `SpySalesOrderItem.uuid` to be set.
     * - Checks if picking of at least one picking list is started for the given sales order item.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem): bool
    {
        $orderTransfer = $this->getFactory()
            ->createPickingListMapper()
            ->mapSalesOrderItemEntityToOrderTransfer($orderItem, new OrderTransfer());

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\OrderTransfer> $orderTransfers */
        $orderTransfers = $this->getFacade()
            ->isPickingStarted((new PickingStartedRequestTransfer())->addOrder($orderTransfer))
            ->getOrders();

        /** @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer */
        $orderTransfer = $orderTransfers->getIterator()->current();

        return $orderTransfer->getIsPickingStartedOrFail();
    }
}
