<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnit\Business\ProductMeasurementUnitFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMeasurementUnit
 * @group Business
 * @group ProductMeasurementUnitFacade
 * @group UpdateProductMeasurementUnitCollectionTest
 *
 * Add your own group annotations below this line
 */
class UpdateProductMeasurementUnitCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMeasurementUnit\ProductMeasurementUnitBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUpdatesProductMeasurementUnitCollection(): void
    {
        // Arrange
        $spyProductMeasurementUnitEntityTransfer = $this->tester->haveProductMeasurementUnit();
        $newName = $spyProductMeasurementUnitEntityTransfer->getName() . ' Updated';
        $productMeasurementUnitCollectionRequestTransfer = new ProductMeasurementUnitCollectionRequestTransfer();
        $productMeasurementUnitCollectionRequestTransfer->addProductMeasurementUnit(
            (new ProductMeasurementUnitTransfer())
                ->setCode($spyProductMeasurementUnitEntityTransfer->getCode())
                ->setName($newName)
                ->setDefaultPrecision($spyProductMeasurementUnitEntityTransfer->getDefaultPrecision()),
        );

        // Act
        $productMeasurementUnitCollectionResponseTransfer = $this->tester->getFacade()
            ->updateProductMeasurementUnitCollection($productMeasurementUnitCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $productMeasurementUnitCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $productMeasurementUnitCollectionResponseTransfer->getProductMeasurementUnits());
        $this->assertSame(
            $productMeasurementUnitCollectionRequestTransfer->getProductMeasurementUnits()[0]->getName(),
            $productMeasurementUnitCollectionResponseTransfer->getProductMeasurementUnits()[0]->getName(),
        );
    }

    /**
     * @return void
     */
    public function testUpdateDoesNotCreateProductMeasurementUnit(): void
    {
        // Arrange
        $spyProductMeasurementUnitEntityTransfer = $this->tester->buildRandomProductMeasurementUnit();
        $productMeasurementUnitCollectionRequestTransfer = new ProductMeasurementUnitCollectionRequestTransfer();
        $productMeasurementUnitCollectionRequestTransfer->addProductMeasurementUnit(
            (new ProductMeasurementUnitTransfer())
                ->setCode($spyProductMeasurementUnitEntityTransfer->getCode())
                ->setName($spyProductMeasurementUnitEntityTransfer->getName())
                ->setDefaultPrecision($spyProductMeasurementUnitEntityTransfer->getDefaultPrecision()),
        );

        // Act
        $productMeasurementUnitCollectionResponseTransfer = $this->tester->getFacade()
            ->updateProductMeasurementUnitCollection($productMeasurementUnitCollectionRequestTransfer);

        // Assert1
        $this->assertCount(1, $productMeasurementUnitCollectionResponseTransfer->getErrors());
        $this->assertCount(0, $productMeasurementUnitCollectionResponseTransfer->getProductMeasurementUnits());
    }
}
