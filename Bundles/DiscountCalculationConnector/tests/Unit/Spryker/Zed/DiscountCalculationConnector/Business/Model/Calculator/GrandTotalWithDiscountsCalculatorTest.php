<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Shared\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsCalculator;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group DiscountCalculationConnector
 * @group Business
 * @group Model
 * @group Calculator
 * @group GrandTotalWithDiscountsCalculatorTest
 */
class GrandTotalWithDiscountsCalculatorTest extends PHPUnit_Framework_TestCase
{

    const GRAND_TOTAL_BEFORE_DISCOUNTS = 500;
    const DISCOUNT_AMOUNT = 100;
    const DISCOUNT_OVER_AMOUNT = 600;

    /**
     * @return void
     */
    public function testGrandTotalWithDiscountsWhenDiscountsPresentShouldBeSubtracted()
    {
        $quoteTransfer = $this->createQuoteTransferWithFixtureData(
            self::GRAND_TOTAL_BEFORE_DISCOUNTS,
            self::DISCOUNT_AMOUNT
        );

        $grandTotalWithDiscountsCalculator = $this->createGrandTotalWithDiscountsCalculator();
        $grandTotalWithDiscountsCalculator->recalculate($quoteTransfer);

        $this->assertSame(
            self::GRAND_TOTAL_BEFORE_DISCOUNTS - self::DISCOUNT_AMOUNT,
            $quoteTransfer->getTotals()->getGrandTotal()
        );
    }

    /**
     * @return void
     */
    public function testGrandTotalWithDiscountsWhenDiscountBiggerShouldUseZero()
    {
        $quoteTransfer = $this->createQuoteTransferWithFixtureData(
            self::GRAND_TOTAL_BEFORE_DISCOUNTS,
            self::DISCOUNT_OVER_AMOUNT
        );

        $grandTotalWithDiscountsCalculator = $this->createGrandTotalWithDiscountsCalculator();
        $grandTotalWithDiscountsCalculator->recalculate($quoteTransfer);

        $this->assertSame(0, $quoteTransfer->getTotals()->getGrandTotal());
    }

    /**
     * @return void
     */
    public function testGrandTotalWithDiscountsWhenTotalsNotPresentShouldThrowAssertException()
    {
        $this->expectException(RequiredTransferPropertyException::class);

        $quoteTransfer = $this->createQuoteTransfer();

        $grandTotalWithDiscountsCalculator = $this->createGrandTotalWithDiscountsCalculator();
        $grandTotalWithDiscountsCalculator->recalculate($quoteTransfer);
    }

    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator\GrandTotalWithDiscountsCalculator
     */
    protected function createGrandTotalWithDiscountsCalculator()
    {
        return new GrandTotalWithDiscountsCalculator();
    }

    /**
     * @param int $grandTotalBeforeDiscounts
     * @param int $discountAmount
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransferWithFixtureData($grandTotalBeforeDiscounts, $discountAmount)
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $totalsTransfer = $this->createTotalsTransfer();
        $totalsTransfer->setGrandTotal($grandTotalBeforeDiscounts);

        $totalsTransfer->setDiscountTotal($discountAmount);

        $quoteTransfer->setTotals($totalsTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function createTotalsTransfer()
    {
        return new TotalsTransfer();
    }

}
