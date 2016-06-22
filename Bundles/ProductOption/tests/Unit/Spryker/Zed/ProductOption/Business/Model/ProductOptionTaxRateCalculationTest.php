<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Tax\Business\Model;

use Functional\Spryker\Zed\ProductOption\Mock\ProductOptionQueryContainer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductOption\Business\Model\ProductOptionTaxRateCalculator;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxBridge;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use Spryker\Zed\Tax\Business\TaxFacade;

/**
 * @group ProductOptionTaxRate
 */
class ProductOptionTaxRateCalculationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCalculateTaxRateForDefaultCountry()
    {
        $quoteTransfer = $this->createQuoteTransferWithoutShippingAddress();

        $taxAverage = $this->getEffectiveTaxRateByQuoteTransfer($quoteTransfer, $this->getMockDefaultTaxRates());
        $this->assertEquals(15, $taxAverage);
    }

    /**
     * @return void
     */
    public function testCalculateTaxRateForDifferentCountry()
    {
        $quoteTransfer = $this->createQuoteTransferWithShippingAddress();

        $taxAverage = $this->getEffectiveTaxRateByQuoteTransfer($quoteTransfer, $this->getMockCountryBasedTaxRates());
        $this->assertEquals(17, $taxAverage);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return float
     */
    protected function getEffectiveTaxRateByQuoteTransfer(QuoteTransfer $quoteTransfer, $mockData)
    {
        $productItemTaxRateCalculatorMock = $this->createProductItemTaxRateCalculator();
        $productItemTaxRateCalculatorMock->method('findTaxRatesByCountry')->willReturn($mockData);

        $productItemTaxRateCalculatorMock->recalculate($quoteTransfer);
        $taxAverage = $this->getProductItemsTaxRateAverage($quoteTransfer);

        return $taxAverage;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ProductOptionTaxRateCalculator
     */
    protected function createProductItemTaxRateCalculator()
    {
        return $productItemTaxRateCalculatorMock = $this->getMock(ProductOptionTaxRateCalculator::class, ['findTaxRatesByCountry'], [
            $this->createQueryContainerMock(),
            new ProductOptionToTaxBridge(new TaxFacade())
        ]);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ProductOptionQueryContainerInterface
     */
    protected function createQueryContainerMock()
    {
        return $this->getMockBuilder(ProductOptionQueryContainer::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return float
     */
    protected function getProductItemsTaxRateAverage(QuoteTransfer $quoteTransfer)
    {
        $taxSum = 0;
        $productOptionCount = 0;
        foreach ($quoteTransfer->getItems() as $item) {
            $taxSum += $this->getEffectiveProductOptionTaxRate($item);
            $productOptionCount += count($item->getProductOptions());
        }

        $taxAverage = $taxSum / $productOptionCount;

        return $taxAverage;
    }

    /**
     * @return QuoteTransfer
     */
    protected function createQuoteTransferWithoutShippingAddress()
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $this->createItemTransfers($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @return QuoteTransfer
     */
    protected function createQuoteTransferWithShippingAddress()
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $this->createItemTransfers($quoteTransfer);

        $addressTransfer = new AddressTransfer();
        $addressTransfer->setIso2Code('AT');

        $quoteTransfer->setShippingAddress($addressTransfer);

        return $quoteTransfer;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function createItemTransfers(QuoteTransfer $quoteTransfer)
    {
        $itemTransfer1 = $this->createProductItemTransfer(1);
        $itemTransfer1->addProductOption($this->createProductOption(1));
        $quoteTransfer->addItem($itemTransfer1);

        $itemTransfer2 = $this->createProductItemTransfer(2);
        $itemTransfer1->addProductOption($this->createProductOption(2));
        $quoteTransfer->addItem($itemTransfer2);
    }

    /**
     * @param $id
     *
     * @return ItemTransfer
     */
    protected function createProductItemTransfer($id)
    {
        $itemTransfer = $this->createItemTransfer();
        $itemTransfer->setIdProductAbstract($id);

        return $itemTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer()
    {
        return new ItemTransfer();
    }

    /**
     * @return array
     */
    protected function getMockDefaultTaxRates()
    {
        return [
            [
                ProductOptionQueryContainer::COL_ID_PRODUCT_OPTION_TYPE_USAGE => 1,
                ProductOptionQueryContainer::COL_SUM_TAX_RATE => 11,
            ]
        ];
    }

    /**
     * @return array
     */
    protected function getMockCountryBasedTaxRates()
    {
        return [
            [
                ProductOptionQueryContainer::COL_ID_PRODUCT_OPTION_TYPE_USAGE => 1,
                ProductOptionQueryContainer::COL_SUM_TAX_RATE => 20,
            ],
            [
                ProductOptionQueryContainer::COL_ID_PRODUCT_OPTION_TYPE_USAGE => 2,
                ProductOptionQueryContainer::COL_SUM_TAX_RATE => 14,
            ],
        ];
    }

    /**
     * @param int $idOptionValueUsage
     *
     * @return ProductOptionTransfer
     */
    protected function createProductOption($idOptionValueUsage)
    {
        $productOption1 = new ProductOptionTransfer();
        $productOption1->setIdOptionValueUsage($idOptionValueUsage);

        return $productOption1;
    }

    /**
     * @param ItemTransfer $item
     *
     * @return float
     */
    protected function getEffectiveProductOptionTaxRate(ItemTransfer $item)
    {
        $taxSum = 0;
        foreach ($item->getProductOptions() as $productOption) {
            $taxSum += $productOption->getTaxRate();
        }

        return $taxSum;
    }

}
