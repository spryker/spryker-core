<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesQuoteRequestConnector;

use Codeception\Actor;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;

/**
 * @method \Spryker\Zed\SalesQuoteRequestConnector\Business\SalesQuoteRequestFacadeInterface getFacade()
 */
class SalesQuoteRequestConnectorBusinessTester extends Actor
{
    use _generated\SalesQuoteRequestConnectorBusinessTesterActions;

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function findOrderByIdSalesOrder(int $idSalesOrder): SpySalesOrder
    {
        return $this->getSalesOrderQuery()
            ->filterByIdSalesOrder($idSalesOrder)
            ->findOne();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function getSalesOrderQuery(): SpySalesOrderQuery
    {
        return SpySalesOrderQuery::create();
    }
}
