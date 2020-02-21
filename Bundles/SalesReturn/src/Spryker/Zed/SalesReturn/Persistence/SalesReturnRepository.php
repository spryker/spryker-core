<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Persistence;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesReturn\Persistence\SalesReturnPersistenceFactory getFactory()
 */
class SalesReturnRepository extends AbstractRepository implements SalesReturnRepositoryInterface
{
    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    public function findIdSalesOrderItem(int $idSalesOrderItem): ?ItemTransfer
    {
        $salesOrderItemEntity = $this->getFactory()
            ->getSalesOrderItemPropelQuery()
            ->filterByIdSalesOrderItem($idSalesOrderItem)
            ->findOne();

        if (!$salesOrderItemEntity) {
            return null;
        }

        return (new ItemTransfer())->fromArray($salesOrderItemEntity->toArray(), true);
    }
}
