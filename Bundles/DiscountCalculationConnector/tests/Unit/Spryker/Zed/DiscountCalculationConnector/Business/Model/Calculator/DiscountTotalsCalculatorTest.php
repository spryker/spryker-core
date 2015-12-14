<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\DiscountCalculationConnector\Business\Model\Calculator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
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
    const ITEM_GROSS_PRICE_WITH_OPTIONS = 500;
    const UNIT_GROSS_AMOUNT = 10;
    const ITEM_GROSS_UNIT_PRICE_WITH_OPTIONS = 250;

    /**
     * @return void
     */
    public function testCalculateTotalsShouldSumAllDiscounts()
    {
        $discountTotalsCalculator = $this->createDiscountTotalsCalculator();

        $calculatedDiscountFixtures = $this->getCalculatedDiscountFixtures();

        $quoteTransfer = $this->createQuoteTransferWithFixtureData(
            self::ITEM_QUANTITY,
            self::ITEM_GROSS_PRICE_WITH_OPTIONS,
            self::ITEM_GROSS_UNIT_PRICE_WITH_OPTIONS,
            $calculatedDiscountFixtures
        );

        $discountTotalsCalculator->recalculate($quoteTransfer);

        $this->assertEquals(120, $quoteTransfer->getTotals()->getDiscount()->getTotalAmount());

    }

    /**
     * @return void
     */
    public function testCalculateTotalsShouldUpdateItemDiscountGrossSumAmounts()
    {
        $discountTotalsCalculator = $this->createDiscountTotalsCalculator();

        $calculatedDiscountFixtures = $this->getCalculatedDiscountFixtures();

        $quoteTransfer = $this->createQuoteTransferWithFixtureData(
            self::ITEM_QUANTITY,
            self::ITEM_GROSS_PRICE_WITH_OPTIONS,
            self::ITEM_GROSS_UNIT_PRICE_WITH_OPTIONS,
            $calculatedDiscountFixtures
        );

        $discountTotalsCalculator->recalculate($quoteTransfer);

        $itemTransfer = $quoteTransfer->getItems()[0];

        $expectedItemDiscountAmount = 0;
        foreach ($itemTransfer->getCalculatedDiscounts() as $calculatedDiscountTransfer) {
            $expectedItemDiscountAmount += $calculatedDiscountTransfer->getSumGrossAmount();
        }

        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            foreach ($productOptionTransfer->getCalculatedDiscounts() as $calculatedOptionDiscountTransfer) {
                $expectedItemDiscountAmount += $calculatedOptionDiscountTransfer->getSumGrossAmount();
            }
        }

        $this->assertEquals(
            self::ITEM_GROSS_PRICE_WITH_OPTIONS - $expectedItemDiscountAmount,
            $itemTransfer->getSumGrossPriceWithProductOptionAndDiscountAmounts()
        );
    }

    /**
     * @return void
     */
    public function testCalculateTotalsShouldUpdateItemDiscountGrossUnitAmounts()
    {
        $discountTotalsCalculator = $this->createDiscountTotalsCalculator();

        $calculatedDiscountFixtures = $this->getCalculatedDiscountFixtures();

        $quoteTransfer = $this->createQuoteTransferWithFixtureData(
            self::ITEM_QUANTITY,
            self::ITEM_GROSS_PRICE_WITH_OPTIONS,
            self::ITEM_GROSS_UNIT_PRICE_WITH_OPTIONS,
            $calculatedDiscountFixtures
        );

        $discountTotalsCalculator->recalculate($quoteTransfer);

        $itemTransfer = $quoteTransfer->getItems()[0];

        $expectedItemDiscountAmount = 0;
        foreach ($itemTransfer->getCalculatedDiscounts() as $calculatedDiscountTransfer) {
            $expectedItemDiscountAmount += $calculatedDiscountTransfer->getUnitGrossAmount();
        }

        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            foreach ($productOptionTransfer->getCalculatedDiscounts() as $calculatedOptionDiscountTransfer) {
                $expectedItemDiscountAmount += $calculatedOptionDiscountTransfer->getUnitGrossAmount();
            }
        }

        $this->assertEquals(
            self::ITEM_GROSS_UNIT_PRICE_WITH_OPTIONS - $expectedItemDiscountAmount,
            $itemTransfer->getUnitGrossPriceWithProductOptionAndDiscountAmounts()
        );
    }


    /**
     * @param int $itemQuantity
     * @param int $itemGrossSumPriceWithOptions
     * @param array $calculatedDiscounts
     *
     * @return QuoteTransfer
     */
    protected function createQuoteTransferWithFixtureData(
        $itemQuantity,
        $itemGrossSumPriceWithOptions,
        $itemGrossUnitPriceWithOptions,
        array $calculatedDiscounts
    ) {
        $quoteTransfer = $this->createQuoteTransfer();

        $quoteTransfer->setTotals($this->createTotalsTransfer());

        $itemTransfer = $this->createItemTransfer();
        $itemTransfer->setQuantity($itemQuantity);
        $itemTransfer->setUnitGrossPriceWithProductOptions($itemGrossUnitPriceWithOptions);
        $itemTransfer->setSumGrossPriceWithProductOptions($itemGrossSumPriceWithOptions);
        $productOptionTransfer = $this->createProductOptionTransfer();
        $itemTransfer->addProductOption($productOptionTransfer);

        foreach ($calculatedDiscounts as $calculatedDiscount) {
            $calculatedDiscountTransfer = $this->createCalculatedDiscountTransfer();
            $calculatedDiscountTransfer->setQuantity($calculatedDiscount['quantity']);
            $calculatedDiscountTransfer->setUnitGrossAmount($calculatedDiscount['unitGrossAmount']);
            $itemTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

            foreach ($itemTransfer->getProductOptions() as $productOption) {
                $productOption->addCalculatedDiscount(clone $calculatedDiscountTransfer);
            }

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
                'unitGrossAmount' => 10
            ],
            [
                'quantity' => 2,
                'unitGrossAmount' => 10
            ]
        ];
    }

    /**
     * @return TotalsTransfer
     */
    protected function createTotalsTransfer()
    {
        return new TotalsTransfer();
    }

    /**
     * @return QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

    /**
     * @return DiscountTotalsCalculator
     */
    protected function createDiscountTotalsCalculator()
    {
        return new DiscountTotalsCalculator();
    }

    /**
     * @return ItemTransfer
     */
    protected function createItemTransfer()
    {
        return new ItemTransfer();
    }

    /**
     * @return CalculatedDiscountTransfer
     */
    protected function createCalculatedDiscountTransfer()
    {
        return new CalculatedDiscountTransfer();
    }

    /**
     * @return ProductOptionTransfer
     */
    protected function createProductOptionTransfer()
    {
        return new ProductOptionTransfer();
    }

    /**
     * @return ExpenseTransfer
     */
    protected function createExpenseTransfer()
    {
        return new ExpenseTransfer();
    }
}
