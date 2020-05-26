<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\OmsProductReservationTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock;
use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oms\Business\OmsBusinessFactory;
use Spryker\Zed\Oms\Business\OmsFacade;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\OmsDependencyProvider;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use SprykerTest\Zed\Oms\Business\OrderStateMachine\Plugin\Fixtures\TestAuthPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group Facade
 * @group OmsFacadeTest
 * Add your own group annotations below this line
 */
class OmsFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->resetReservedStatesCache();
        $this->tester->configureTestStateMachine(['Test01']);
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $this->tester->resetReservedStatesCache();
    }

    /**
     * @return void
     */
    public function testClearLocksShouldEmptyDatabaseFromExpiredLocks(): void
    {
        $identifier = '1-2-3';

        $omsFacade = $this->createOmsFacade();

        $omsLockEntity = new SpyOmsStateMachineLock();
        $omsLockEntity->setIdentifier($identifier);
        $omsLockEntity->setExpires(new DateTime('Yesterday'));
        $omsLockEntity->save();

        $omsFacade->clearLocks();

        $numberOfItems = SpyOmsStateMachineLockQuery::create()->filterByIdentifier($identifier)->count();

        $this->assertSame(0, $numberOfItems);
    }

    /**
     * @return void
     */
    public function testOrderMatrixCreation(): void
    {
        $omsFacade = $this->createOmsFacade();

        $matrix = $omsFacade->getOrderItemMatrix();

        $this->assertNotEmpty($matrix);
        $this->assertSame('', $matrix[0]['COL_STATE']);
    }

    /**
     * @return void
     */
    public function testIsOrderFlaggedExcludeFromCustomerShouldReturnTrueWhenAllStatesHaveFlag(): void
    {
        $testStateMachineProcessName = 'Test01';

        $omsFacade = $this->createOmsFacadeWithTestStateMachine([$testStateMachineProcessName]);

        $saveOrderTransfer = $this->tester->haveOrder([
            'unitPrice' => 100,
            'sumPrice' => 100,
        ], $testStateMachineProcessName);

        $idSalesOrder = $saveOrderTransfer->getIdSalesOrder();

        $salesOrderEntity = SpySalesOrderQuery::create()->filterByIdSalesOrder($idSalesOrder)->findOne();

        $omsFacade->triggerEvent('authorization-failed', $salesOrderEntity->getItems(), []);

        $isOrderExcluded = $omsFacade->isOrderFlaggedExcludeFromCustomer($idSalesOrder);

        $this->assertTrue($isOrderExcluded);
    }

    /**
     * @return void
     */
    public function testIsOrderFlaggedExcludeFromCustomerShouldReturnFalseWhenAnyOfStatesMissingFlag(): void
    {
        $testStateMachineProcessName = 'Test01';

        $omsFacade = $this->createOmsFacadeWithTestStateMachine([$testStateMachineProcessName]);

        $saveOrderTransfer = $this->tester->haveOrder([
            'unitPrice' => 100,
            'sumPrice' => 100,
        ], $testStateMachineProcessName);

        $idSalesOrder = $saveOrderTransfer->getIdSalesOrder();

        $isOrderExcluded = $omsFacade->isOrderFlaggedExcludeFromCustomer($idSalesOrder);

        $this->assertFalse($isOrderExcluded);
    }

    /**
     * @return void
     */
    public function testGetReservedStateNames(): void
    {
        $expected = [
            'new',
            'payment pending',
            'paid',
            'exported',
            'shipped',
        ];

        // Action
        $stateNames = array_keys($this->createOmsFacade()->getOmsReservedStateCollection()->getStates()->getArrayCopy());

        // Assert
        $this->assertSame($expected, $stateNames);
    }

    /**
     * @return void
     */
    public function testSaveReservation(): void
    {
        $storeTransfer = (new StoreTransfer())->setIdStore(1)->setName('DE');
        $productSku = 'xxx';
        $reservationQuantity = new Decimal(10);

        // Action
        $this->createOmsFacade()->saveReservation($productSku, $storeTransfer, $reservationQuantity);

        // Assert
        $this->assertTrue(
            $this->createOmsFacade()
                ->getOmsReservedProductQuantityForSku($productSku, $storeTransfer)
                ->equals($reservationQuantity)
        );
    }

    /**
     * @return void
     */
    public function testTriggerEventWillNotThrowAnExceptionWhenExceptionWasThrownDuringOrderItemHandling(): void
    {
        //Arrange
        $testStateMachineProcessName = 'Test04';
        $this->tester->configureTestStateMachine([$testStateMachineProcessName]);

        $saveOrderTransfer1 = $this->tester->haveOrder([
            'unitPrice' => 100,
            'sumPrice' => 100,
        ], $testStateMachineProcessName);
        $saveOrderTransfer2 = $this->tester->haveOrder([
            'unitPrice' => 100,
            'sumPrice' => 100,
        ], $testStateMachineProcessName);

        $orderItems = SpySalesOrderItemQuery::create()
            ->filterByFkSalesOrder_In([
                $saveOrderTransfer1->getIdSalesOrder(),
                $saveOrderTransfer2->getIdSalesOrder(),
            ])
            ->orderByIdSalesOrderItem(Criteria::ASC)
            ->find();

        $omsFacade = $this->createOmsFacadeWithErroredTestStateMachine([$testStateMachineProcessName]);

        //Act
        $omsFacade->triggerEvent('authorize', clone $orderItems, []);

        //Assert
        $processedOrderItems = SpySalesOrderItemQuery::create()
            ->filterByFkSalesOrder_In([
                $saveOrderTransfer1->getIdSalesOrder(),
                $saveOrderTransfer2->getIdSalesOrder(),
            ])
            ->orderByIdSalesOrderItem(Criteria::ASC)
            ->find();

        $this->assertEquals(
            $orderItems->offsetGet(0)->getFkOmsOrderItemState(),
            $processedOrderItems->offsetGet(0)->getFkOmsOrderItemState(),
            'Order item state ID does not equal to an expected value.'
        );
        $this->assertNotEquals(
            $orderItems->offsetGet(1)->getFkOmsOrderItemState(),
            $processedOrderItems->offsetGet(1)->getFkOmsOrderItemState(),
            'Order item state ID does not equal to an expected value.'
        );
    }

    /**
     * @return void
     */
    public function testExpandOrderWithOmsStatesReturnsUpdatedTransferWithCorrectData(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder())->withState()->build();
        $orderTransfer = (new OrderTransfer())->addItem($itemTransfer);

        // Act
        $expandedOrderTransfer = $this->createOmsFacade()
            ->expandOrderWithOmsStates($orderTransfer);

        // Assert
        $this->assertSame([$itemTransfer->getState()->getName()], $expandedOrderTransfer->getItemStates());
    }

    /**
     * @return void
     */
    public function testExpandOrderWithOmsStatesDoesNothingWithIncorrectData(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder())->build();
        $orderTransfer = (new OrderTransfer())->addItem($itemTransfer);

        // Act
        $expandedOrderTransfer = $this->createOmsFacade()
            ->expandOrderWithOmsStates($orderTransfer);

        // Assert
        $this->assertEmpty($expandedOrderTransfer->getItemStates());
    }

    /**
     * @return void
     */
    public function testUpdateReservationUpdatesExistingReservationQuantity(): void
    {
        // Arrange
        $omsProductReservationTransfer = $this->tester->haveOmsProductReservation([
            OmsProductReservationTransfer::SKU => 'test',
            OmsProductReservationTransfer::FK_STORE => 1,
            OmsProductReservationTransfer::RESERVATION_QUANTITY => 5,
        ]);

        $storeTransfer = (new StoreTransfer())->setIdStore($omsProductReservationTransfer->getFkStore());
        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setSku($omsProductReservationTransfer->getSku())
            ->setStore($storeTransfer)
            ->setReservationQuantity(5);

        // Act
        $this->createOmsFacade()->updateReservation($reservationRequestTransfer);

        // Assert
        $reservationResponseTransfer = $this->createOmsFacade()->getOmsReservedProductQuantity($reservationRequestTransfer);
        $this->assertTrue($reservationResponseTransfer->getReservationQuantity()->isZero());
    }

    /**
     * @return void
     */
    public function testCreateProductReservationForDifferentStores(): void
    {
        // Arrange
        $omsProductReservationTransfer1 = $this->tester->haveOmsProductReservation([
            OmsProductReservationTransfer::SKU => 'test',
            OmsProductReservationTransfer::FK_STORE => 1,
            OmsProductReservationTransfer::RESERVATION_QUANTITY => 5,
        ]);

        $omsProductReservationTransfer2 = $this->tester->haveOmsProductReservation([
            OmsProductReservationTransfer::SKU => 'test',
            OmsProductReservationTransfer::FK_STORE => 2,
            OmsProductReservationTransfer::RESERVATION_QUANTITY => 3,
        ]);

        $storeTransfer1 = (new StoreTransfer())->setIdStore($omsProductReservationTransfer1->getFkStore());
        $storeTransfer2 = (new StoreTransfer())->setIdStore($omsProductReservationTransfer2->getFkStore());

        $reservationRequestTransfer1 = (new ReservationRequestTransfer())
            ->setSku($omsProductReservationTransfer1->getSku())
            ->setStore($storeTransfer1);

        $reservationRequestTransfer2 = (new ReservationRequestTransfer())
            ->setSku($omsProductReservationTransfer2->getSku())
            ->setStore($storeTransfer2);

        // Act
        $reservationResponseTransfer1 = $this->createOmsFacade()->getOmsReservedProductQuantity($reservationRequestTransfer1);
        $reservationResponseTransfer2 = $this->createOmsFacade()->getOmsReservedProductQuantity($reservationRequestTransfer2);

        // Assert
        $this->assertSame(5, $reservationResponseTransfer1->getReservationQuantity()->toInt());
        $this->assertSame(3, $reservationResponseTransfer2->getReservationQuantity()->toInt());
    }

    /**
     * @return void
     */
    public function testGetOmsReservedProductQuantityForProduct(): void
    {
        // Arrange
        $omsProductReservationTransfer = $this->tester->haveOmsProductReservation([
            OmsProductReservationTransfer::SKU => 'test',
            OmsProductReservationTransfer::FK_STORE => 1,
            OmsProductReservationTransfer::RESERVATION_QUANTITY => 5,
        ]);

        $storeTransfer = (new StoreTransfer())->setIdStore($omsProductReservationTransfer->getFkStore());
        $reservationRequestTransfer = (new ReservationRequestTransfer())
            ->setSku($omsProductReservationTransfer->getSku())
            ->setStore($storeTransfer);

        // Act
        $reservationResponseTransfer = $this->createOmsFacade()->getOmsReservedProductQuantity($reservationRequestTransfer);

        // Assert
        $this->assertSame(
            $omsProductReservationTransfer->getReservationQuantity()->toInt(),
            $reservationResponseTransfer->getReservationQuantity()->toInt()
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected function createOmsFacade(): OmsFacadeInterface
    {
        $omsBusinessFactory = new OmsBusinessFactory();
        $omsConfig = new OmsConfig();
        $omsBusinessFactory->setConfig($omsConfig);

        $omsFacade = new OmsFacade();
        $omsFacade->setFactory($omsBusinessFactory);

        return $omsFacade;
    }

    /**
     * @param array $activeProcesses
     * @param string|null $xmlFolder
     *
     * @return \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected function createOmsFacadeWithTestStateMachine(array $activeProcesses = [], ?string $xmlFolder = null): OmsFacadeInterface
    {
        $this->tester->configureTestStateMachine($activeProcesses, $xmlFolder);

        return new OmsFacade();
    }

    /**
     * @param array $activeProcesses
     * @param string|null $xmlFolder
     *
     * @return \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected function createOmsFacadeWithErroredTestStateMachine(array $activeProcesses = [], ?string $xmlFolder = null): OmsFacadeInterface
    {
        $this->tester->configureTestStateMachine($activeProcesses);

        $omsDependencyProvider = new OmsDependencyProvider();
        $container = new Container();
        $container = $omsDependencyProvider->provideBusinessLayerDependencies($container);
        $container = $omsDependencyProvider->providePersistenceLayerDependencies($container);
        $container->extend(OmsDependencyProvider::COMMAND_PLUGINS, function (CommandCollectionInterface $commandCollection) {
            return $commandCollection->add(new TestAuthPlugin(), 'TestPayment/Authorize');
        });

        $omsBusinessFactory = new OmsBusinessFactory();
        $omsBusinessFactory->setContainer($container);

        $omsFacade = new OmsFacade();
        $omsFacade->setFactory($omsBusinessFactory);

        return $omsFacade;
    }
}
