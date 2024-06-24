<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesMerchantCommission\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesMerchantCommissionTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToCalculationFacadeInterface;
use Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToSalesFacadeBridge;
use Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToSalesFacadeInterface;
use Spryker\Zed\SalesMerchantCommission\SalesMerchantCommissionDependencyProvider;
use Spryker\Zed\SalesMerchantCommissionExtension\Dependency\Plugin\PostRefundMerchantCommissionPluginInterface;
use SprykerTest\Zed\SalesMerchantCommission\SalesMerchantCommissionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesMerchantCommission
 * @group Business
 * @group Facade
 * @group RefundMerchantCommissionsTest
 * Add your own group annotations below this line
 */
class RefundMerchantCommissionsTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var int
     */
    protected const FAKE_AMOUNT = 100;

    /**
     * @var int
     */
    protected const FAKE_ID = 1234;

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
    public function testShouldRefundMerchantCommissions(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithTwoItems();
        $this->createDummySalesMerchantCommissions($saveOrderTransfer);

        $this->tester->mockFactoryMethod('getCalculationFacade', $this->createCalculationFacadeMock());

        // Act
        $this->tester->getFacade()->refundMerchantCommissions(
            (new OrderTransfer())->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder()),
            $saveOrderTransfer->getOrderItems()->getArrayCopy(),
        );

        // Assert
        /** @var list<\Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission> $salesMerchantCommissionEntities */
        $salesMerchantCommissionEntities = $this->tester->getSalesMerchantCommissions();

        foreach ($salesMerchantCommissionEntities as $salesMerchantCommissionEntity) {
            $this->assertSame($salesMerchantCommissionEntity->getAmount(), $salesMerchantCommissionEntity->getRefundedAmount());
        }
    }

    /**
     * @return void
     */
    public function testShouldRefundMerchantCommissionsForOneOrderItem(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithTwoItems();
        $this->createDummySalesMerchantCommissions($saveOrderTransfer);
        $this->tester->mockFactoryMethod('getCalculationFacade', $this->createCalculationFacadeMock());

        // Act
        $this->tester->getFacade()->refundMerchantCommissions(
            (new OrderTransfer())->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder()),
            [$saveOrderTransfer->getOrderItems()->offsetGet(1)],
        );

        // Assert
        $this->assertPartialRefundCommissionAmounts($saveOrderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldUpdateOrderItemCommissionAmounts(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithTwoItems();
        $this->createDummySalesMerchantCommissions($saveOrderTransfer);

        $this->tester->mockFactoryMethod(
            'getCalculationFacade',
            $this->createCalculationFacadeMock($this->createCallbackWithFakeOrderItemCommissions()),
        );
        $this->tester->mockFactoryMethod('getSalesFacade', $this->createSalesFacadeMock());

        $orderTransfer = (new OrderTransfer())
            ->setItems($saveOrderTransfer->getOrderItems())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $orderTransfer = $this->tester->getFacade()->refundMerchantCommissions(
            $orderTransfer,
            $saveOrderTransfer->getOrderItems()->getArrayCopy(),
        );

        // Assert
        /** @var \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity */
        foreach ($this->tester->getSalesOrderItemByIdSalesOrder($saveOrderTransfer->getIdSalesOrder()) as $key => $salesOrderItemEntity) {
            $itemTransfer = $orderTransfer->getItems()->offsetGet($key);
            $this->assertSame(static::FAKE_AMOUNT, $salesOrderItemEntity->getMerchantCommissionAmountAggregation());
            $this->assertSame($itemTransfer->getMerchantCommissionAmountAggregation(), $salesOrderItemEntity->getMerchantCommissionAmountAggregation());

            $this->assertSame(static::FAKE_AMOUNT, $salesOrderItemEntity->getMerchantCommissionAmountFullAggregation());
            $this->assertSame($itemTransfer->getMerchantCommissionAmountFullAggregation(), $salesOrderItemEntity->getMerchantCommissionAmountFullAggregation());

            $this->assertSame(static::FAKE_AMOUNT, $salesOrderItemEntity->getMerchantCommissionRefundedAmount());
            $this->assertSame($itemTransfer->getMerchantCommissionRefundedAmount(), $salesOrderItemEntity->getMerchantCommissionRefundedAmount());
        }
    }

    /**
     * @return void
     */
    public function testShouldUpdateOrderTotalsCommissionAmounts(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithTwoItems();
        $this->createDummySalesMerchantCommissions($saveOrderTransfer);

        $this->tester->mockFactoryMethod(
            'getCalculationFacade',
            $this->createCalculationFacadeMock($this->createCallbackWithFakeOrderTotalsCommissions()),
        );

        $orderTransfer = (new OrderTransfer())
            ->setItems($saveOrderTransfer->getOrderItems())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $orderTransfer = $this->tester->getFacade()->refundMerchantCommissions(
            $orderTransfer,
            $saveOrderTransfer->getOrderItems()->getArrayCopy(),
        );

        // Assert
        $totalsEntity = $this->tester->getSalesOrderTotalByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        $this->assertSame(static::FAKE_AMOUNT, $totalsEntity->getMerchantCommissionTotal());
        $this->assertSame($orderTransfer->getTotals()->getMerchantCommissionTotal(), $totalsEntity->getMerchantCommissionTotal());

        $this->assertSame(static::FAKE_AMOUNT, $totalsEntity->getMerchantCommissionRefundedTotal());
        $this->assertSame($orderTransfer->getTotals()->getMerchantCommissionRefundedTotal(), $totalsEntity->getMerchantCommissionRefundedTotal());
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionForRefundWithEmptyIdSalesOrderItem(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())->setIdOrderItem(null);
        $orderTransfer = (new OrderTransfer())->setIdSalesOrder(static::FAKE_ID);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->refundMerchantCommissions($orderTransfer, [$itemTransfer]);
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionForOrderWithEmptyIdSalesOrder(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())->setIdOrderItem(static::FAKE_ID);
        $orderTransfer = (new OrderTransfer())->setIdSalesOrder(null);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->refundMerchantCommissions($orderTransfer, [$itemTransfer]);
    }

    /**
     * @return void
     */
    public function testShouldExecutesPostRefundMerchantCommissionPlugins(): void
    {
        // Arrange
        $postRefundMerchantCommissionPluginMock = $this->getMockBuilder(PostRefundMerchantCommissionPluginInterface::class)->getMock();
        $this->tester->setDependency(
            SalesMerchantCommissionDependencyProvider::PLUGINS_POST_REFUND_MERCHANT_COMMISSION,
            [$postRefundMerchantCommissionPluginMock],
        );

        $saveOrderTransfer = $this->tester->createOrderWithTwoItems();
        $this->createDummySalesMerchantCommissions($saveOrderTransfer);

        $this->tester->mockFactoryMethod('getCalculationFacade', $this->createCalculationFacadeMock());

        // Assert
        $postRefundMerchantCommissionPluginMock
            ->expects($this->once())
            ->method('execute');

        // Act
        $this->tester->getFacade()->refundMerchantCommissions(
            (new OrderTransfer())->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder()),
            $saveOrderTransfer->getOrderItems()->getArrayCopy(),
        );
    }

    /**
     * @return callable
     */
    protected function createCallbackWithFakeOrderItemCommissions(): callable
    {
        return function (OrderTransfer $orderTransfer) {
            $itemTransfers = $orderTransfer->getItems();

            foreach ($itemTransfers as $itemTransfer) {
                $itemTransfer->setMerchantCommissionAmountAggregation(static::FAKE_AMOUNT);
                $itemTransfer->setMerchantCommissionAmountFullAggregation(static::FAKE_AMOUNT);
                $itemTransfer->setMerchantCommissionRefundedAmount(static::FAKE_AMOUNT);
            }

            return $orderTransfer->setItems($itemTransfers);
        };
    }

    /**
     * @return callable
     */
    protected function createCallbackWithFakeOrderTotalsCommissions(): callable
    {
        return function (OrderTransfer $orderTransfer) {
            $totalsTransfer = (new TotalsTransfer())
                ->setMerchantCommissionTotal(static::FAKE_AMOUNT)
                ->setMerchantCommissionRefundedTotal(static::FAKE_AMOUNT);

            return $orderTransfer->setTotals($totalsTransfer);
        };
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    protected function assertEmptyCommissionValues(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $totalsTransfer = $calculableObjectTransfer->getTotals();
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $calculableObjectTransfer->getItems()->offsetGet(0);

        $this->assertContains($totalsTransfer->getMerchantCommissionTotal(), [0, null]);
        $this->assertContains($totalsTransfer->getMerchantCommissionRefundedTotal(), [0, null]);
        $this->assertContains($itemTransfer->getMerchantCommissionAmountAggregation(), [0, null]);
        $this->assertContains($itemTransfer->getMerchantCommissionAmountFullAggregation(), [0, null]);
        $this->assertContains($itemTransfer->getMerchantCommissionRefundedAmount(), [0, null]);
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return list<\Generated\Shared\Transfer\SalesMerchantCommissionConditionsTransfer>
     */
    protected function createDummySalesMerchantCommissions(SaveOrderTransfer $saveOrderTransfer): array
    {
        $salesMerchantCommissions = [];
        $idSalesOrderItem1 = $saveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItem();
        $idSalesOrderItem2 = $saveOrderTransfer->getOrderItems()->offsetGet(1)->getIdSalesOrderItem();

        $salesMerchantCommissions[] = $this->tester->haveSalesMerchantCommission([
            SalesMerchantCommissionTransfer::REFUNDED_AMOUNT => 0,
            SalesMerchantCommissionTransfer::ID_SALES_ORDER => $saveOrderTransfer->getIdSalesOrder(),
            SalesMerchantCommissionTransfer::ID_SALES_ORDER_ITEM => $idSalesOrderItem1,
        ]);

        $salesMerchantCommissions[] = $this->tester->haveSalesMerchantCommission([
            SalesMerchantCommissionTransfer::REFUNDED_AMOUNT => 0,
            SalesMerchantCommissionTransfer::ID_SALES_ORDER => $saveOrderTransfer->getIdSalesOrder(),
            SalesMerchantCommissionTransfer::ID_SALES_ORDER_ITEM => $idSalesOrderItem1,
        ]);

        $salesMerchantCommissions[] = $this->tester->haveSalesMerchantCommission([
            SalesMerchantCommissionTransfer::REFUNDED_AMOUNT => 0,
            SalesMerchantCommissionTransfer::ID_SALES_ORDER => $saveOrderTransfer->getIdSalesOrder(),
            SalesMerchantCommissionTransfer::ID_SALES_ORDER_ITEM => $idSalesOrderItem2,
        ]);

        return $salesMerchantCommissions;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function assertPartialRefundCommissionAmounts(SaveOrderTransfer $saveOrderTransfer): void
    {
        /** @var list<\Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission> $salesMerchantCommissionEntities */
        $salesMerchantCommissionEntities = $this->tester->getSalesMerchantCommissionQuery()
            ->filterByFkSalesOrderItem($saveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItem())
            ->find();

        foreach ($salesMerchantCommissionEntities as $salesMerchantCommissionEntity) {
            $this->assertSame(0, $salesMerchantCommissionEntity->getRefundedAmount());
        }

        /** @var \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission $salesMerchantCommissionEntity */
        $salesMerchantCommissionEntity = $this->tester->getSalesMerchantCommissionQuery()
            ->filterByFkSalesOrderItem($saveOrderTransfer->getOrderItems()->offsetGet(1)->getIdSalesOrderItem())
            ->findOne();

        $this->assertSame($salesMerchantCommissionEntity->getAmount(), $salesMerchantCommissionEntity->getRefundedAmount());
    }

    /**
     * @param callable|null $callback
     *
     * @return \Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToCalculationFacadeInterface
     */
    protected function createCalculationFacadeMock(?callable $callback = null): SalesMerchantCommissionToCalculationFacadeInterface
    {
        $calculationFacadeMock = $this->getMockBuilder(SalesMerchantCommissionToCalculationFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $calculationFacadeMock
            ->method('recalculateOrder')
            ->willReturnCallback($callback ?? function (OrderTransfer $orderTransfer) {
                return $orderTransfer;
            });

        return $calculationFacadeMock;
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
                    $salesOrderItemEntity->setMerchantCommissionRefundedAmount($itemTransfer->getMerchantCommissionRefundedAmountOrFail());

                    $salesOrderItemEntity->save();
                }

                return true;
            });

        return $salesFacadeMock;
    }
}
