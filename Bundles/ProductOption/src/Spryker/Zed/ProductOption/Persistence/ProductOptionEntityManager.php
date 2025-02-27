<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionPersistenceFactory getFactory()
 */
class ProductOptionEntityManager extends AbstractEntityManager implements ProductOptionEntityManagerInterface
{
    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteSalesOrderItemProductOptionsBySalesOrderItemIds(array $salesOrderItemIds): void
    {
        $this->getFactory()
            ->createSalesOrderItemOptionQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds)
            ->delete();
    }
}
