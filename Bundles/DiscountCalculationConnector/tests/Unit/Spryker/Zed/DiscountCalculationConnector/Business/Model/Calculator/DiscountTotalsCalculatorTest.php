<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator;

/**
 * @group Spryker
 * @group Zed
 * @group DiscountCalculationConnector
 * @group Business
 * @group DiscountTotalsCalculator
 */
class DiscountTotalsCalculatorTest extends \PHPUnit_Framework_TestCase
{

    const ITEM_QUANTITY = 2;
    const UNIT_GROSS_AMOUNT = 10;

    /**
     * @return void
     */
    public function testCalculateTotalsShouldSumAllDiscounts()
    {
        $discountTotalsCalculator = $this->createDiscountTotalsCalculator();

        $calculatedDiscountFixtures = $this->getCalculatedDiscountFixtures();

        $quoteTransfer = $this->createQuoteTransferWithFixtureData(
            self::ITEM_QUANTITY,
            $calculatedDiscountFixtures
        );

        $discountTotalsCalculator->recalculate($quoteTransfer);

        $this->assertEquals(80, $quoteTransfer->getTotals()->getDiscountTotal());
    }

    /**
     * @param int $itemQuantity
     * @param array $calculatedDiscounts
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransferWithFixtureData($itemQuantity, array $calculatedDiscounts)
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $quoteTransfer->setTotals($this->createTotalsTransfer());

        $itemTransfer = $this->createItemTransfer();
        $itemTransfer->setQuantity($itemQuantity);

        foreach ($calculatedDiscounts as $calculatedDiscount) {
            $calculatedDiscountTransfer = $this->createCalculatedDiscountTransfer();
            $calculatedDiscountTransfer->setQuantity($calculatedDiscount['quantity']);
            $calculatedDiscountTransfer->setUnitGrossAmount($calculatedDiscount['unitGrossAmount']);
            $calculatedDiscountTransfer->setSumGrossAmount(
                $calculatedDiscount['unitGrossAmount'] * $calculatedDiscount['quantity']
            );
            $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

            $expenseTransfer = $this->createExpenseTransfer();
            $expenseTransfer->addCalculatedDiscount(clone $calculatedDiscountTransfer);
            $quoteTransfer->addExpense($expenseTransfer);
        }

        $quoteTransfer->addItem($itemTransfer);

        return $quoteTransfer;
    }

    /**
     * @return array
     */
    protected function getCalculatedDiscountFixtures()
    {
        return [
            [
                'quantity' => 2,
                'unitGrossAmount' => 10,
            ],
            [
                'quantity' => 2,
                'unitGrossAmount' => 10,
            ],
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function createTotalsTransfer()
    {
        return new TotalsTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\DiscountTotalsCalculator
     */
    protected function createDiscountTotalsCalculator()
    {
        return new DiscountTotalsCalculator();
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer()
    {
        return new ItemTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CalculatedDiscountTransfer
     */
    protected function createCalculatedDiscountTransfer()
    {
        return new CalculatedDiscountTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseTransfer()
    {
        return new ExpenseTransfer();
    }

}
