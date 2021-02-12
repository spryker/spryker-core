<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms;

use Codeception\Actor;
use Codeception\Stub;
use DateInterval;
use DateTime;
use Exception;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use PHPUnit\Framework\ExpectationFailedException;
use Propel\Runtime\Collection\ObjectCollection;
use ReflectionClass;
use Spryker\Zed\Oms\Business\Lock\LockerInterface;
use Spryker\Zed\Oms\Business\Lock\TriggerLocker;
use Spryker\Zed\Oms\Business\OrderStateMachine\LockedOrderStateMachine;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface;
use Spryker\Zed\Oms\Business\Util\ActiveProcessFetcher;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\Persistence\OmsQueryContainer;

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
 * @method \Spryker\Zed\Oms\Business\OmsFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class OmsBusinessTester extends Actor
{
    use _generated\OmsBusinessTesterActions;

    /**
     * @return void
     */
    public function resetReservedStatesCache(): void
    {
        $reflectionResolver = new ReflectionClass(ActiveProcessFetcher::class);
        $reflectionProperty = $reflectionResolver->getProperty('reservedStatesCache');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue([]);
    }

    /**
     * @return void
     */
    public function resetReservedStateProcessNamesCache(): void
    {
        $reflectionResolver = new ReflectionClass(ActiveProcessFetcher::class);
        $reflectionProperty = $reflectionResolver->getProperty('reservedStateProcessNamesCache');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue([]);
    }

    /**
     * @param string $stateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderByStateMachineProcessName(string $stateMachineProcessName): OrderTransfer
    {
        $quoteTransfer = $this->buildFakeQuote(
            $this->haveCustomer(),
            $this->haveStore([StoreTransfer::NAME => 'DE'])
        );

        $saveOrderTransfer = $this->haveOrderFromQuote($quoteTransfer, $stateMachineProcessName);

        return (new OrderTransfer())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setStore($quoteTransfer->getStore()->getName())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setItems($saveOrderTransfer->getOrderItems());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function buildFakeQuote(CustomerTransfer $customerTransfer, StoreTransfer $storeTransfer): QuoteTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem()
            ->withItem()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $quoteTransfer
            ->setCustomer($customerTransfer)
            ->setStore($storeTransfer);

        return $quoteTransfer;
    }

    /**
     * @param string $storeName
     * @param string $eventName
     * @param string $stateName
     * @param int $orderItemsAmount
     * @param int|null $omsProcessorIdentifier
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function createOrderWithExpiredEventTimeoutOrderItemsForStore(
        string $storeName,
        string $eventName,
        string $stateName,
        int $orderItemsAmount,
        ?int $omsProcessorIdentifier = null
    ): SpySalesOrder {
        $dateTime = new DateTime('now');
        $dateTime->sub(DateInterval::createFromDateString('1 day'));
        $processName = 'DummyPayment01';
        $salesOrderTransferDE = $this->haveOrder([], $processName);
        $salesOrderEntity = SpySalesOrderQuery::create()->findOneByIdSalesOrder($salesOrderTransferDE->getIdSalesOrder());
        $salesOrderEntity->setStore($storeName)
            ->setOmsProcessorIdentifier($omsProcessorIdentifier)
            ->save();

        $salesOrderItemDefaults = [
            'state' => $stateName,
            'process' => $processName,
        ];
        for ($i = 0; $i < $orderItemsAmount; $i++) {
            $salesOrderItemEntity = $this->createSalesOrderItemForOrder($salesOrderTransferDE->getIdSalesOrder(), $salesOrderItemDefaults);
            $omsOrderItemStateEntity = $this->haveOmsOrderItemStateEntity($stateName);
            $omsEventTimeoutEntity = $this->haveOmsEventTimeoutEntity([
                'fk_sales_order_item' => $salesOrderItemEntity->getIdSalesOrderItem(),
                'fk_oms_order_item_state' => $omsOrderItemStateEntity->getIdOmsOrderItemState(),
                'event' => $eventName,
                'timeout' => $dateTime,
            ]);
            $salesOrderItemEntity->addEventTimeout($omsEventTimeoutEntity);
            $salesOrderItemEntity->setState($omsOrderItemStateEntity);
            $salesOrderEntity->addItem($salesOrderItemEntity);
        }

        return $salesOrderEntity;
    }

    /**
     * @param string $storeName
     * @param string $stateName
     * @param string $processName
     * @param int $orderItemsAmount One spy_sales_order_item is added always by the {@link \SprykerTest\Zed\Oms\_generated\OmsBusinessTesterActions::haveOrder()} method.
     * @param int|null $omsProcessorIdentifier
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function createOrderWithOrderItemsInStateAndProcessForStore(
        string $storeName,
        string $stateName,
        string $processName,
        int $orderItemsAmount = 0,
        ?int $omsProcessorIdentifier = null
    ): SpySalesOrder {
        $salesOrderTransferDE = $this->haveOrder([], $processName);
        $salesOrderEntity = SpySalesOrderQuery::create()->findOneByIdSalesOrder($salesOrderTransferDE->getIdSalesOrder());
        $salesOrderEntity->setStore($storeName)
            ->setOmsProcessorIdentifier($omsProcessorIdentifier)
            ->save();
        $omsOrderItemStateEntity = $this->haveOmsOrderItemStateEntity($stateName);
        $salesOrderEntity->getItems()->getFirst()->setState($omsOrderItemStateEntity)->save();

        for ($i = 1; $i < $orderItemsAmount; $i++) {
            $salesOrderItemEntity = $this->createSalesOrderItemForOrder(
                $salesOrderTransferDE->getIdSalesOrder(),
                ['state' => $stateName, 'process' => $processName]
            );
            $salesOrderEntity->addItem($salesOrderItemEntity);
        }

        return $salesOrderEntity;
    }

    /**
     * @param string $methodUnderTest
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface $lockedOrderStatemachine
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItemEntityCollection
     *
     * @return void
     */
    public function callLockedOrderStatemachineMethod(
        string $methodUnderTest,
        OrderStateMachineInterface $lockedOrderStatemachine,
        ObjectCollection $orderItemEntityCollection
    ): void {
        if ($methodUnderTest === 'triggerEvent') {
            $lockedOrderStatemachine->triggerEvent('event identifier', $orderItemEntityCollection->getArrayCopy(), []);

            return;
        }
        if ($methodUnderTest === 'triggerEventForNewItem') {
            $lockedOrderStatemachine->triggerEventForNewItem($orderItemEntityCollection->getArrayCopy(), []);

            return;
        }
        if ($methodUnderTest === 'triggerEventForNewOrderItems') {
            $lockedOrderStatemachine->triggerEventForNewOrderItems($orderItemEntityCollection->getPrimaryKeys(), []);

            return;
        }
        if ($methodUnderTest === 'triggerEventForOneOrderItem') {
            $lockedOrderStatemachine->triggerEventForOneOrderItem('event identifier', current($orderItemEntityCollection->getPrimaryKeys()), []);

            return;
        }

        $lockedOrderStatemachine->triggerEventForOrderItems('event identifier', $orderItemEntityCollection->getPrimaryKeys(), []);
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    public function createOrderItemEntityCollection(): ObjectCollection
    {
        $orderItemEntityCollection = new ObjectCollection();
        $orderItemEntityCollection->append((new SpySalesOrderItem())->setIdSalesOrderItem(10));
        $orderItemEntityCollection->append((new SpySalesOrderItem())->setIdSalesOrderItem(11));
        $orderItemEntityCollection->append((new SpySalesOrderItem())->setIdSalesOrderItem(12));

        return $orderItemEntityCollection;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface
     */
    public function createLockedOrderStatemachineWithTriggerSuccess(): OrderStateMachineInterface
    {
        $triggerLocker = $this->createTriggerLocker();

        $orderStatemachineMock = $this->getOrderStatemachineMockForSuccessfulTriggeredEvents();

        return new LockedOrderStateMachine($orderStatemachineMock, $triggerLocker);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface
     */
    public function createLockedOrderStatemachineWithTriggerException(): OrderStateMachineInterface
    {
        $triggerLocker = $this->createTriggerLocker();

        $orderStateMachineMock = $this->getOrderStatemachineMockForFailedTriggeredEvents();

        return new LockedOrderStateMachine($orderStateMachineMock, $triggerLocker);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Lock\LockerInterface
     */
    public function createTriggerLocker(): LockerInterface
    {
        return new TriggerLocker(new OmsQueryContainer(), new OmsConfig());
    }

    /**
     * @return bool
     */
    public function hasLockedOrderItems(): bool
    {
        return SpyOmsStateMachineLockQuery::create()->count() > 0;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItemEntityCollection
     *
     * @return void
     */
    public function lockOrderItems(ObjectCollection $orderItemEntityCollection): void
    {
        $orderItemsIds = $orderItemEntityCollection->getPrimaryKeys();
        $this->createTriggerLocker()->acquire($orderItemsIds);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    public function getOrderStatemachineMockForSuccessfulTriggeredEvents()
    {
        return Stub::makeEmpty(OrderStateMachineInterface::class, [
            'checkConditions' => function () {
                return 1;
            },
        ]);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    public function getOrderStatemachineMockForFailedTriggeredEvents()
    {
        return Stub::makeEmpty(OrderStateMachineInterface::class, [
            'triggerEvent' => function () {
                throw new Exception('Trigger failed.');
            },
            'triggerEventForNewItem' => function () {
                throw new Exception('Trigger failed.');
            },
            'triggerEventForNewOrderItems' => function () {
                throw new Exception('Trigger failed.');
            },
            'triggerEventForOneOrderItem' => function () {
                throw new Exception('Trigger failed.');
            },
            'triggerEventForOrderItems' => function () {
                throw new Exception('Trigger failed.');
            },
        ]);
    }

    /**
     * @param int $expectedLockedEntityCount
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
     */
    public function assertLockedEntityCount(int $expectedLockedEntityCount): void
    {
        $lockedEntityCount = SpyOmsStateMachineLockQuery::create()->count();

        if ($expectedLockedEntityCount !== $lockedEntityCount) {
            throw new ExpectationFailedException(sprintf('Expected to have "%s" locked entries but found "%s"', $expectedLockedEntityCount, $lockedEntityCount));
        }
    }
}
