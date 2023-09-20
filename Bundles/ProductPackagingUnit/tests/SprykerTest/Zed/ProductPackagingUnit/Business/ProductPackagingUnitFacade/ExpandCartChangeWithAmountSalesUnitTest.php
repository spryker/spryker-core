<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group ExpandCartChangeWithAmountSalesUnitTest
 * Add your own group annotations below this line
 */
class ExpandCartChangeWithAmountSalesUnitTest extends Unit
{
    /**
     * @var int
     */
    protected const ITEM_QUANTITY = 2;

    /**
     * @var int
     */
    protected const PACKAGE_AMOUNT = 4;

    /**
     * @var string
     */
    protected const BOX_PACKAGING_TYPE = 'box';

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandCartChangeWithAmountSalesUnit(): void
    {
        // Arrange
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::BOX_PACKAGING_TYPE]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_LEAD_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::DEFAULT_AMOUNT => static::PACKAGE_AMOUNT,
        ]);

        $code = 'MYCODE' . random_int(1, 100);
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
        ]);

        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $itemProductConcreteTransfer->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
        );

        $productMeasurementSalesUnitEntityTransfer = $this->tester->haveProductMeasurementSalesUnit(
            $boxProductConcreteTransfer->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit(),
        );

        $productMeasurementSalesUnitTransfer = (new ProductMeasurementSalesUnitTransfer())
            ->setIdProductMeasurementSalesUnit($productMeasurementSalesUnitEntityTransfer->getIdProductMeasurementSalesUnit());

        $cartChange = (new CartChangeTransfer())
            ->addItem(
                (new ItemTransfer())
                    ->setId($boxProductConcreteTransfer->getIdProductConcrete())
                    ->setSku($boxProductConcreteTransfer->getSku())
                    ->setQuantity(static::ITEM_QUANTITY)
                    ->setAmount(static::PACKAGE_AMOUNT)
                    ->setAmountLeadProduct($itemProductConcreteTransfer)
                    ->setAmountSalesUnit($productMeasurementSalesUnitTransfer),
            );

        // Act
        $this->tester->getFacade()->expandCartChangeWithAmountSalesUnit($cartChange);

        // Assert
        foreach ($cartChange->getItems() as $itemTransfer) {
            $this->assertInstanceOf(ProductMeasurementSalesUnitTransfer::class, $itemTransfer->getAmountSalesUnit());
        }
    }
}
