<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesMerchantCommission\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantCommissionCalculationItemTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationTotalsTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToMerchantCommissionFacadeInterface;
use Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToSalesFacadeBridge;
use Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToSalesFacadeInterface;
use SprykerTest\Zed\SalesMerchantCommission\SalesMerchantCommissionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesMerchantCommission
 * @group Business
 * @group Facade
 * @group CreateSalesMerchantCommissionsTest
 * Add your own group annotations below this line
 */
class CreateSalesMerchantCommissionsTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var int
     */
    protected const FAKE_ID_SALES_ORDER = 666;

    /**
     * @var string
     */
    protected const FAKE_COMMISSION_NAME = 'FAKE_COMMISSION_NAME';

    /**
     * @var int
     */
    protected const FAKE_COMMISSION_AMOUNT = 123;

    /**
     * @var \SprykerTest\Zed\SalesMerchantCommission\SalesMerchantCommissionBusinessTester
     */
    protected SalesMerchantCommissionBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureSalesMerchantCommissionDatabaseTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testShouldNotCreateSalesMerchantCommissionsForNotPersistedOrder(): void
    {
        // Arrange
        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder(static::FAKE_ID_SALES_ORDER);

        // Act
        $this->tester->getFacade()->createSalesMerchantCommissions($orderTransfer);

        // Assert
        $this->assertCount(0, $this->tester->getSalesMerchantCommissions());
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionForOrderWithEmptyIdSalesOrder(): void
    {
        // Arrange
        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder(null);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->createSalesMerchantCommissions($orderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionForOrderWithEmptyPriceMode(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithItem();
        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->createSalesMerchantCommissions($orderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldPersistSalesMerchantCommission(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithItem(true);
        $idSalesOrder = $saveOrderTransfer->getIdSalesOrderOrFail();
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItemOrFail();

        $this->tester->mockFactoryMethod(
            'getMerchantCommissionFacade',
            $this->createMerchantCommissionFacadeMock($this->buildFakeCommissionCalculationResponse($idSalesOrder, $idSalesOrderItem)),
        );

        // Act
        $this->tester->getFacade()->createSalesMerchantCommissions((new OrderTransfer())->setIdSalesOrder($idSalesOrder));

        // Assert
        $this->assertCount(1, $this->tester->getSalesMerchantCommissions());
        $this->assertSalesMerchantCommission($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * @return void
     */
    public function testShouldUpdateOrderTotals(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithItem(true);
        $idSalesOrder = $saveOrderTransfer->getIdSalesOrder();
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItem();

        $this->tester->mockFactoryMethod(
            'getMerchantCommissionFacade',
            $this->createMerchantCommissionFacadeMock($this->buildFakeCommissionCalculationResponse($idSalesOrder, $idSalesOrderItem)),
        );

        // Act
        $this->tester->getFacade()->createSalesMerchantCommissions((new OrderTransfer())->setIdSalesOrder($idSalesOrder));

        // Assert
        $this->assertSame(
            static::FAKE_COMMISSION_AMOUNT,
            $this->tester->getSalesOrderTotalByIdSalesOrder($idSalesOrder)->getMerchantCommissionTotal(),
        );
    }

    /**
     * @group test
     *
     * @return void
     */
    public function testShouldUpdateOrderItems(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithItem(true);
        $idSalesOrder = $saveOrderTransfer->getIdSalesOrder();
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItem();

        $this->tester->mockFactoryMethod(
            'getMerchantCommissionFacade',
            $this->createMerchantCommissionFacadeMock($this->buildFakeCommissionCalculationResponse($idSalesOrder, $idSalesOrderItem)),
        );
        $this->tester->mockFactoryMethod('getSalesFacade', $this->createSalesFacadeMock());

        // Act
        $this->tester->getFacade()->createSalesMerchantCommissions((new OrderTransfer())->setIdSalesOrder($idSalesOrder));

        // Assert
        $salesOrderItemEntity = $this->tester->getSalesOrderItemByIdSalesOrderItem($idSalesOrderItem);
        $this->assertSame(static::FAKE_COMMISSION_AMOUNT, $salesOrderItemEntity->getMerchantCommissionAmountAggregation());
        $this->assertSame(static::FAKE_COMMISSION_AMOUNT, $salesOrderItemEntity->getMerchantCommissionAmountFullAggregation());
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return void
     */
    protected function assertSalesMerchantCommission(int $idSalesOrder, int $idSalesOrderItem): void
    {
        /** @var \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission $salesMerchantCommissionEntity */
        $salesMerchantCommissionEntity = $this->tester->getSalesMerchantCommissions()->getIterator()->current();

        $this->assertSame($idSalesOrder, $salesMerchantCommissionEntity->getFkSalesOrder());
        $this->assertSame($idSalesOrderItem, $salesMerchantCommissionEntity->getFkSalesOrderItem());
        $this->assertSame(static::FAKE_COMMISSION_NAME, $salesMerchantCommissionEntity->getName());
        $this->assertSame(static::FAKE_COMMISSION_AMOUNT, $salesMerchantCommissionEntity->getAmount());

        $this->assertNotEmpty($salesMerchantCommissionEntity->getIdSalesMerchantCommission());
        $this->assertNotEmpty($salesMerchantCommissionEntity->getUuid());
        $this->assertNotEmpty($salesMerchantCommissionEntity->getCreatedAt());
        $this->assertNotEmpty($salesMerchantCommissionEntity->getUpdatedAt());
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer
     */
    protected function buildFakeCommissionCalculationResponse(
        int $idSalesOrder,
        int $idSalesOrderItem
    ): MerchantCommissionCalculationResponseTransfer {
        $merchantCommissionTransfer = (new MerchantCommissionTransfer())
            ->setAmount(static::FAKE_COMMISSION_AMOUNT)
            ->setName(static::FAKE_COMMISSION_NAME);

        return (new MerchantCommissionCalculationResponseTransfer())
            ->setTotals(
                (new MerchantCommissionCalculationTotalsTransfer())
                    ->setIdSalesOrder($idSalesOrder)
                    ->setMerchantCommissionTotal(static::FAKE_COMMISSION_AMOUNT),
            )
            ->addItem(
                (new MerchantCommissionCalculationItemTransfer())
                    ->setIdSalesOrder($idSalesOrder)
                    ->setIdSalesOrderItem($idSalesOrderItem)
                    ->setMerchantCommissionAmountAggregation(static::FAKE_COMMISSION_AMOUNT)
                    ->setMerchantCommissionAmountFullAggregation(static::FAKE_COMMISSION_AMOUNT)
                    ->addMerchantCommission($merchantCommissionTransfer),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer $merchantCommissionCalculationResponseTransfer
     *
     * @return \Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToMerchantCommissionFacadeInterface
     */
    protected function createMerchantCommissionFacadeMock(
        MerchantCommissionCalculationResponseTransfer $merchantCommissionCalculationResponseTransfer
    ): SalesMerchantCommissionToMerchantCommissionFacadeInterface {
        $merchantCommissionFacadeMock = $this->getMockBuilder(SalesMerchantCommissionToMerchantCommissionFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $merchantCommissionFacadeMock
            ->method('calculateMerchantCommission')
            ->willReturn($merchantCommissionCalculationResponseTransfer);

        return $merchantCommissionFacadeMock;
    }

    /**
     * @return \Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToSalesFacadeInterface
     */
    protected function createSalesFacadeMock(): SalesMerchantCommissionToSalesFacadeInterface
    {
        $salesFacadeMock = $this->getMockBuilder(SalesMerchantCommissionToSalesFacadeBridge::class)
            ->enableProxyingToOriginalMethods()
            ->setConstructorArgs([$this->tester->getLocator()->sales()->facade()])
            ->getMock();

        $salesFacadeMock->expects($this->once())->method('updateOrder')
            ->willReturnCallback(function (OrderTransfer $orderTransfer, int $idSalesOrder) {
                foreach ($orderTransfer->getItems() as $itemTransfer) {
                    $salesOrderItemEntity = $this->tester->getSalesOrderItemByIdSalesOrderItem($itemTransfer->getIdSalesOrderItemOrFail());
                    $salesOrderItemEntity->setMerchantCommissionAmountAggregation($itemTransfer->getMerchantCommissionAmountAggregationOrFail());
                    $salesOrderItemEntity->setMerchantCommissionAmountFullAggregation($itemTransfer->getMerchantCommissionAmountFullAggregationOrFail());

                    $salesOrderItemEntity->save();
                }

                return true;
            });

        return $salesFacadeMock;
    }
}
