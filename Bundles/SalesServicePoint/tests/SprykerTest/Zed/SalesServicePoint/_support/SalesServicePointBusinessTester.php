<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\SalesServicePoint;

use Codeception\Actor;
use Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePointQuery;
use Propel\Runtime\Collection\ObjectCollection;

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
class SalesServicePointBusinessTester extends Actor
{
    use _generated\SalesServicePointBusinessTesterActions;

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
     * @return \Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePointQuery
     */
    public function getSalesOrderItemServicePointQuery(): SpySalesOrderItemServicePointQuery
    {
        return SpySalesOrderItemServicePointQuery::create();
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePoint>
     */
    public function getSalesOrderItemServicePointEntities(): ObjectCollection
    {
        return $this->getSalesOrderItemServicePointQuery()->find();
    }
}
