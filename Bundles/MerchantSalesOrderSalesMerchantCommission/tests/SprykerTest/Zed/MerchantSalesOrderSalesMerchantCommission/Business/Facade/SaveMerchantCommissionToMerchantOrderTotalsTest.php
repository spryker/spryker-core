<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesOrderSalesMerchantCommission\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\MerchantSalesOrderSalesMerchantCommission\MerchantSalesOrderSalesMerchantCommissionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantSalesOrderSalesMerchantCommission
 * @group Business
 * @group Facade
 * @group SaveMerchantCommissionToMerchantOrderTotalsTest
 * Add your own group annotations below this line
 */
class SaveMerchantCommissionToMerchantOrderTotalsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantSalesOrderSalesMerchantCommission\MerchantSalesOrderSalesMerchantCommissionBusinessTester
     */
    protected MerchantSalesOrderSalesMerchantCommissionBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenIdOrderIsNotSet(): void
    {
        // Arrange
        $merchantOrderTransfer = $this->tester->createMerchantOrderWithTwoItems();
        $merchantOrderTransfer = (new MerchantOrderTransfer())
            ->setIdOrder(null)
            ->setMerchantReference($merchantOrderTransfer->getMerchantReference())
            ->addMerchantOrderItem((new MerchantOrderItemTransfer())->setOrderItem(new ItemTransfer()));

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->saveMerchantCommissionToMerchantOrderTotals($merchantOrderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenMerchantReferenceIsNotSet(): void
    {
        // Arrange
        $merchantOrderTransfer = $this->tester->createMerchantOrderWithTwoItems();
        $merchantOrderTransfer = (new MerchantOrderTransfer())
            ->setIdOrder($merchantOrderTransfer->getOrder())
            ->setMerchantReference(null)
            ->addMerchantOrderItem((new MerchantOrderItemTransfer())->setOrderItem(new ItemTransfer()));

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->saveMerchantCommissionToMerchantOrderTotals($merchantOrderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenSalesOrderItemIsNotSet(): void
    {
        // Arrange
        $merchantOrderTransfer = $this->tester->createMerchantOrderWithTwoItems();
        $merchantOrderTransfer = (new MerchantOrderTransfer())
            ->setIdOrder($merchantOrderTransfer->getOrder())
            ->setMerchantReference($merchantOrderTransfer->getMerchantReference())
            ->addMerchantOrderItem((new MerchantOrderItemTransfer())->setOrderItem(null));

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->saveMerchantCommissionToMerchantOrderTotals($merchantOrderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenIdMerchantOrderIsNotSet(): void
    {
        // Arrange
        $merchantOrderTransfer = $this->tester->createMerchantOrderWithTwoItems();
        $merchantOrderTransfer = (new MerchantOrderTransfer())
            ->setIdMerchantOrder(null)
            ->setIdOrder($merchantOrderTransfer->getIdOrderOrFail())
            ->setMerchantReference($merchantOrderTransfer->getMerchantReference())
            ->addMerchantOrderItem((new MerchantOrderItemTransfer())->setOrderItem(new ItemTransfer()));

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->saveMerchantCommissionToMerchantOrderTotals($merchantOrderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldUpdateMerchantOrderTotals(): void
    {
        // Arrange
        $merchantOrderTransfer = $this->tester->createMerchantOrderWithTwoItems();

        // Act
        $this->tester->getFacade()->saveMerchantCommissionToMerchantOrderTotals($merchantOrderTransfer);

        // Assert
        $merchantSalesOrderTotalsEntity = $this->tester->getMerchantSalesOrderTotalByIdMerchantSalesOrder(
            $merchantOrderTransfer->getIdMerchantOrder(),
        );

        $this->assertSame(400, $merchantSalesOrderTotalsEntity->getMerchantCommissionTotal());
        $this->assertSame(200, $merchantSalesOrderTotalsEntity->getMerchantCommissionRefundedTotal());
    }

    /**
     * @return void
     */
    public function testShouldKeepOtherMerchantOrderTotalsSame(): void
    {
        // Arrange
        $merchantOrderTransfer = $this->tester->createMerchantOrderWithTwoItems();

        // Act
        $this->tester->getFacade()->saveMerchantCommissionToMerchantOrderTotals($merchantOrderTransfer);

        // Assert
        $totalsTransfer = $merchantOrderTransfer->getTotals();
        $merchantSalesOrderTotalsEntity = $this->tester->getMerchantSalesOrderTotalByIdMerchantSalesOrder(
            $merchantOrderTransfer->getIdMerchantOrder(),
        );

        $this->assertSame($totalsTransfer->getGrandTotal(), $merchantSalesOrderTotalsEntity->getGrandTotal());
        $this->assertSame($totalsTransfer->getDiscountTotal(), $merchantSalesOrderTotalsEntity->getDiscountTotal());
        $this->assertEquals($totalsTransfer->getExpenseTotal(), $merchantSalesOrderTotalsEntity->getOrderExpenseTotal());
        $this->assertSame($totalsTransfer->getCanceledTotal(), $merchantSalesOrderTotalsEntity->getCanceledTotal());
    }
}
