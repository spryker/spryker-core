<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OrderStateMachine;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OmsCheckTimeoutsQueryCriteriaTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine;
use Spryker\Zed\Oms\Business\OrderStateMachine\Timeout;
use Spryker\Zed\Oms\Business\OrderStateMachine\TimeoutInterface;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\Persistence\OmsQueryContainer;

/**
 * Auto-generated group annotations
 *
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
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCheckTimeouts(): void
    {
        $salesOrderItem1 = $this->createSalesOrderItem(10, 1, static::EVENT_PAY);
        $salesOrderItem2 = $this->createSalesOrderItem(11, 1, static::EVENT_PAY);
        $salesOrderItem3 = $this->createSalesOrderItem(20, 2, static::EVENT_PAY);
        $salesOrderItem4 = $this->createSalesOrderItem(21, 2, static::EVENT_SHIP);

        $orderStateMachine = $this->createOrderStateMachineMock();

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
     * @return array[]
     */
    public function timeoutDataProvider(): array
    {
        return [
            'no store name, no limit' => [3, null, null],
            'no store name, limit' => [1, null, 1], // Will take only first created order
            'US store, no limit' => [2, 'US', null],
            'DE store, no limit' => [1, 'DE', null],
        ];
    }

    /**
     * This method will always create 2 orders:
     * - One DE order with one order item which has an event timeout.
     * - One US order with two order items which have an event timeout.
     *
     * @dataProvider timeoutDataProvider()
     *
     * @param int $expectedAffectedOrderItemsCount
     * @param string|null $storeName
     * @param int|null $limit
     *
     * @return void
     */
    public function testCheckTimeoutsWithCriteria(int $expectedAffectedOrderItemsCount, ?string $storeName = null, ?int $limit = null)
    {
        $this->tester->createOrderWithExpiredEventTimeoutOrderItemsForStore('DE', 1);
        $this->tester->createOrderWithExpiredEventTimeoutOrderItemsForStore('US', 2);

        $omsCheckTimeoutQueryCriteriaTransfer = new OmsCheckTimeoutsQueryCriteriaTransfer();
        $omsCheckTimeoutQueryCriteriaTransfer
            ->setStoreName($storeName)
            ->setLimit($limit);

        $orderStateMachineMock = $this->createOrderStateMachineMock();

        $timeout = new Timeout(new OmsQueryContainer(), new OmsConfig());
        $affectedOrderItems = $timeout->checkTimeouts($orderStateMachineMock, $omsCheckTimeoutQueryCriteriaTransfer);

        $this->assertSame($expectedAffectedOrderItemsCount, $affectedOrderItems);
    }

    /**
     * @param int $idSalesOrderItem
     * @param int $idSalesOrder
     * @param string $eventName
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function createSalesOrderItem(int $idSalesOrderItem, int $idSalesOrder, string $eventName): SpySalesOrderItem
    {
        return (new SpySalesOrderItem())
            ->setIdSalesOrderItem($idSalesOrderItem)
            ->setFkSalesOrder($idSalesOrder)
            ->setVirtualColumn('event', $eventName);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine
     */
    protected function createOrderStateMachineMock(): OrderStateMachine
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
    private function createOmsTimeoutMock(): TimeoutInterface
    {
        return $this->getMockBuilder(Timeout::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'findItemsWithExpiredTimeouts',
            ])
            ->getMock();
    }
}
