<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Persistence;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturn;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnItem;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesReturn\Persistence\SalesReturnPersistenceFactory getFactory()
 */
class SalesReturnEntityManager extends AbstractEntityManager implements SalesReturnEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function updateOrderItem(ItemTransfer $itemTransfer): ItemTransfer
    {
        $salesOrderItemEntity = $this->getFactory()
            ->getSalesOrderItemPropelQuery()
            ->filterByIdSalesOrderItem($itemTransfer->getIdSalesOrderItem())
            ->findOne();

        $salesOrderItemEntity->fromArray($itemTransfer->modifiedToArray());

        $salesOrderItemEntity->save();

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    public function createReturn(ReturnTransfer $returnTransfer): ReturnTransfer
    {
        $salesReturnEntity = $this->getFactory()
            ->createReturnMapper()
            ->mapReturnTransferToSalesReturnEntity($returnTransfer, new SpySalesReturn());

        $salesReturnEntity->save();
        $returnTransfer->fromArray($salesReturnEntity->toArray(), true);

        return $returnTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnItemTransfer $returnItemTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnItemTransfer
     */
    public function createReturnItem(ReturnItemTransfer $returnItemTransfer): ReturnItemTransfer
    {
        $salesReturnItemEntity = $this->getFactory()
            ->createReturnMapper()
            ->mapReturnItemTransferToSalesReturnItemEntity($returnItemTransfer, new SpySalesReturnItem());

        $salesReturnItemEntity->save();
        $returnItemTransfer->fromArray($salesReturnItemEntity->toArray(), true);

        return $returnItemTransfer;
    }
}
