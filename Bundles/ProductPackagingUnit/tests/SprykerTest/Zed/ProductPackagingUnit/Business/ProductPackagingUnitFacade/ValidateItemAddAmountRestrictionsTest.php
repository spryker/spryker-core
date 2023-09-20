<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
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
 * @group ValidateItemAddAmountRestrictionsTest
 * Add your own group annotations below this line
 */
class ValidateItemAddAmountRestrictionsTest extends Unit
{
    /**
     * @var string
     */
    protected const BOX_PACKAGING_TYPE = 'box';

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @dataProvider itemAdditionAmounts
     *
     * @param bool $expectedIsSuccess
     * @param int $defaultAmount
     * @param int $itemAmount
     * @param int $itemQuantity
     * @param int|null $minRestriction
     * @param int|null $maxRestriction
     * @param int|null $intervalRestriction
     * @param bool $isAmountVariable
     *
     * @return void
     */
    public function testValidateItemAddAmountRestrictions(
        bool $expectedIsSuccess,
        int $defaultAmount,
        int $itemAmount,
        int $itemQuantity,
        ?int $minRestriction,
        ?int $maxRestriction,
        ?int $intervalRestriction,
        bool $isAmountVariable
    ): void {
        // Arrange
        $itemProductConcreteTransfer = $this->tester->haveProduct();
        $boxProductConcreteTransfer = $this->tester->haveProduct([
            SpyProductEntityTransfer::FK_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ], [
            SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT => $itemProductConcreteTransfer->getFkProductAbstract(),
        ]);

        $boxProductPackagingUnitType = $this->tester->haveProductPackagingUnitType([
            SpyProductPackagingUnitTypeEntityTransfer::NAME => static::BOX_PACKAGING_TYPE,
        ]);

        $this->tester->haveProductPackagingUnit(
            [
                SpyProductPackagingUnitEntityTransfer::FK_PRODUCT => $boxProductConcreteTransfer->getIdProductConcrete(),
                SpyProductPackagingUnitEntityTransfer::FK_PRODUCT_PACKAGING_UNIT_TYPE => $boxProductPackagingUnitType->getIdProductPackagingUnitType(),
                SpyProductPackagingUnitEntityTransfer::FK_LEAD_PRODUCT => $itemProductConcreteTransfer->getIdProductConcrete(),
            ],
            [
                SpyProductPackagingUnitEntityTransfer::DEFAULT_AMOUNT => $defaultAmount,
                SpyProductPackagingUnitEntityTransfer::AMOUNT_MIN => $minRestriction,
                SpyProductPackagingUnitEntityTransfer::AMOUNT_MAX => $maxRestriction,
                SpyProductPackagingUnitEntityTransfer::AMOUNT_INTERVAL => $intervalRestriction,
                SpyProductPackagingUnitEntityTransfer::IS_AMOUNT_VARIABLE => $isAmountVariable,
            ],
        );

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
            ->fromArray($productMeasurementSalesUnitEntityTransfer->toArray(), true);

        $cartChangeTransfer = $this->tester->createCartChangeTransferForProductPackagingUnitValidation(
            $boxProductConcreteTransfer,
            $productMeasurementSalesUnitTransfer,
            $itemAmount,
            $itemQuantity,
        );

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->validateItemAddAmountRestrictions($cartChangeTransfer);

        // Assert
        $this->assertSame($expectedIsSuccess, $cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return array
     */
    protected function itemAdditionAmounts(): array
    {
        return [
            // expectedResult, defaultAmount, itemAmount, itemQuantity, minRestriction, maxRestriction, intervalRestriction, isAmountVariable
            [true, 1, 2, 1, 1, null, 1, true], // general rule
            [true, 1, 7, 1, 7, null, 1, true], // min equals new amount
            [true, 1, 5, 1, 5, 5, 1, true], // max equals new amount
            [true, 1, 7, 1, 0, null, 7, true], // interval matches new amount
            [false, 1, 5, 1, 7, 7, 7, true], // min, max, interval matches new amount
            [false, 1, 5, 1, 8, null, 1, true], // min above new amount
            [false, 1, 5, 1, 1, 3, 1, true], // max below new amount
            [false, 1, 5, 1, 1, null, 3, true], // interval does not match new amount
            [true, 1, 1, 1, null, null, null, false], // is not variable
            [true, 2, 4, 2, null, null, null, false], // is not variable with quantity more than 1
            [false, 2, 5, 2, null, null, null, false], // is not variable with amount per quantity not equal to default amount
        ];
    }
}
