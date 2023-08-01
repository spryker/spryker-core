<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\SalesServicePoint;

use Codeception\Actor;
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
}
