<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Business\Model;

use Codeception\Test\Unit;
use SprykerTest\Zed\Tax\TaxBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Tax
 * @group Business
 * @group Model
 * @group TaxAmountCalculatorTest
 * Add your own group annotations below this line
 */
class TaxAmountCalculatorTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Tax\TaxBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Tax\Business\TaxFacadeInterface
     */
    protected $taxFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->taxFacade = $this->tester->getLocator()->tax()->facade();
    }

    /**
     * @dataProvider getSeparateTestData
     *
     * @param float $taxRate
     * @param int $price
     * @param int $quantity
     * @param int $expected
     *
     * @return void
     */
    public function testRecalculationOfSumTaxAmountForNonSplitMode(
        float $taxRate,
        int $price,
        int $quantity,
        int $expected
    ) {
        // Assign
        $nonSplitItemTransferCollection = $this->tester->createItemTransferCollection($taxRate, $price, $price * $quantity, TaxBusinessTester::DEFAULT_QUANTITY);
        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($nonSplitItemTransferCollection);

        // Act
        $this->taxFacade->calculateTaxAmount($calculableObjectTransfer);
        $recalculatedNonSplitSumTaxAmount = $this->tester->sumTaxAmount($calculableObjectTransfer);

        // Assert
        $this->assertEquals($expected, $recalculatedNonSplitSumTaxAmount);
    }

    /**
     * @dataProvider getSeparateTestData
     *
     * @param float $taxRate
     * @param int $price
     * @param int $quantity
     * @param int $expected
     *
     * @return void
     */
    public function testRecalculationOfSumTaxAmountForSplitMode(
        float $taxRate,
        int $price,
        int $quantity,
        int $expected
    ) {
        // Assign
        $splitItemTransferCollection = $this->tester->createItemTransferCollection($taxRate, $price, $price, $quantity);
        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($splitItemTransferCollection);

        // Act
        $this->taxFacade->calculateTaxAmount($calculableObjectTransfer);
        $recalculatedSplitSumTaxAmount = $this->tester->sumTaxAmount($calculableObjectTransfer);

        // Assert
        $this->assertEquals($expected, $recalculatedSplitSumTaxAmount);
    }

    /**
     * @dataProvider getGroupTestData
     *
     * @param float $taxRate
     * @param int $price
     * @param int $quantity
     *
     * @return void
     */
    public function testRecalculatedSumTaxAmountForNonSplitModeAndSplitShouldBeEqual(
        float $taxRate,
        int $price,
        int $quantity
    ) {
        // Assign
        $nonSplitItemTransferCollection = $this->tester->createItemTransferCollection($taxRate, $price, $price * $quantity, TaxBusinessTester::DEFAULT_QUANTITY);
        $splitItemTransferCollection = $this->tester->createItemTransferCollection($taxRate, $price, $price, $quantity);

        $calculableNonSplitObjectTransfer = $this->tester->createCalculableObjectTransfer($nonSplitItemTransferCollection);
        $calculableSplitObjectTransfer = $this->tester->createCalculableObjectTransfer($splitItemTransferCollection);

        // Act
        $this->taxFacade->calculateTaxAmount($calculableNonSplitObjectTransfer);
        $this->taxFacade->calculateTaxAmount($calculableSplitObjectTransfer);

        $recalculatedNonSplitSumTaxAmount = $this->tester->sumTaxAmount($calculableNonSplitObjectTransfer);
        $recalculatedSplitSumTaxAmount = $this->tester->sumTaxAmount($calculableSplitObjectTransfer);

        // Assert
        $this->assertEquals($recalculatedNonSplitSumTaxAmount, $recalculatedSplitSumTaxAmount);
    }

    /**
     * @return array
     */
    public function getGroupTestData()
    {
        return [
            [7.25, 3400, 20],
            [10.11, 100, 5],
            [20.22, 83, 10],
            [12.45, 137, 13],
            [14.26, 120, 8],
        ];
    }

    /**
     * @return array
     */
    public function getSeparateTestData()
    {
        return [
            [7.25, 124, 20, 180],
            [10.11, 860, 5, 435],
            [35.22, 32, 10, 113],
            [28.45, 47, 13, 174],
            [14.26, 56, 8, 64],
        ];
    }
}
