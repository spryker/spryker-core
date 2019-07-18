<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OrderStateMachine;

use Codeception\Test\Unit;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine;
use Spryker\Zed\Oms\Business\OrderStateMachine\Timeout;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group OrderStateMachine
 * @group TimeoutTest
 * Add your own group annotations below this line
 */
class TimeoutTest extends Unit
{
    public const CONDITION_NAME = 'conditionName';
    public const EVENT_PAY = 'pay';
    public const EVENT_SHIP = 'ship';

    /**
     * @return void
     */
    public function testCheckTimeouts()
    {
        $salesOrderItem1 = $this->createSalesOrderItem(10, 1, static::EVENT_PAY);
        $salesOrderItem2 = $this->createSalesOrderItem(11, 1, static::EVENT_PAY);
        $salesOrderItem3 = $this->createSalesOrderItem(20, 2, static::EVENT_PAY);
        $salesOrderItem4 = $this->createSalesOrderItem(21, 2, static::EVENT_SHIP);

        $orderStateMachine = $this->createOrderStateMachine();

        //Check with grouping by event + order
        $orderStateMachine
            ->expects($this->exactly(3))
            ->method('triggerEvent')
            ->withConsecutive(
                [$this->equalTo(static::EVENT_PAY), $this->equalTo([$salesOrderItem1, $salesOrderItem2])],
                [$this->equalTo(static::EVENT_PAY), $this->equalTo([$salesOrderItem3])],
                [$this->equalTo(static::EVENT_SHIP), $this->equalTo([$salesOrderItem4])]
            )
            ->willReturn([]);

        $timeout = $this->createOmsTimeoutMock();
        $timeout
            ->expects($this->once())
            ->method('findItemsWithExpiredTimeouts')
            ->willReturn(new ObjectCollection([
                $salesOrderItem1,
                $salesOrderItem2,
                $salesOrderItem3,
                $salesOrderItem4,
            ]));

        $affectedItems = $timeout->checkTimeouts($orderStateMachine);

        $this->assertSame(4, $affectedItems);
    }

    /**
     * @param int $idSalesOrderItem
     * @param int $idSalesOrder
     * @param string $eventName
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function createSalesOrderItem($idSalesOrderItem, $idSalesOrder, $eventName)
    {
        return (new SpySalesOrderItem())
            ->setIdSalesOrderItem($idSalesOrderItem)
            ->setFkSalesOrder($idSalesOrder)
            ->setVirtualColumn('event', $eventName);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine
     */
    protected function createOrderStateMachine()
    {
        return $this->getMockBuilder(OrderStateMachine::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'triggerEvent',
            ])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\OrderStateMachine\TimeoutInterface
     */
    private function createOmsTimeoutMock()
    {
        return $this->getMockBuilder(Timeout::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'findItemsWithExpiredTimeouts',
            ])
            ->getMock();
    }
}
