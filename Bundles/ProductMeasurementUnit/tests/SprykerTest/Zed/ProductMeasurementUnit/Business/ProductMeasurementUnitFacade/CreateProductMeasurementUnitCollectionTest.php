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
 * @group CreateProductMeasurementUnitCollectionTest
 *
 * Add your own group annotations below this line
 */
class CreateProductMeasurementUnitCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMeasurementUnit\ProductMeasurementUnitBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCanCreateProductMeasurementUnitCollection(): void
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
            ->createProductMeasurementUnitCollection($productMeasurementUnitCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $productMeasurementUnitCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $productMeasurementUnitCollectionResponseTransfer->getProductMeasurementUnits());
        $this->assertSame(
            $productMeasurementUnitCollectionRequestTransfer->getProductMeasurementUnits()[0]->getCode(),
            $productMeasurementUnitCollectionResponseTransfer->getProductMeasurementUnits()[0]->getCode(),
        );
    }

    /**
     * @return void
     */
    public function testCanNotCreateProductMeasurementUnitWithAlreadyExistingCode(): void
    {
        // Arrange
        $spyProductMeasurementUnitEntityTransfer = $this->tester->haveProductMeasurementUnit();
        $productMeasurementUnitCollectionRequestTransfer = new ProductMeasurementUnitCollectionRequestTransfer();
        $productMeasurementUnitCollectionRequestTransfer->addProductMeasurementUnit(
            (new ProductMeasurementUnitTransfer())
                ->setCode($spyProductMeasurementUnitEntityTransfer->getCode())
                ->setName($spyProductMeasurementUnitEntityTransfer->getName())
                ->setDefaultPrecision($spyProductMeasurementUnitEntityTransfer->getDefaultPrecision()),
        );

        // Act
        $productMeasurementUnitCollectionResponseTransfer = $this->tester->getFacade()
            ->createProductMeasurementUnitCollection($productMeasurementUnitCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $productMeasurementUnitCollectionResponseTransfer->getProductMeasurementUnits());
        $this->assertCount(1, $productMeasurementUnitCollectionResponseTransfer->getErrors());
    }

    /**
     * @dataProvider getInvalidPrecisions
     *
     * @param string|int $invalidPrecision
     *
     * @return void
     */
    public function testCanNotCreateProductMeasurementUnitWithInvalidPrecision(int|string|null $invalidPrecision): void
    {
        // Arrange
        $spyProductMeasurementUnitEntityTransfer = $this->tester->buildRandomProductMeasurementUnit();
        $productMeasurementUnitCollectionRequestTransfer = new ProductMeasurementUnitCollectionRequestTransfer();
        $productMeasurementUnitCollectionRequestTransfer->addProductMeasurementUnit(
            (new ProductMeasurementUnitTransfer())
                ->setCode($spyProductMeasurementUnitEntityTransfer->getCode())
                ->setName($spyProductMeasurementUnitEntityTransfer->getName())
                ->setDefaultPrecision($invalidPrecision),
        );

        // Act
        $productMeasurementUnitCollectionResponseTransfer = $this->tester->getFacade()
            ->createProductMeasurementUnitCollection($productMeasurementUnitCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productMeasurementUnitCollectionResponseTransfer->getErrors());
        $this->assertCount(0, $productMeasurementUnitCollectionResponseTransfer->getProductMeasurementUnits());
    }

    /**
     * @return array<int|string|null>
     */
    public static function getInvalidPrecisions(): array
    {
        return [
            [null],
            [''],
            ['invalid'],
            ['-1'],
            ['0'],
            ['100.01'],
        ];
    }
}
