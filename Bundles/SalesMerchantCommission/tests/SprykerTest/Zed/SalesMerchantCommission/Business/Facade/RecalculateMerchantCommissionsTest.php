<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesMerchantCommission\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesMerchantCommissionTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerTest\Zed\SalesMerchantCommission\SalesMerchantCommissionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesMerchantCommission
 * @group Business
 * @group Facade
 * @group RecalculateMerchantCommissionsTest
 * Add your own group annotations below this line
 */
class RecalculateMerchantCommissionsTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var int
     */
    protected const FAKE_COMMISSION_AMOUNT = 100;

    /**
     * @var int
     */
    protected const FAKE_COMMISSION_REFUNDED_AMOUNT = 50;

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
    public function testShouldRecalculateMerchantCommissionInOrderTotals(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithItem();
        $this->createDummySalesMerchantCommissions($saveOrderTransfer);

        $calculableObjectTransfer = (new CalculableObjectTransfer())
            ->setOriginalOrder((new OrderTransfer())->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder()))
            ->setTotals(new TotalsTransfer())
            ->addItem($saveOrderTransfer->getOrderItems()->offsetGet(0));

        // Act
        $calculableObjectTransfer = $this->tester->getFacade()->recalculateMerchantCommissions($calculableObjectTransfer);

        // Assert
        $totalsTransfer = $calculableObjectTransfer->getTotals();

        $this->assertSame(150, $totalsTransfer->getMerchantCommissionTotal());
        $this->assertSame(150, $totalsTransfer->getMerchantCommissionRefundedTotal());
    }

    /**
     * @return void
     */
    public function testShouldRecalculateMerchantCommissionInOrderItems(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithItem();
        $this->createDummySalesMerchantCommissions($saveOrderTransfer, 100, 0);

        $calculableObjectTransfer = (new CalculableObjectTransfer())
            ->setOriginalOrder((new OrderTransfer())->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder()))
            ->setTotals(new TotalsTransfer())
            ->addItem($saveOrderTransfer->getOrderItems()->offsetGet(0));

        // Act
        $calculableObjectTransfer = $this->tester->getFacade()->recalculateMerchantCommissions($calculableObjectTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $calculableObjectTransfer->getItems()->offsetGet(0);

        $this->assertSame(200, $itemTransfer->getMerchantCommissionAmountAggregation());
        $this->assertSame(200, $itemTransfer->getMerchantCommissionAmountFullAggregation());
        $this->assertSame(0, $itemTransfer->getMerchantCommissionRefundedAmount());
    }

    /**
     * @return void
     */
    public function testShouldNotRecalculateMerchantCommissionWhenOriginalOrderIsNull(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithItem();
        $this->createDummySalesMerchantCommissions($saveOrderTransfer, 100, 0);

        $calculableObjectTransfer = (new CalculableObjectTransfer())
            ->setOriginalOrder(null)
            ->setTotals(new TotalsTransfer())
            ->addItem($saveOrderTransfer->getOrderItems()->offsetGet(0));

        // Act
        $calculableObjectTransfer = $this->tester->getFacade()->recalculateMerchantCommissions($calculableObjectTransfer);

        // Assert
        $this->assertEmptyCommissionValues($calculableObjectTransfer);
    }

    /**
     * @return void
     */
    public function testShouldNotRecalculateMerchantCommissionWhenOriginalOrderIdIsNull(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithItem();
        $this->createDummySalesMerchantCommissions($saveOrderTransfer, 100, 0);

        $calculableObjectTransfer = (new CalculableObjectTransfer())
            ->setOriginalOrder((new OrderTransfer())->setIdSalesOrder(null))
            ->setTotals(new TotalsTransfer())
            ->addItem($saveOrderTransfer->getOrderItems()->offsetGet(0));

        // Act
        $calculableObjectTransfer = $this->tester->getFacade()->recalculateMerchantCommissions($calculableObjectTransfer);

        // Assert
        $this->assertEmptyCommissionValues($calculableObjectTransfer);
    }

    /**
     * @return void
     */
    public function testShouldNotRecalculateMerchantCommissionWhenSalesMerchantCommissionsAbsent(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithItem();

        $calculableObjectTransfer = (new CalculableObjectTransfer())
            ->setOriginalOrder((new OrderTransfer())->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder()))
            ->setTotals(new TotalsTransfer())
            ->addItem($saveOrderTransfer->getOrderItems()->offsetGet(0));

        // Act
        $calculableObjectTransfer = $this->tester->getFacade()->recalculateMerchantCommissions($calculableObjectTransfer);

        // Assert
        $this->assertEmptyCommissionValues($calculableObjectTransfer);
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
     * @param int|null $amount
     * @param int|null $refundedAmount
     *
     * @return list<\Generated\Shared\Transfer\SalesMerchantCommissionConditionsTransfer>
     */
    protected function createDummySalesMerchantCommissions(
        SaveOrderTransfer $saveOrderTransfer,
        ?int $amount = self::FAKE_COMMISSION_AMOUNT,
        ?int $refundedAmount = self::FAKE_COMMISSION_REFUNDED_AMOUNT
    ): array {
        $salesMerchantCommissions = [];
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItem();

        $salesMerchantCommissions[] = $this->tester->haveSalesMerchantCommission([
            SalesMerchantCommissionTransfer::AMOUNT => $amount,
            SalesMerchantCommissionTransfer::REFUNDED_AMOUNT => $refundedAmount,
            SalesMerchantCommissionTransfer::ID_SALES_ORDER => $saveOrderTransfer->getIdSalesOrder(),
            SalesMerchantCommissionTransfer::ID_SALES_ORDER_ITEM => $idSalesOrderItem,
        ]);

        $salesMerchantCommissions[] = $this->tester->haveSalesMerchantCommission([
            SalesMerchantCommissionTransfer::AMOUNT => $amount,
            SalesMerchantCommissionTransfer::REFUNDED_AMOUNT => $refundedAmount,
            SalesMerchantCommissionTransfer::ID_SALES_ORDER => $saveOrderTransfer->getIdSalesOrder(),
            SalesMerchantCommissionTransfer::ID_SALES_ORDER_ITEM => $idSalesOrderItem,
        ]);

        $salesMerchantCommissions[] = $this->tester->haveSalesMerchantCommission([
            SalesMerchantCommissionTransfer::AMOUNT => $amount,
            SalesMerchantCommissionTransfer::REFUNDED_AMOUNT => $refundedAmount,
            SalesMerchantCommissionTransfer::ID_SALES_ORDER => $saveOrderTransfer->getIdSalesOrder(),
        ]);

        return $salesMerchantCommissions;
    }
}
