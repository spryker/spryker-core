<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCardBalance;

use Codeception\Actor;
use Orm\Zed\GiftCardBalance\Persistence\SpyGiftCardBalanceLog;
use Orm\Zed\GiftCardBalance\Persistence\SpyGiftCardBalanceLogQuery;

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
 * @method \Spryker\Zed\GiftCardBalance\Business\GiftCardBalanceFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class GiftCardBalanceBusinessTester extends Actor
{
    use _generated\GiftCardBalanceBusinessTesterActions;

    /**
     * @param int $idGiftCard
     * @param int $idSalesOrder
     * @param int $value
     *
     * @return void
     */
    public function createGiftCardBalanceLogEntity(int $idGiftCard, int $idSalesOrder, int $value): void
    {
        $giftCardBalanceLogEntity = new SpyGiftCardBalanceLog();
        $giftCardBalanceLogEntity->setFkGiftCard($idGiftCard);
        $giftCardBalanceLogEntity->setFkSalesOrder($idSalesOrder);
        $giftCardBalanceLogEntity->setValue($value);

        $giftCardBalanceLogEntity->save();
    }

    /**
     * @param list<int> $salesOrderIds
     *
     * @return array<int, \Orm\Zed\GiftCardBalance\Persistence\SpyGiftCardBalanceLog>
     */
    public function getGiftCardBalanceLogEntitiesIndexedByIdSalesOrder(array $salesOrderIds): array
    {
        $giftCardBalanceLogEntitiesIndexedByIdSalesOrder = [];
        $giftCardBalanceLogEntities = $this->getGiftCardBalanceLogQuery()
            ->filterByFkSalesOrder_In($salesOrderIds)
            ->find();

        foreach ($giftCardBalanceLogEntities as $giftCardBalanceLogEntity) {
            $giftCardBalanceLogEntitiesIndexedByIdSalesOrder[$giftCardBalanceLogEntity->getFkSalesOrder()] = $giftCardBalanceLogEntity;
        }

        return $giftCardBalanceLogEntitiesIndexedByIdSalesOrder;
    }

    /**
     * @return \Orm\Zed\GiftCardBalance\Persistence\SpyGiftCardBalanceLogQuery
     */
    protected function getGiftCardBalanceLogQuery(): SpyGiftCardBalanceLogQuery
    {
        return SpyGiftCardBalanceLogQuery::create();
    }
}
