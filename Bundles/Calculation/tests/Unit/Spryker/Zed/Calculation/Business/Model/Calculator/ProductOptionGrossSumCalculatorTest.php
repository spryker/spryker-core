<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\ItemGrossAmountsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ProductOptionGrossSumCalculator;

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
                'quantity' => 2
            ]
        ];

        $quoteTransfer = $this->createQuoteTransferWithFixtureData($productOptionFixtures);

        $productOptionGrossSumCalculator->recalculate($quoteTransfer);

        $productOptionSumGross = $quoteTransfer->getItems()[0]->getProductOptions()[0]->getSumGrossPrice();

        $expectedProductOptionSum = array_reduce($productOptionFixtures, function($carry, $item) {
            $carry += $item['unitGrossPrice'] * $item['quantity'];
            return $carry;
        });

        $this->assertEquals($expectedProductOptionSum, $productOptionSumGross);
    }
    
    /**
     * @param array $productOptions
     *
     * @return QuoteTransfer
     */
    protected function createQuoteTransferWithFixtureData(array $productOptions = [])
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $itemTransfer = $this->createItemTransfer();
        foreach ($productOptions as $productOption) {
            $productOptionTransfer = $this->createProductOptionTransfer();
            $productOptionTransfer->setUnitGrossPrice($productOption['unitGrossPrice']);
            $productOptionTransfer->setQuantity($productOption['quantity']);
            $itemTransfer->addProductOption($productOptionTransfer);
        }

        $quoteTransfer->addItem($itemTransfer);

        return $quoteTransfer;
    }
    /**
     * @return QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

    /**
     * @return ItemGrossAmountsCalculator
     */
    protected function createItemGrossAmountsCalculator()
    {
        return new ItemGrossAmountsCalculator();
    }

    /**
     * @return ItemTransfer
     */
    protected function createItemTransfer()
    {
        return new ItemTransfer();
    }

    /**
     * @return ProductOptionTransfer
     */
    protected function createProductOptionTransfer()
    {
        return new ProductOptionTransfer();
    }

    /**
     * @return ProductOptionGrossSumCalculator
     */
    protected function createProductOptionGrossSumCalculator()
    {
        return new ProductOptionGrossSumCalculator();
    }
}
