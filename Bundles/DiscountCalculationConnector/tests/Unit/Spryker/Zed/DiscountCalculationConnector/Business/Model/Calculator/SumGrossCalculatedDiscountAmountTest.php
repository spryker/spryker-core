<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\SumGrossCalculatedDiscountAmountCalculator;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group DiscountCalculationConnector
 * @group Business
 * @group Model
 * @group Calculator
 * @group SumGrossCalculatedDiscountAmountTest
 */
class SumGrossCalculatedDiscountAmountTest extends PHPUnit_Framework_TestCase
{

    const ITEM_QUANTITY = 2;
    const UNIT_GROSS_AMOUNT = 10;

    /**
     * @return void
     */
    public function testCalculateTotalsShouldUpdateItemTotalDiscountsAmounts()
    {
        $discountTotalsCalculator = $this->createSumGrossCalculatedDiscountAmountCalculator();

        $calculatedDiscountFixtures = $this->getCalculatedDiscountFixtures();

        $quoteTransfer = $this->createQuoteTransferWithFixtureData(
            self::ITEM_QUANTITY,
            $calculatedDiscountFixtures
        );

        $discountTotalsCalculator->recalculate($quoteTransfer);

        $itemTransfer = $quoteTransfer->getItems()[0];

        $this->assertSame(160, $itemTransfer->getSumGrossPriceWithDiscounts());

        $this->assertSame(
            40,
            $itemTransfer->getSumTotalDiscountAmount()
        );
    }

    /**
     * @return void
     */
    public function testCalculateTotalsShouldUpdateItemDiscountGrossUnitAmounts()
    {
        $discountTotalsCalculator = $this->createSumGrossCalculatedDiscountAmountCalculator();

        $calculatedDiscountFixtures = $this->getCalculatedDiscountFixtures();

        $quoteTransfer = $this->createQuoteTransferWithFixtureData(
            self::ITEM_QUANTITY,
            $calculatedDiscountFixtures
        );

        $discountTotalsCalculator->recalculate($quoteTransfer);

        $itemTransfer = $quoteTransfer->getItems()[0];

        $this->assertSame(80, $itemTransfer->getUnitGrossPriceWithDiscounts());

        $this->assertSame(
            20,
            $itemTransfer->getUnitTotalDiscountAmount()
        );
    }

    /**
     * @return void
     */
    public function testCalculateDiscountsWhenDiscountIsOverItemAmountShouldNotBeMoreThatItemAmount()
    {
        $discountTotalsCalculator = $this->createSumGrossCalculatedDiscountAmountCalculator();

        $calculatedDiscountFixtures = $this->getCalculatedDiscountFixtures();

        $calculatedDiscountFixtures[0]['unitGrossAmount'] = 1000;
        $calculatedDiscountFixtures[1]['unitGrossAmount'] = 1000;

        $quoteTransfer = $this->createQuoteTransferWithFixtureData(
            self::ITEM_QUANTITY,
            $calculatedDiscountFixtures
        );

        $discountTotalsCalculator->recalculate($quoteTransfer);

        $itemTransfer = $quoteTransfer->getItems()[0];

        $this->assertSame(0, $itemTransfer->getUnitGrossPriceWithDiscounts());
        $this->assertSame(100, $itemTransfer->getUnitTotalDiscountAmount());
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
        $itemTransfer->setUnitGrossPrice(100);
        $itemTransfer->setSumGrossPrice(100 * $itemQuantity);

        foreach ($calculatedDiscounts as $calculatedDiscount) {
            $calculatedDiscountTransfer = $this->createCalculatedDiscountTransfer();
            $calculatedDiscountTransfer->setIdDiscount($calculatedDiscount['idDiscount']);
            $calculatedDiscountTransfer->setQuantity($calculatedDiscount['quantity']);
            $calculatedDiscountTransfer->setUnitGrossAmount($calculatedDiscount['unitGrossAmount']);
            $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

            $expenseTransfer = $this->createExpenseTransfer();
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
                'idDiscount' => 1,
                'quantity' => 2,
                'unitGrossAmount' => 10,
            ],
            [
                'idDiscount' => 2,
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
     * @return \Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\SumGrossCalculatedDiscountAmountCalculator
     */
    protected function createSumGrossCalculatedDiscountAmountCalculator()
    {
        return new SumGrossCalculatedDiscountAmountCalculator();
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
