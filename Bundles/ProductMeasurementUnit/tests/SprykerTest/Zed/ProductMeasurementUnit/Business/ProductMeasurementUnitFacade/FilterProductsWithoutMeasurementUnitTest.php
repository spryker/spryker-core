<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMeasurementUnit
 * @group Business
 * @group FilterProductsWithoutMeasurementUnitTest
 * Add your own group annotations below this line
 */
class FilterProductsWithoutMeasurementUnitTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMeasurementUnit\ProductMeasurementUnitBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFilterProductsWithoutMeasurementUnitTestThrowsExceptionForMissingIdProductConcrete(): void
    {
        // Arrange
        $productConcreteTransfers = [(new ProductConcreteTransfer())->setFkProductAbstract(1)];

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->filterProductsWithoutMeasurementUnit($productConcreteTransfers);
    }

    /**
     * @return void
     */
    public function testFilterProductsWithoutMeasurementUnitTestThrowsExceptionForMissingFkProductAbstract(): void
    {
        // Arrange
        $productConcreteTransfers = [(new ProductConcreteTransfer())->setIdProductConcrete(1)];

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->filterProductsWithoutMeasurementUnit($productConcreteTransfers);
    }

    /**
     * @return void
     */
    public function testFilterProductsWithoutMeasurementUnitTestExcludesProductWithSalesUnit(): void
    {
        // Arrange
        $productConcreteTransferWithMeasurementUnit = $this->tester->haveProduct();
        $productConcreteTransfer = $this->tester->haveProduct();

        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => uniqid(),
        ]);

        $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
            $productConcreteTransferWithMeasurementUnit->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
        );

        $this->tester->haveProductMeasurementSalesUnit(
            $productConcreteTransferWithMeasurementUnit->getIdProductConcrete(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
            $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit()
        );

        // Act
        $productConcreteTransfers = $this->tester->getFacade()->filterProductsWithoutMeasurementUnit([
            $productConcreteTransfer,
            $productConcreteTransferWithMeasurementUnit,
        ]);

        // Assert
        $this->assertCount(1, $productConcreteTransfers);
    }

    /**
     * @return void
     */
    public function testFilterProductsWithoutMeasurementUnitTestExcludesProductWithBaseUnit(): void
    {
        // Arrange
        $productConcreteTransferWithMeasurement = $this->tester->haveProduct();
        $productConcreteTransfer = $this->tester->haveProduct();

        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => uniqid(),
        ]);

        $this->tester->haveProductMeasurementBaseUnit(
            $productConcreteTransferWithMeasurement->getFkProductAbstract(),
            $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
        );

        // Act
        $productConcreteTransfers = $this->tester->getFacade()->filterProductsWithoutMeasurementUnit([
            $productConcreteTransfer,
            $productConcreteTransferWithMeasurement,
        ]);

        // Assert
        $this->assertCount(1, $productConcreteTransfers);
    }

    /**
     * @return void
     */
    public function testFilterProductsWithoutMeasurementUnitTestReturnsUnmodifiedArray(): void
    {
        // Arrange
        $firstProductConcreteTransfer = $this->tester->haveProduct();
        $secondProductConcreteTransfer = $this->tester->haveProduct();

        // Act
        $productConcreteTransfers = $this->tester->getFacade()->filterProductsWithoutMeasurementUnit([
            $firstProductConcreteTransfer,
            $secondProductConcreteTransfer,
        ]);

        // Assert
        $this->assertCount(2, $productConcreteTransfers);
    }
}
