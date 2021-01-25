<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OrderStateMachine;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OmsCheckTimeoutsQueryCriteriaTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsEventTimeoutQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Oms\Business\OmsBusinessFactory;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine;
use Spryker\Zed\Oms\Business\OrderStateMachine\Timeout;
use Spryker\Zed\Oms\Business\OrderStateMachine\TimeoutInterface;
use Spryker\Zed\Oms\Business\Util\TimeoutProcessorCollection;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\OmsDependencyProvider;
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

        $orderStateMachine = $this->createOrderStateMachineMock(['triggerEvent']);

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
            'US store, no limit, single processor identifier' => [2, 'US', null, [2]],
            'US store, no limit, multiple processor identifiers' => [2, 'US', null, [1, 2]],
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
     * @param array $omsProcessorIdentifiers
     *
     * @return void
     */
    public function testCheckTimeoutsWithCriteria(
        int $expectedAffectedOrderItemsCount,
        ?string $storeName = null,
        ?int $limit = null,
        array $omsProcessorIdentifiers = []
    ) {
        $this->tester->createOrderWithExpiredEventTimeoutOrderItemsForStore('DE', 'pay', 'payment pending', 1, 1);
        $this->tester->createOrderWithExpiredEventTimeoutOrderItemsForStore('US', 'pay', 'payment pending', 2, 2);

        $omsCheckTimeoutQueryCriteriaTransfer = new OmsCheckTimeoutsQueryCriteriaTransfer();
        $omsCheckTimeoutQueryCriteriaTransfer
            ->setStoreName($storeName)
            ->setOmsProcessorIdentifiers($omsProcessorIdentifiers)
            ->setLimit($limit);

        $this->tester->mockConfigMethod('getActiveProcesses', ['DummyPayment01']);

        $conditionModelMock = $this->getMockBuilder(ConditionInterface::class)->onlyMethods(['check'])->getMock();
        $conditionModelMock->method('check')->willReturn(true);

        $orderStateMachineMock = $this->createOrderStateMachineMock(['runCommand']);
        $orderStateMachineMock->method('runCommand')->willReturn([]);

        $timeout = new Timeout(new OmsQueryContainer(), new TimeoutProcessorCollection(), new OmsConfig());

        $affectedOrderItems = $timeout->checkTimeouts($orderStateMachineMock, $omsCheckTimeoutQueryCriteriaTransfer);

        $this->assertSame(
            $expectedAffectedOrderItemsCount,
            $affectedOrderItems,
            sprintf('Expected "%s" sales order items but "%s" are processed.', $expectedAffectedOrderItemsCount, $affectedOrderItems)
        );
    }

    /**
     * @return array
     */
    public function dropEventsDataProvider(): array
    {
        return [
            'fallback query' => [null, null],
            'store filter query' => ['DE', null],
            'limit filter query' => [null, 1],
            'store and limit filter query' => ['DE', 1],
        ];
    }

    /**
     * @dataProvider dropEventsDataProvider()
     *
     * @param string|null $storeName
     * @param int|null $limit
     *
     * @return void
     */
    public function testCheckTimeoutsWillRemoveTimeoutEntityAfterTransition(?string $storeName = null, ?int $limit = null)
    {
        // Arrange
        $orderItemStateName = 'payment pending';
        $salesOrderEntity = $this->tester->createOrderWithExpiredEventTimeoutOrderItemsForStore('DE', 'pay', $orderItemStateName, 1);

        $omsCheckTimeoutQueryCriteriaTransfer = new OmsCheckTimeoutsQueryCriteriaTransfer();
        $omsCheckTimeoutQueryCriteriaTransfer
            ->setStoreName($storeName)
            ->setLimit($limit);

        $orderStateMachineMock = $this->createOrderStatemachineMockForCheckTimeoutsWillRemoveTimeoutEntityAfterTransition($salesOrderEntity);
        $timeout = new Timeout(new OmsQueryContainer(), new TimeoutProcessorCollection(), new OmsConfig());

        // Act
        $affectedOrderItems = $timeout->checkTimeouts($orderStateMachineMock, $omsCheckTimeoutQueryCriteriaTransfer);

        // Assert
        $expectedAffectedOrderItemsCount = 1;
        $this->assertSame(
            $expectedAffectedOrderItemsCount,
            $affectedOrderItems,
            sprintf('Expected "%s" sales order items but "%s" are processed.', $expectedAffectedOrderItemsCount, $affectedOrderItems)
        );

        $this->assertOrderItemsNotHaveTimeoutsWithState($salesOrderEntity->getItems(), 'payment pending');
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine
     */
    protected function createOrderStatemachineMockForCheckTimeoutsWillRemoveTimeoutEntityAfterTransition(SpySalesOrder $salesOrderEntity): OrderStateMachine
    {
        $this->tester->mockConfigMethod('getActiveProcesses', ['DummyPayment01']);
        $orderStateMachineMock = $this->createOrderStateMachineMock(['runCommand', 'getCondition']);

        $processedItems = [];
        $processedItems = $this->addSalesOrderItemsWithTimeoutsFromSalesOrderToProcessedItems($salesOrderEntity, $processedItems);

        $conditionModelMock = $this->getMockBuilder(ConditionInterface::class)->onlyMethods(['check'])->getMock();
        $conditionModelMock->method('check')->willReturn(true);

        $orderStateMachineMock->method('runCommand')->willReturn($processedItems);
        $orderStateMachineMock->method('getCondition')->willReturn($conditionModelMock);

        return $orderStateMachineMock;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param array $processedItems
     *
     * @return array
     */
    protected function addSalesOrderItemsWithTimeoutsFromSalesOrderToProcessedItems(SpySalesOrder $salesOrderEntity, array $processedItems)
    {
        foreach ($salesOrderEntity->getItems() as $salesOrderItemEntity) {
            if ($salesOrderItemEntity->getEventTimeouts()->count() > 0) {
                $processedItems[] = $salesOrderItemEntity;
            }
        }

        return $processedItems;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $objectCollection
     * @param string $stateName
     *
     * @return void
     */
    protected function assertOrderItemsNotHaveTimeoutsWithState(ObjectCollection $objectCollection, string $stateName): void
    {
        $omsOrderItemStateEntity = SpyOmsOrderItemStateQuery::create()->findOneByName($stateName);

        $primaryKeys = $objectCollection->getPrimaryKeys();
        $omsEventTimeoutQuery = SpyOmsEventTimeoutQuery::create()
            ->filterByFkSalesOrderItem_In($primaryKeys)
            ->filterByState($omsOrderItemStateEntity);

        $omsEventTimeoutEntityCount = $omsEventTimeoutQuery->count();

        $this->assertSame(
            0,
            $omsEventTimeoutEntityCount,
            sprintf(
                'Expected no timeouts for order item ids "%s" in state "%s" but found "%s"',
                implode(', ', $primaryKeys),
                $stateName,
                $omsEventTimeoutEntityCount
            )
        );
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
     * @param array $methods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine
     */
    protected function createOrderStateMachineMock(array $methods): OrderStateMachine
    {
        /** @var \Spryker\Zed\Oms\OmsConfig $omsConfigMock */
        $omsConfigMock = $this->tester->getModuleConfig();
        $omsBusinessFactory = new OmsBusinessFactory();
        $omsBusinessFactory->setConfig($omsConfigMock);

        return $this->getMockBuilder(OrderStateMachine::class)
            ->setConstructorArgs([
                new OmsQueryContainer(),
                $omsBusinessFactory->createOrderStateMachineBuilder(),
                $omsBusinessFactory->createUtilTransitionLog([]),
                $omsBusinessFactory->createOrderStateMachineTimeout(),
                $omsBusinessFactory->createUtilReadOnlyArrayObject($omsConfigMock->getActiveProcesses()),
                $omsBusinessFactory->getProvidedDependency(OmsDependencyProvider::CONDITION_PLUGINS),
                $omsBusinessFactory->getProvidedDependency(OmsDependencyProvider::COMMAND_PLUGINS),
                $omsBusinessFactory->createUtilReservation(),
                $omsConfigMock,
            ])
            ->onlyMethods($methods)
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
