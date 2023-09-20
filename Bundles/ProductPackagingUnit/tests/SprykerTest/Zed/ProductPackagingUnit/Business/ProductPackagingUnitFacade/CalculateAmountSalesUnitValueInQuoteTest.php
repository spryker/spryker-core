<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group CalculateAmountSalesUnitValueInQuoteTest
 * Add your own group annotations below this line
 */
class CalculateAmountSalesUnitValueInQuoteTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @dataProvider calculateAmountNormalizedSalesUnitValues
     *
     * @param int $amount
     * @param int $quantity
     * @param float $conversion
     * @param int $precision
     * @param int $expectedResult
     *
     * @return void
     */
    public function testCalculateAmountNormalizedSalesUnitValueCalculatesCorrectValues(
        int $amount,
        int $quantity,
        float $conversion,
        int $precision,
        int $expectedResult
    ): void {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransferForValueCalculation($amount, $quantity, $conversion, $precision);

        // Act
        $updatedQuoteTransfer = $this->tester->getFacade()->calculateAmountSalesUnitValueInQuote($quoteTransfer);

        // Assert
        $itemTransfer = $updatedQuoteTransfer->getItems()[0];
        $this->assertSame($expectedResult, $itemTransfer->getAmountSalesUnit()->getValue());
    }

    /**
     * @return array
     */
    protected function calculateAmountNormalizedSalesUnitValues(): array
    {
        return [
            [7, 1, 1.25, 1000, 5600],
            [7, 1, 1.25, 100, 560],
            [7, 1, 1.25, 10, 56],
            [7, 1, 1.25, 1, 6],
            [10, 1, 5, 1, 2],
            [13, 1, 7, 1000, 1857],
            [13, 1, 7, 100, 186],
            [13, 1, 7, 10, 19],
            [13, 1, 7, 1, 2],
        ];
    }
}
