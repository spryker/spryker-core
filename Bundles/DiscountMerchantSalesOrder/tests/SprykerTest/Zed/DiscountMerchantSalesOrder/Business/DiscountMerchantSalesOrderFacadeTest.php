<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountMerchantSalesOrder\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;

class DiscountMerchantSalesOrderFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DiscountMerchantSalesOrder\DiscountMerchantSalesOrderBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFilterMerchantDiscounts(): void
    {
        // Arrange
        $idSalesOrder = $this->tester->createOrder();
        $salesOrderItem = $this->tester->createSalesOrderItemForOrder($idSalesOrder);
        $discountForSalesOrderItem = $this->tester->createDiscountForSalesOrderItem($salesOrderItem->getIdSalesOrderItem());

        $merchantOrderTransfer = $this->tester->haveMerchantOrder([
            MerchantOrderTransfer::ID_ORDER => $idSalesOrder,
        ]);
        $merchantOrderItemTransfer = $this->tester->haveMerchantOrderItem([
            MerchantOrderItemTransfer::ID_ORDER_ITEM => $salesOrderItem->getIdSalesOrderItem(),
            MerchantOrderItemTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
        ]);

        $orderTransfer = $this->tester->getSalesFacade()->findOrderByIdSalesOrder($idSalesOrder);
        $merchantOrderTransfer->setOrder($orderTransfer);

        // Act
        $merchantOrderTransferExpected = $this->tester->getFacade()->filterMerchantDiscounts($merchantOrderTransfer);

        // Assert
        $this->assertSame(
            $merchantOrderTransferExpected->getOrder()->getCalculatedDiscounts(),
            $merchantOrderTransfer->getOrder()->getCalculatedDiscounts()
        );
    }
}
