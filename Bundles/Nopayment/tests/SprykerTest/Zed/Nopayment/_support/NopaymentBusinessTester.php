<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Nopayment;

use Codeception\Actor;
use Orm\Zed\Nopayment\Persistence\SpyNopaymentPaid;
use Orm\Zed\Nopayment\Persistence\SpyNopaymentPaidQuery;
use Propel\Runtime\Collection\ObjectCollection;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class NopaymentBusinessTester extends Actor
{
    use _generated\NopaymentBusinessTesterActions;

    /**
     * @param int $idSalesOrderItem
     *
     * @return void
     */
    public function haveNopaymentPaid(int $idSalesOrderItem): void
    {
        $paidItem = new SpyNopaymentPaid();
        $paidItem->setFkSalesOrderItem($idSalesOrderItem);
        $paidItem->save();
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return void
     */
    public function assertNopaymentPaidWereCreated(int $idSalesOrderItem): void
    {
        $nopaymentPaids = SpyNopaymentPaidQuery::create()->findByFkSalesOrderItem($idSalesOrderItem);
        $this->assertCount(1, $nopaymentPaids);
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Nopayment\Persistence\SpyNopaymentPaid
     */
    public function createNopaymentPaidEntity(int $idSalesOrderItem): SpyNopaymentPaid
    {
        $nopaymentPaidEntity = (new SpyNopaymentPaid())->setFkSalesOrderItem($idSalesOrderItem);
        $nopaymentPaidEntity->save();

        return $nopaymentPaidEntity;
    }

    /**
     * @return void
     */
    public function ensureNopaymentPaidTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getNopaymentPaidQuery());
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Nopayment\Persistence\SpyNopaymentPaid>
     */
    public function getNopaymentPaidEntities(): ObjectCollection
    {
        return $this->getNopaymentPaidQuery()->find();
    }

    /**
     * @return \Orm\Zed\Nopayment\Persistence\SpyNopaymentPaidQuery
     */
    protected function getNopaymentPaidQuery(): SpyNopaymentPaidQuery
    {
        return SpyNopaymentPaidQuery::create();
    }
}
