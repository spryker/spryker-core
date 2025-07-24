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
use Generated\Shared\DataBuilder\ItemMetadataBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OmsEventTriggeredTransfer;
use Generated\Shared\Transfer\OrderFilterTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Oms\Persistence\Map\SpyOmsStateMachineLockTableMap;
use Orm\Zed\Oms\Persistence\SpyOmsEventTimeoutQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistoryQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery;
use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery;
use Orm\Zed\Oms\Persistence\SpyOmsTransitionLog;
use Orm\Zed\Oms\Persistence\SpyOmsTransitionLogQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use PHPUnit\Framework\ExpectationFailedException;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Propel;
use ReflectionClass;
use Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\ValidationMiddlewarePlugin;
use Spryker\Zed\MessageBroker\MessageBrokerDependencyProvider;
use Spryker\Zed\Oms\Business\Lock\LockerInterface;
use Spryker\Zed\Oms\Business\Lock\TriggerLocker;
use Spryker\Zed\Oms\Business\OrderStateMachine\Builder;
use Spryker\Zed\Oms\Business\OrderStateMachine\LockedOrderStateMachine;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface;
use Spryker\Zed\Oms\Business\Reader\ProcessCacheReader;
use Spryker\Zed\Oms\Business\Reader\ProcessCacheReaderInterface;
use Spryker\Zed\Oms\Business\Util\ActiveProcessFetcher;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Business\Writer\ProcessCacheWriter;
use Spryker\Zed\Oms\Business\Writer\ProcessCacheWriterInterface;
use Spryker\Zed\Oms\Dependency\Facade\OmsToSalesInterface;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\Persistence\OmsQueryContainer;
use Spryker\Zed\OmsExtension\Dependency\Plugin\OmsEventTriggeredListenerPluginInterface;
use Spryker\Zed\SalesPayment\SalesPaymentDependencyProvider;
use Spryker\Zed\Store\Communication\Plugin\MessageBroker\CurrentStoreReferenceMessageAttributeProviderPlugin;
use Spryker\Zed\Store\Communication\Plugin\MessageBroker\StoreReferenceMessageValidatorPlugin;

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
     * @var string
     */
    public const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var string
     */
    public const FAKE_BILLING_ADDRESS = 'TestAddress 1';

    /**
     * @var string
     */
    public const FAKE_SKU = 'FAKE_SKU';

    /**
     * @var string
     */
    protected const LOCKED_ENTITY_IDENTIFIER = '1';

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
            $this->haveStore([StoreTransfer::NAME => 'DE']),
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
                ['state' => $stateName, 'process' => $processName],
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
        $omsConfig = new OmsConfig();
        $activeProcessList = new ReadOnlyArrayObject($omsConfig->getActiveProcesses());

        return new LockedOrderStateMachine(
            $orderStatemachineMock,
            $triggerLocker,
            Stub::make(Builder::class),
            $activeProcessList,
            new OmsQueryContainer(),
            $omsConfig,
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface
     */
    public function createLockedOrderStatemachineWithTriggerException(): OrderStateMachineInterface
    {
        $triggerLocker = $this->createTriggerLocker();

        $orderStateMachineMock = $this->getOrderStatemachineMockForFailedTriggeredEvents();

        $omsConfig = new OmsConfig();
        $activeProcessList = new ReadOnlyArrayObject($omsConfig->getActiveProcesses());

        return new LockedOrderStateMachine(
            $orderStateMachineMock,
            $triggerLocker,
            Stub::make(Builder::class),
            $activeProcessList,
            new OmsQueryContainer(),
            $omsConfig,
        );
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

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTransferForOrderStatusChanged(): OrderTransfer
    {
        $orderTransfer = $this->createOrderByStateMachineProcessName('Test01');
        $orderTransfer->setCreatedAt(date('Y-m-d h:i:s'));
        $orderTransfer->setEmail($orderTransfer->getCustomer()->getEmail());

        $itemMetadataTransfer = (new ItemMetadataBuilder())->build();
        $itemMetadataTransfer->setImage('https://image.url');

        foreach ($orderTransfer->getItems() as $item) {
            $item->setMetadata($itemMetadataTransfer);
            $item->setSku('some_sku');
        }

        return $orderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTransferAndSetupSalesFacadeMock(): OrderTransfer
    {
        $salesFacadeMock = Stub::makeEmpty(OmsToSalesInterface::class);

        $this->setDependency(SalesPaymentDependencyProvider::FACADE_SALES, $salesFacadeMock);

        $orderTransfer = $this->getOrderTransferForOrderStatusChanged();

        $salesFacadeMock->method('getOrderByIdSalesOrder')->willReturn($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @return void
     */
    public function setupMessageBroker(): void
    {
        $this->setDependency(MessageBrokerDependencyProvider::PLUGINS_MIDDLEWARE, [new ValidationMiddlewarePlugin()]);

        $this->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_ATTRIBUTE_PROVIDER, [new CurrentStoreReferenceMessageAttributeProviderPlugin()]);

        $this->setDependency(MessageBrokerDependencyProvider::PLUGINS_EXTERNAL_VALIDATOR, [new StoreReferenceMessageValidatorPlugin()]);
    }

    /**
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsEventTriggeredListenerPluginInterface
     */
    public function setupEventTriggeredListenerPluginDependency(): OmsEventTriggeredListenerPluginInterface
    {
        $omsEventTriggeredListener = new class implements OmsEventTriggeredListenerPluginInterface {
            /**
             * @var bool
             */
            public $wasTriggered;

            /**
             * @param \Generated\Shared\Transfer\OmsEventTriggeredTransfer $omsEventTriggeredTransfer
             *
             * @return void
             */
            public function onEventTriggered(OmsEventTriggeredTransfer $omsEventTriggeredTransfer): void
            {
                $this->wasTriggered = true;
            }

            /**
             * @param \Generated\Shared\Transfer\OmsEventTriggeredTransfer $omsEventTriggeredTransfer
             *
             * @return bool
             */
            public function isApplicable(OmsEventTriggeredTransfer $omsEventTriggeredTransfer): bool
            {
                return true;
            }
        };

        $this->setDependency('PLUGINS_OMS_EVENT_TRIGGERED_LISTENER', [$omsEventTriggeredListener]);

        return $omsEventTriggeredListener;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderByIdSalesOrder(int $idSalesOrder): OrderTransfer
    {
        return $this->getLocator()->sales()->facade()
            ->getOrder(
                (new OrderFilterTransfer())->setSalesOrderId($idSalesOrder),
            );
    }

    /**
     * @param int $identifier
     *
     * @return void
     */
    public function insertOmsStateMachineLockByIdUsingRawQuery(int $identifier): void
    {
        $query = sprintf(
            "INSERT INTO %s (id_oms_state_machine_lock, identifier, expires) VALUES (%d, '%s', '%s')",
            SpyOmsStateMachineLockTableMap::TABLE_NAME,
            $identifier,
            static::LOCKED_ENTITY_IDENTIFIER,
            '2025-01-01 00:00:00',
        );

        $connection = Propel::getConnection();
        $connection->query($query);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Reader\ProcessCacheReaderInterface
     */
    public function createProcessCacheReader(): ProcessCacheReaderInterface
    {
        return new ProcessCacheReader(new OmsConfig());
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Writer\ProcessCacheWriterInterface
     */
    public function createProcessCacheWriter(): ProcessCacheWriterInterface
    {
        return new ProcessCacheWriter(new OmsConfig(), $this->createProcessCacheReader());
    }

    /**
     * @return void
     */
    public function resetProcessBuffer(): void
    {
        $reflection = new ReflectionClass(Builder::class);
        $property = $reflection->getProperty('processBuffer');
        $property->setAccessible(true);
        $property->setValue(null, []);
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsTransitionLog
     */
    public function createOmsTransitionLog(int $idSalesOrder, int $idSalesOrderItem): SpyOmsTransitionLog
    {
        $omsOrderProcessEntity = $this->createOmsOrderProcess(static::DEFAULT_OMS_PROCESS_NAME);
        $omsTransitionLogEntity = (new SpyOmsTransitionLog())
            ->setFkOmsOrderProcess($omsOrderProcessEntity->getIdOmsOrderProcess())
            ->setFkSalesOrder($idSalesOrder)
            ->setFkSalesOrderItem($idSalesOrderItem)
            ->setHostname('test')
            ->setEvent('test')
            ->setCommand('test')
            ->setCondition('test');
        $omsTransitionLogEntity->save();

        return $omsTransitionLogEntity;
    }

    /**
     * @param int $idOmsOrderItemState
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory
     */
    public function createOmsOrderItemStateHistory(
        int $idOmsOrderItemState,
        int $idSalesOrderItem
    ): SpyOmsOrderItemStateHistory {
        $omsOrderItemStateHistoryEntity = (new SpyOmsOrderItemStateHistory())
            ->setFkOmsOrderItemState($idOmsOrderItemState)
            ->setFkSalesOrderItem($idSalesOrderItem);
        $omsOrderItemStateHistoryEntity->save();

        return $omsOrderItemStateHistoryEntity;
    }

    /**
     * @param string $processName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    public function createOmsOrderProcess(string $processName): SpyOmsOrderProcess
    {
        $orderProcessEntity = (new SpyOmsOrderProcessQuery())
            ->filterByName($processName)
            ->findOneOrCreate();

        $orderProcessEntity->save();

        return $orderProcessEntity;
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Oms\Persistence\SpyOmsEventTimeout>
     */
    public function getOmsEventTimeoutEntities(): ObjectCollection
    {
        return $this->getOmsEventTimeoutQuery()->find();
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory>
     */
    public function getOmsOrderItemStateHistoryEntities(): ObjectCollection
    {
        return $this->getOmsOrderItemStateHistoryQuery()->find();
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Oms\Persistence\SpyOmsTransitionLog>
     */
    public function getOmsTransitionLogEntities(): ObjectCollection
    {
        return $this->getOmsTransitionLogQuery()->find();
    }

    /**
     * @return void
     */
    public function ensureOmsEventTimeoutTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getOmsEventTimeoutQuery());
    }

    /**
     * @return void
     */
    public function ensureOmsOrderItemStateHistoryTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getOmsOrderItemStateHistoryQuery());
    }

    /**
     * @return void
     */
    public function ensureOmsTransitionLogTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getOmsTransitionLogQuery());
    }

    /**
     * @return void
     */
    public function ensureOmsOrderItemStateDatabaseTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty(
            $this->getOmsOrderItemStateQuery(),
        );
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsEventTimeoutQuery
     */
    protected function getOmsEventTimeoutQuery(): SpyOmsEventTimeoutQuery
    {
        return SpyOmsEventTimeoutQuery::create();
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistoryQuery
     */
    protected function getOmsOrderItemStateHistoryQuery(): SpyOmsOrderItemStateHistoryQuery
    {
        return SpyOmsOrderItemStateHistoryQuery::create();
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsTransitionLogQuery
     */
    protected function getOmsTransitionLogQuery(): SpyOmsTransitionLogQuery
    {
        return SpyOmsTransitionLogQuery::create();
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery
     */
    protected function getOmsOrderItemStateQuery(): SpyOmsOrderItemStateQuery
    {
        return SpyOmsOrderItemStateQuery::create();
    }
}
