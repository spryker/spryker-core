<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Persistence;

use Generated\Shared\Transfer\ItemTransfer;
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
}
