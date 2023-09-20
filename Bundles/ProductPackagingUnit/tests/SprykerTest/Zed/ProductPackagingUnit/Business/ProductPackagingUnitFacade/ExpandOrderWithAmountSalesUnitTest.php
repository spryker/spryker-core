<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group ExpandOrderWithAmountSalesUnitTest
 * Add your own group annotations below this line
 */
class ExpandOrderWithAmountSalesUnitTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @return void
     */
    public function testHydrateOrderWithAmountSalesUnit(): void
    {
        // Arrange
        $salesOrderEntity = $this->tester->create();
        $productMeasurementUnit = $this->tester->haveProductMeasurementUnit();
        foreach ($salesOrderEntity->getItems() as $salesOrderItem) {
            $salesOrderItem->setAmountMeasurementUnitName($productMeasurementUnit->getName());
            $salesOrderItem->save();
        }

        $orderTransfer = (new OrderTransfer())->fromArray($salesOrderEntity->toArray(), true);
        foreach ($salesOrderEntity->getItems() as $salesOrderItem) {
            $itemTransfer = (new ItemTransfer())->fromArray($salesOrderItem->toArray(), true);
            $orderTransfer->addItem($itemTransfer);
        }

        // Act
        $orderTransfer = $this->tester->getFacade()->expandOrderWithAmountSalesUnit($orderTransfer);

        // Assert
        $this->assertInstanceOf(OrderTransfer::class, $orderTransfer);

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $this->assertInstanceOf(ProductMeasurementSalesUnitTransfer::class, $itemTransfer->getAmountSalesUnit());
        }
    }
}
