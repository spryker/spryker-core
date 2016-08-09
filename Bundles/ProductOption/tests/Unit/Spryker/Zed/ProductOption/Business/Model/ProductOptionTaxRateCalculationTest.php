<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductOption\Business\Calculator\ProductOptionTaxRateCalculator;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxBridge;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer;

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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return float
     */
    protected function getEffectiveTaxRateByQuoteTransfer(QuoteTransfer $quoteTransfer, $mockData)
    {
        $productItemTaxRateCalculatorMock = $this->createProductItemTaxRateCalculator();
        $productItemTaxRateCalculatorMock->method('findTaxRatesByIdOptionValueAndCountryIso2Code')->willReturn($mockData);

        $productItemTaxRateCalculatorMock->recalculate($quoteTransfer);
        $taxAverage = $this->getProductItemsTaxRateAverage($quoteTransfer);

        return $taxAverage;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Business\Calculator\ProductOptionTaxRateCalculator
     */
    protected function createProductItemTaxRateCalculator()
    {
        return $this->getMock(ProductOptionTaxRateCalculator::class, ['findTaxRatesByIdOptionValueAndCountryIso2Code'], [
            $this->createQueryContainerMock(),
            $this->createProductOptionToTaxBridgeMock()
        ]);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected function createQueryContainerMock()
    {
        return $this->getMockBuilder(ProductOptionQueryContainer::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxBridge
     */
    protected function createProductOptionToTaxBridgeMock()
    {
        $bridgeMock = $this->getMockBuilder(ProductOptionToTaxBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $bridgeMock
            ->expects($this->any())
            ->method('getDefaultTaxCountryIso2Code')
            ->willReturn('DE');

        $bridgeMock
            ->expects($this->any())
            ->method('getDefaultTaxRate')
            ->willReturn(19);

        return $bridgeMock;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransferWithoutShippingAddress()
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $this->createItemTransfers($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
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
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
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
                ProductOptionQueryContainer::COL_ID_PRODUCT_OPTION_VALUE => 1,
                ProductOptionQueryContainer::COL_MAX_TAX_RATE => 11,
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
                ProductOptionQueryContainer::COL_ID_PRODUCT_OPTION_VALUE => 1,
                ProductOptionQueryContainer::COL_MAX_TAX_RATE => 20,
            ],
            [
                ProductOptionQueryContainer::COL_ID_PRODUCT_OPTION_VALUE => 2,
                ProductOptionQueryContainer::COL_MAX_TAX_RATE => 14,
            ],
        ];
    }

    /**
     * @param int $idOptionValueUsage
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    protected function createProductOption($idOptionValueUsage)
    {
        $productOption1 = new ProductOptionTransfer();
        $productOption1->setIdProductOptionValue($idOptionValueUsage);

        return $productOption1;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
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
     * @param \Generated\Shared\Transfer\ItemTransfer $item
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
