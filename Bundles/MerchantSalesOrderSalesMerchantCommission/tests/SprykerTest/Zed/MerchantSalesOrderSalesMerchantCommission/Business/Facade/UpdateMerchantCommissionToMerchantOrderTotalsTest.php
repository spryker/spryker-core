<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesOrderSalesMerchantCommission\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
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
 * @group UpdateMerchantCommissionToMerchantOrderTotalsTest
 * Add your own group annotations below this line
 */
class UpdateMerchantCommissionToMerchantOrderTotalsTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_MERCHANT_REFERENCE = 'FAKE_MERCHANT_REFERENCE';

    /**
     * @var \SprykerTest\Zed\MerchantSalesOrderSalesMerchantCommission\MerchantSalesOrderSalesMerchantCommissionBusinessTester
     */
    protected MerchantSalesOrderSalesMerchantCommissionBusinessTester $tester;

    /**
     * @dataProvider getInvalidOrderTransferDataProvider
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function testShouldThrowNullValueException(OrderTransfer $orderTransfer): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->updateMerchantCommissionToMerchantOrderTotals(
            $orderTransfer,
            $orderTransfer->getItems()->getArrayCopy(),
        );
    }

    /**
     * @return void
     */
    public function testShouldSkipOrderItemsWithoutMerchantReference(): void
    {
        // Arrange
        $merchantOrderTransfer = $this->tester->createMerchantOrderWithTwoItems();

        $item1 = $merchantOrderTransfer->getMerchantOrderItems()->offsetGet(0)->getOrderItem();
        $item2 = $merchantOrderTransfer->getMerchantOrderItems()->offsetGet(1)->getOrderItem();
        $item3 = (new ItemTransfer())->setMerchantReference(null);

        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder($merchantOrderTransfer->getIdOrder())
            ->addItem($item1)
            ->addItem($item2)
            ->addItem((new ItemTransfer())->setMerchantReference(null));

        // Act
        $this->tester->getFacade()->updateMerchantCommissionToMerchantOrderTotals(
            $orderTransfer,
            [$item1, $item2, $item3],
        );

        // Assert
        $merchantSalesOrderTotalsEntity = $this->tester->getMerchantSalesOrderTotalByIdMerchantSalesOrder(
            $merchantOrderTransfer->getIdMerchantOrder(),
        );

        $this->assertSame(200, $merchantSalesOrderTotalsEntity->getMerchantCommissionTotal());
        $this->assertSame(200, $merchantSalesOrderTotalsEntity->getMerchantCommissionRefundedTotal());
    }

    /**
     * @return void
     */
    public function testShouldUpdateMerchantOrderTotals(): void
    {
        // Arrange
        $merchantOrderTransfer = $this->tester->createMerchantOrderWithTwoItems();
        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder($merchantOrderTransfer->getIdOrder())
            ->addItem($merchantOrderTransfer->getMerchantOrderItems()->offsetGet(0)->getOrderItem())
            ->addItem($merchantOrderTransfer->getMerchantOrderItems()->offsetGet(1)->getOrderItem());

        // Act
        $this->tester->getFacade()->updateMerchantCommissionToMerchantOrderTotals(
            $orderTransfer,
            $orderTransfer->getItems()->getArrayCopy(),
        );

        // Assert
        $merchantSalesOrderTotalsEntity = $this->tester->getMerchantSalesOrderTotalByIdMerchantSalesOrder(
            $merchantOrderTransfer->getIdMerchantOrder(),
        );

        $this->assertSame(200, $merchantSalesOrderTotalsEntity->getMerchantCommissionTotal());
        $this->assertSame(200, $merchantSalesOrderTotalsEntity->getMerchantCommissionRefundedTotal());
    }

    /**
     * @return void
     */
    public function testShouldKeepOtherMerchantOrderTotalsSame(): void
    {
        // Arrange
        $merchantOrderTransfer = $this->tester->createMerchantOrderWithTwoItems();
        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder($merchantOrderTransfer->getIdOrder())
            ->addItem($merchantOrderTransfer->getMerchantOrderItems()->offsetGet(0)->getOrderItem())
            ->addItem($merchantOrderTransfer->getMerchantOrderItems()->offsetGet(1)->getOrderItem());

        // Act
        $this->tester->getFacade()->updateMerchantCommissionToMerchantOrderTotals(
            $orderTransfer,
            $orderTransfer->getItems()->getArrayCopy(),
        );

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

    /**
     * @return array<string, list<\Generated\Shared\Transfer\OrderTransfer>>
     */
    protected function getInvalidOrderTransferDataProvider(): array
    {
        return [
            'When IdSalesOrder is not set' => [
                (new OrderTransfer())
                    ->setIdSalesOrder(null)
                    ->addItem((new ItemTransfer())->setMerchantReference(static::FAKE_MERCHANT_REFERENCE)),
            ],
        ];
    }
}
