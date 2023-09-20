<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group ExpandSalesOrderItemWithAmountSalesUnitTest
 * Add your own group annotations below this line
 */
class ExpandSalesOrderItemWithAmountSalesUnitTest extends Unit
{
    /**
     * @var int
     */
    protected const ITEM_QUANTITY = 2;

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandOrderItemWithAmountSalesUnit(): void
    {
        // Arrange
        $code = 'MYCODE' . random_int(1, 100);
        $productTransfer = $this->tester->haveProduct();
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
        ]);
        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $productTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
        );
        $productMeasurementBaseUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);

        $productMeasurementSalesUnitTransfer = $this->tester->haveProductMeasurementSalesUnit(
            $productTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit(),
        );

        $productMeasurementSalesUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);
        $productMeasurementSalesUnitTransfer->setProductMeasurementBaseUnit($productMeasurementBaseUnitTransfer);

        $amountSalesUnitTransfer = (new ProductMeasurementSalesUnitTransfer())->fromArray($productMeasurementSalesUnitTransfer->toArray(), true);
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(static::ITEM_QUANTITY);
        $itemTransfer->setAmountSalesUnit($amountSalesUnitTransfer);

        // Act
        $salesOrderItemEntity = $this->tester->getFacade()->expandSalesOrderItemWithAmountSalesUnit(
            $itemTransfer,
            new SpySalesOrderItemEntityTransfer(),
        );

        // Assert
        $this->assertSame($productMeasurementUnitTransfer->getName(), $salesOrderItemEntity->getAmountMeasurementUnitName());
        $this->assertSame($productMeasurementUnitTransfer->getName(), $salesOrderItemEntity->getAmountBaseMeasurementUnitName());
        $this->assertSame($amountSalesUnitTransfer->getPrecision(), $salesOrderItemEntity->getAmountMeasurementUnitPrecision());
        $this->assertSame($amountSalesUnitTransfer->getConversion(), $salesOrderItemEntity->getAmountMeasurementUnitConversion());
    }
}
