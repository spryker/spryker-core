<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnit\Business\ProductMeasurementUnitFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitConditionsTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCriteriaTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMeasurementUnit
 * @group Business
 * @group ProductMeasurementUnitFacade
 * @group DeleteProductMeasurementUnitCollectionTest
 *
 * Add your own group annotations below this line
 */
class DeleteProductMeasurementUnitCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMeasurementUnit\ProductMeasurementUnitBusinessTester
     */
    protected $tester;

    /**
     * @var array
     */
    protected const VALID_MEASUREMENT_UNIT_1 = [
        'code' => 'TEST_GRAM_1',
        'name' => 'Test Gram_1',
        'conversionRate' => 1.0,
    ];

    /**
     * @var array
     */
    protected const VALID_MEASUREMENT_UNIT_2 = [
        'code' => 'TEST_GRAM_2',
        'name' => 'Test Gram_2',
        'conversionRate' => 2.0,
    ];

    /**
     * @return void
     */
    public function testDeletesExactlyRequestedItems(): void
    {
        // Arrange
        $this->tester->haveProductMeasurementUnit(static::VALID_MEASUREMENT_UNIT_1);
        $this->tester->haveProductMeasurementUnit(static::VALID_MEASUREMENT_UNIT_2);

        $productMeasurementUnitConditionsTransfer = (new ProductMeasurementUnitCollectionDeleteCriteriaTransfer())
            ->addCode(static::VALID_MEASUREMENT_UNIT_1['code']);

        // Act
        $productMeasurementUnitCollectionResponseTransfer = $this->tester->getFacade()
            ->deleteProductMeasurementUnitCollection($productMeasurementUnitConditionsTransfer);
        $productMeasurementUnit1 = $this->getProductMeasurementUnitTransferByCode(static::VALID_MEASUREMENT_UNIT_1['code']);
        $productMeasurementUnit2 = $this->getProductMeasurementUnitTransferByCode(static::VALID_MEASUREMENT_UNIT_2['code']);

        // Assert
        $this->assertCount(1, $productMeasurementUnitCollectionResponseTransfer->getProductMeasurementUnits());
        $this->assertEquals(static::VALID_MEASUREMENT_UNIT_1['code'], $productMeasurementUnitCollectionResponseTransfer->getProductMeasurementUnits()[0]->getCode());
        $this->assertNull($productMeasurementUnit1->getCode());
        $this->assertEquals(static::VALID_MEASUREMENT_UNIT_2['code'], $productMeasurementUnit2->getCode());
    }

    /**
     * @return void
     */
    public function testDeletesNoItemsOnEmptyCriteria(): void
    {
        // Arrange
        $this->tester->haveProductMeasurementUnit(static::VALID_MEASUREMENT_UNIT_1);

        $productMeasurementUnitConditionsTransfer = (new ProductMeasurementUnitCollectionDeleteCriteriaTransfer());

        // Act
        $productMeasurementUnitCollectionResponseTransfer = $this->tester->getFacade()
            ->deleteProductMeasurementUnitCollection($productMeasurementUnitConditionsTransfer);
        $productMeasurementUnit1 = $this->getProductMeasurementUnitTransferByCode(static::VALID_MEASUREMENT_UNIT_1['code']);

        // Assert
        $this->assertCount(0, $productMeasurementUnitCollectionResponseTransfer->getProductMeasurementUnits());
        $this->assertCount(1, $productMeasurementUnitCollectionResponseTransfer->getErrors());
        $this->assertEquals(static::VALID_MEASUREMENT_UNIT_1['code'], $productMeasurementUnit1->getCode());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteMeasurementUnitWithAssignedProducts(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $spyProductMeasurementUnitEntityTransfer = $this->tester->haveProductMeasurementUnit();
        $this->tester->haveProductMeasurementBaseUnit(
            $productAbstractTransfer->getIdProductAbstract(),
            $spyProductMeasurementUnitEntityTransfer->getIdProductMeasurementUnit(),
        );

        $productMeasurementUnitConditionsTransfer = (new ProductMeasurementUnitCollectionDeleteCriteriaTransfer())
            ->addCode($spyProductMeasurementUnitEntityTransfer->getCode());

        // Act
        $productMeasurementUnitCollectionResponseTransfer = $this->tester->getFacade()
            ->deleteProductMeasurementUnitCollection($productMeasurementUnitConditionsTransfer);
        $productMeasurementUnit1 = $this->getProductMeasurementUnitTransferByCode($spyProductMeasurementUnitEntityTransfer->getCode());

        // Assert
        $this->assertCount(0, $productMeasurementUnitCollectionResponseTransfer->getProductMeasurementUnits());
        $this->assertCount(1, $productMeasurementUnitCollectionResponseTransfer->getErrors());
        $this->assertEquals($spyProductMeasurementUnitEntityTransfer->getCode(), $productMeasurementUnit1->getCode());
    }

    /**
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer
     */
    protected function getProductMeasurementUnitTransferByCode(string $code): ProductMeasurementUnitTransfer
    {
        $productMeasurementUnitConditionsTransfer = (new ProductMeasurementUnitConditionsTransfer())
            ->addCode($code);
        $productMeasurementUnitCriteriaTransfer = (new ProductMeasurementUnitCriteriaTransfer())
            ->setProductMeasurementUnitConditions($productMeasurementUnitConditionsTransfer);

        return $this->tester
            ->getFacade()
            ->getProductMeasurementUnitCollection($productMeasurementUnitCriteriaTransfer)
            ->getProductMeasurementUnits()[0] ?? (new ProductMeasurementUnitTransfer());
    }
}
