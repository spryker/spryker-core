<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OrderCustomReference;

use Codeception\Actor;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;

/**
 * @method \Spryker\Zed\OrderCustomReference\Business\OrderCustomReferenceFacadeInterface getFacade()
 */
class OrderCustomReferenceBusinessTester extends Actor
{
    use _generated\OrderCustomReferenceBusinessTesterActions;

    /**
     * @param int $idSalesOrder
     * @param string|null $orderCustomReference
     *
     * @return void
     */
    public function updateOrderCustomReference(int $idSalesOrder, ?string $orderCustomReference): void
    {
        $salesOrderEntity = $this->getSalesOrderQuery()->filterByIdSalesOrder($idSalesOrder)->findOne();
        $salesOrderEntity->setOrderCustomReference($orderCustomReference);
        $salesOrderEntity->save();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function getSalesOrderQuery(): SpySalesOrderQuery
    {
        return SpySalesOrderQuery::create();
    }
}
