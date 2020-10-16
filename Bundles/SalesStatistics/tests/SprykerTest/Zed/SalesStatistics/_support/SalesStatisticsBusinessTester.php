<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesStatistics;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @method \Spryker\Zed\SalesStatistics\Business\SalesStatisticsFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesStatisticsBusinessTester extends Actor
{
    use _generated\SalesStatisticsBusinessTesterActions;

    public const ITEM_NAME = 'test1';

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function haveOrderWithOneItem(): SpySalesOrder
    {
        return $this->haveSalesOrderEntity([
            (new ItemBuilder([ItemTransfer::NAME => static::ITEM_NAME]))->build(),
        ]);
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function haveOrderWithTwoItems(): SpySalesOrder
    {
        return $this->haveSalesOrderEntity([
            (new ItemBuilder([ItemTransfer::NAME => static::ITEM_NAME]))->build(),
            (new ItemBuilder([ItemTransfer::NAME => static::ITEM_NAME]))->build(),
        ]);
    }
}
