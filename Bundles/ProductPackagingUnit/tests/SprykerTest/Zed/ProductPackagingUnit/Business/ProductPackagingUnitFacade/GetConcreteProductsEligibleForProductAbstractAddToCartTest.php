<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group GetConcreteProductsEligibleForProductAbstractAddToCartTest
 * Add your own group annotations below this line
 */
class GetConcreteProductsEligibleForProductAbstractAddToCartTest extends Unit
{
    protected const PACKAGING_UNIT_NAME = 'packagingUnit';
    protected const PACKAGING_UNIT_AMOUNT = 1;

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetConcreteProductsEligibleForProductAbstractAddToCartTestThrowsExceptionForMissingProperty(): void
    {
        // Arrange
        $productConcreteTransfers = [new ProductConcreteTransfer()];

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->getConcreteProductsEligibleForProductAbstractAddToCart($productConcreteTransfers);
    }

    /**
     * @return void
     */
    public function testGetConcreteProductsEligibleForProductAbstractAddToCartTestExcludesProduct(): void
    {
        // Arrange
        $productConcreteTransferWithPackagingUnit = $this->tester->haveProduct();
        $productConcreteTransfer = $this->tester->haveProduct();

        $productPackagingUnitTypeTransfer = $this->tester->haveProductPackagingUnitType([
            SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_UNIT_NAME,
        ]);

        $this->tester->haveProductPackagingUnit([
            SpyProductPackagingUnitEntityTransfer::FK_LEAD_PRODUCT => $productConcreteTransferWithPackagingUnit->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $productConcreteTransferWithPackagingUnit->getIdProductConcrete(),
            SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $productPackagingUnitTypeTransfer->getIdProductPackagingUnitType(),
            SpyProductPackagingUnitEntityTransfer::DEFAULT_AMOUNT => static::PACKAGING_UNIT_AMOUNT,
        ]);

        // Act
        $productConcreteTransfers = $this->tester->getFacade()->getConcreteProductsEligibleForProductAbstractAddToCart([
            $productConcreteTransfer,
            $productConcreteTransferWithPackagingUnit,
        ]);

        // Assert
        $this->assertCount(1, $productConcreteTransfers);
    }

    /**
     * @return void
     */
    public function testGetConcreteProductsEligibleForProductAbstractAddToCartTestReturnsUnmodifiedArray(): void
    {
        // Arrange
        $firstProductConcreteTransfer = $this->tester->haveProduct();
        $secondProductConcreteTransfer = $this->tester->haveProduct();

        // Act
        $productConcreteTransfers = $this->tester->getFacade()->getConcreteProductsEligibleForProductAbstractAddToCart([
            $firstProductConcreteTransfer,
            $secondProductConcreteTransfer,
        ]);

        // Assert
        $this->assertCount(2, $productConcreteTransfers);
    }
}
