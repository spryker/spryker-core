<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\ItemGrossAmountsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ProductOptionGrossSumCalculator;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Calculation
 * @group Business
 * @group Model
 * @group Calculator
 * @group ProductOptionGrossSumCalculatorTest
 */
class ProductOptionGrossSumCalculatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCalculateProductOptionSumShouldMultiplWithQuantity()
    {
        $productOptionGrossSumCalculator = $this->createProductOptionGrossSumCalculator();

        $productOptionFixtures = [
            [
                'unitGrossPrice' => 100,
                'quantity' => 2,
            ],
        ];

        $quoteTransfer = $this->createQuoteTransferWithFixtureData($productOptionFixtures);

        $productOptionGrossSumCalculator->recalculate($quoteTransfer);

        $productOptionSumGross = $quoteTransfer->getItems()[0]->getProductOptions()[0]->getSumGrossPrice();

        $expectedProductOptionSum = array_reduce($productOptionFixtures, function ($carry, $item) {
            $carry += $item['unitGrossPrice'] * $item['quantity'];

            return $carry;
        });

        $this->assertEquals($expectedProductOptionSum, $productOptionSumGross);
    }

    /**
     * @param array $productOptions
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransferWithFixtureData(array $productOptions = [])
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $itemTransfer = $this->createItemTransfer();
        $itemTransfer->setQuantity(1);
        $itemTransfer->setSumGrossPrice(100);
        foreach ($productOptions as $productOption) {
            $productOptionTransfer = $this->createProductOptionTransfer();
            $productOptionTransfer->setSumGrossPrice($productOption['unitGrossPrice'] * $productOption['quantity']);
            $productOptionTransfer->setUnitGrossPrice($productOption['unitGrossPrice']);
            $productOptionTransfer->setQuantity($productOption['quantity']);
            $itemTransfer->addProductOption($productOptionTransfer);
        }

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
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ItemGrossAmountsCalculator
     */
    protected function createItemGrossAmountsCalculator()
    {
        return new ItemGrossAmountsCalculator();
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

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ProductOptionGrossSumCalculator
     */
    protected function createProductOptionGrossSumCalculator()
    {
        return new ProductOptionGrossSumCalculator();
    }

}
