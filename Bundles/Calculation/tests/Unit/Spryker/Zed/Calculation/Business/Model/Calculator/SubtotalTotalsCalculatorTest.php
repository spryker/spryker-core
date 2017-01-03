<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator;
use Spryker\Zed\Kernel\Locator;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Calculation
 * @group Business
 * @group Model
 * @group Calculator
 * @group SubtotalTotalsCalculatorTest
 */
class SubtotalTotalsCalculatorTest extends PHPUnit_Framework_TestCase
{

    const ITEM_GROSS_PRICE = 10000;
    const ITEM_OPTION_GROSS_PRICE = 1000;

    /**
     * @return void
     */
    public function testSubtotalShouldBeMoreThanZeroForAnOrderWithOneItem()
    {
        $quoteTransfer = $this->getQuoteTransferWithFixtureData();

        $item = $this->getItemWithFixtureData();
        $item->setQuantity(1);
        $item->setUnitGrossPrice(self::ITEM_GROSS_PRICE);
        $item->setSumGrossPriceWithProductOptions(self::ITEM_GROSS_PRICE + self::ITEM_OPTION_GROSS_PRICE);
        $quoteTransfer->addItem($item);

        $calculator = new SubtotalTotalsCalculator(Locator::getInstance());
        $calculator->recalculate($quoteTransfer);
        $this->assertEquals(self::ITEM_GROSS_PRICE + self::ITEM_OPTION_GROSS_PRICE, $quoteTransfer->getTotals()->getSubtotal());
    }

    /**
     * @return void
     */
    public function testSubtotalShouldReturnTwiceTheItemGrossPriceForAnOrderWithTwoItems()
    {
        $quoteTransfer = $this->getQuoteTransferWithFixtureData();

        $item = $this->getItemWithFixtureData();

        $item->setUnitGrossPrice(self::ITEM_GROSS_PRICE);
        $item->setQuantity(1);
        $item->setSumGrossPriceWithProductOptions(self::ITEM_GROSS_PRICE + self::ITEM_OPTION_GROSS_PRICE);
        $quoteTransfer->addItem($item);
        $quoteTransfer->addItem(clone $item);

        $calculator = new SubtotalTotalsCalculator(Locator::getInstance());
        $calculator->recalculate($quoteTransfer);
        $this->assertEquals(
            2 * (self::ITEM_GROSS_PRICE + self::ITEM_OPTION_GROSS_PRICE),
            $quoteTransfer->getTotals()->getSubtotal()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuoteTransferWithFixtureData()
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setTotals(new TotalsTransfer());

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getItemWithFixtureData()
    {
        $item = new ItemTransfer();

        return $item;
    }

    /**
     * @return \Spryker\Shared\Kernel\AbstractLocatorLocator|\Generated\Zed\Ide\AutoCompletion
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

}
