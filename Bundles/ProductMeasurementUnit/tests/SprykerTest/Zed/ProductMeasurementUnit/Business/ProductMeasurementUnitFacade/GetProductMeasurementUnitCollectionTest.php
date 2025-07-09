<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnit\Business\ProductMeasurementUnitFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductMeasurementUnitConditionsTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCriteriaTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMeasurementUnit
 * @group Business
 * @group ProductMeasurementUnitFacade
 * @group GetProductMeasurementUnitCollectionTest
 *
 * Add your own group annotations below this line
 */
class GetProductMeasurementUnitCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMeasurementUnit\ProductMeasurementUnitBusinessTester
     */
    protected $tester;

    /**
     * @var array
     */
    protected const VALID_MEASUREMENT_UNIT = [
        'code' => 'TEST_GRAM',
        'name' => 'Test Gram',
        'conversionRate' => 1.0,
    ];

    /**
     * @return void
     */
    public function testReturnsEmptyArrayForInvalidCode(): void
    {
        // Arrange
        $productMeasurementUnitConditionsTransfer = (new ProductMeasurementUnitConditionsTransfer())
            ->addCode('INVALID_CODE');
        $productMeasurementUnitCriteriaTransfer = (new ProductMeasurementUnitCriteriaTransfer())
            ->setProductMeasurementUnitConditions($productMeasurementUnitConditionsTransfer);

        // Act
        $productMeasurementUnitCollectionTransfer = $this->tester->getFacade()
            ->getProductMeasurementUnitCollection($productMeasurementUnitCriteriaTransfer);

        // Assert
        $this->assertCount(0, $productMeasurementUnitCollectionTransfer->getProductMeasurementUnits());
    }

    /**
     * @return void
     */
    public function testReturnsItemByCode(): void
    {
        // Arrange
        $this->tester->haveProductMeasurementUnit(static::VALID_MEASUREMENT_UNIT);

        $productMeasurementUnitConditionsTransfer = (new ProductMeasurementUnitConditionsTransfer())
            ->addCode(static::VALID_MEASUREMENT_UNIT['code']);
        $productMeasurementUnitCriteriaTransfer = (new ProductMeasurementUnitCriteriaTransfer())
            ->setProductMeasurementUnitConditions($productMeasurementUnitConditionsTransfer);

        // Act
        $productMeasurementUnitCollectionTransfer = $this->tester->getFacade()
            ->getProductMeasurementUnitCollection($productMeasurementUnitCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productMeasurementUnitCollectionTransfer->getProductMeasurementUnits());
        $this->assertEquals(static::VALID_MEASUREMENT_UNIT['code'], $productMeasurementUnitCollectionTransfer->getProductMeasurementUnits()[0]->getCode());
    }
}
