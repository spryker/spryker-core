<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReclamation;

use Codeception\Actor;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItemQuery;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationQuery;
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
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesReclamationBusinessTester extends Actor
{
    use _generated\SalesReclamationBusinessTesterActions;

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation
     */
    public function createSalesReclamation(int $idSalesOrder): SpySalesReclamation
    {
        $salesReclamationEntity = (new SpySalesReclamation())
            ->setFkSalesOrder($idSalesOrder)
            ->setCustomerEmail('test@test.com')
            ->setCustomerName('test customer name')
            ->setIsOpen(true);
        $salesReclamationEntity->save();

        return $salesReclamationEntity;
    }

    /**
     * @param int $idSalesReclamation
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem
     */
    public function createSalesReclamationItem(int $idSalesReclamation, int $idSalesOrderItem): SpySalesReclamationItem
    {
        $salesReclamationItemEntity = (new SpySalesReclamationItem())
            ->setFkSalesOrderItem($idSalesOrderItem)
            ->setFkSalesReclamation($idSalesReclamation);
        $salesReclamationItemEntity->save();

        return $salesReclamationItemEntity;
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItem>
     */
    public function getSalesReclamationItemEntities(): ObjectCollection
    {
        return $this->getSalesReclamationItemQuery()->find();
    }

    /**
     * @return void
     */
    public function ensureSalesReclamationItemTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getSalesReclamationItemQuery());
    }

    /**
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationQuery
     */
    protected function getSalesReclamationQuery(): SpySalesReclamationQuery
    {
        return SpySalesReclamationQuery::create();
    }

    /**
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationItemQuery
     */
    protected function getSalesReclamationItemQuery(): SpySalesReclamationItemQuery
    {
        return SpySalesReclamationItemQuery::create();
    }
}
