<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Calculation\Business\Model\Calculator\ItemGrossSumCalculator;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Calculation
 * @group Business
 * @group Model
 * @group Calculator
 * @group ItemGrossAmountsCalculatorTest
 */
class ItemGrossAmountsCalculatorTest extends PHPUnit_Framework_TestCase
{

    const UNIT_GROSS_PRICE = 100;
    const QUANTITY = 2;

    /**
     * @return void
     */
    public function testCalculatorWhenOnlyItemAmountsPresentShouldCalculateItemGrossSum()
    {
        $itemGrossAmountsCalculator = $this->createItemGrossAmountsCalculator();

        $quoteTransfer = $this->createQuoteTransferWithFixtureData(self::UNIT_GROSS_PRICE, self::QUANTITY);
        $itemGrossAmountsCalculator->recalculate($quoteTransfer);
        $calculatedItemSumGrossPrice = $quoteTransfer->getItems()[0]->getSumGrossPrice();

        $this->assertEquals(self::UNIT_GROSS_PRICE * self::QUANTITY, $calculatedItemSumGrossPrice);
    }

    /**
     * @return void
     */
    public function testWhenItemQuantityIsNotPresentShouldThrowAssertException()
    {
        $this->expectException(RequiredTransferPropertyException::class);

        $itemGrossAmountsCalculator = $this->createItemGrossAmountsCalculator();

        $quoteTransfer = $this->createQuoteTransferWithFixtureData(self::UNIT_GROSS_PRICE, null);
        $itemGrossAmountsCalculator->recalculate($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testWhenItemUnitPriceIsNotPresentShouldThrowAssertException()
    {
        $this->expectException(RequiredTransferPropertyException::class);

        $itemGrossAmountsCalculator = $this->createItemGrossAmountsCalculator();

        $quoteTransfer = $this->createQuoteTransferWithFixtureData(null, self::QUANTITY);
        $itemGrossAmountsCalculator->recalculate($quoteTransfer);
    }

    /**
     * @param int $unitGrossPrice
     * @param int $itemQuantity
     * @param array $productOptions
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransferWithFixtureData($unitGrossPrice, $itemQuantity, array $productOptions = [])
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $itemTransfer = $this->createItemTransfer();
        $itemTransfer->setUnitGrossPrice($unitGrossPrice);
        $itemTransfer->setQuantity($itemQuantity);
        $quoteTransfer->addItem($itemTransfer);

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
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ItemGrossSumCalculator
     */
    protected function createItemGrossAmountsCalculator()
    {
        return new ItemGrossSumCalculator();
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer()
    {
        return new ItemTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    protected function createProductOptionTransfer()
    {
        return new ProductOptionTransfer();
    }

}
