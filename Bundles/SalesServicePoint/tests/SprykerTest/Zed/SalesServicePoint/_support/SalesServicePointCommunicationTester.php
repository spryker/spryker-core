<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\SalesServicePoint;

use Codeception\Actor;
use Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePoint;
use Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePointQuery;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\SalesServicePoint\Business\SalesServicePointFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\SalesServicePoint\PHPMD)
 */
class SalesServicePointCommunicationTester extends Actor
{
    use _generated\SalesServicePointCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureSalesOrderItemServicePointDatabaseTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty(
            $this->getSalesOrderItemServicePointQuery(),
        );
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePoint|null
     */
    public function findSalesOrderItemServicePoint(int $idSalesOrderItem): ?SpySalesOrderItemServicePoint
    {
        return $this->getSalesOrderItemServicePointQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->findOne();
    }

    /**
     * @return \Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePointQuery
     */
    public function getSalesOrderItemServicePointQuery(): SpySalesOrderItemServicePointQuery
    {
        return SpySalesOrderItemServicePointQuery::create();
    }
}
