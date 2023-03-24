<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Communication\Plugin\Oms;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * @method \Spryker\Zed\PickingList\Business\PickingListFacadeInterface getFacade()
 * @method \Spryker\Zed\PickingList\PickingListConfig getConfig()
 * @method \Spryker\Zed\PickingList\Communication\PickingListCommunicationFactory getFactory()
 */
class IsPickingListGenerationFinishedConditionPlugin extends AbstractPlugin implements ConditionInterface
{
    /**
     * {@inheritDoc}
     * - Checks if picking lists generation is finished for the given sales order item.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        $orderTransfer = $this->getFactory()
            ->createPickingListMapper()
            ->mapSalesOrderItemEntityToOrderTransfer($orderItem, new OrderTransfer());

        return $this->getFacade()->isPickingListGenerationFinishedForOrder($orderTransfer);
    }
}
