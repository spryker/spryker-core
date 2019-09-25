<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxProductConnector\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\TaxProductConnector\Business\Model\ProductItemTaxRateCalculator;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group TaxProductConnector
 * @group Business
 * @group Model
 * @group TaxRateCalculationTest
 * Add your own group annotations below this line
 */
class TaxRateCalculationTest extends Unit
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
     * @param array $mockData
     *
     * @return float
     */
    protected function getEffectiveTaxRateByQuoteTransfer(QuoteTransfer $quoteTransfer, $mockData)
    {
        $productItemTaxRateCalculatorMock = $this->createProductItemTaxRateCalculator();
        $productItemTaxRateCalculatorMock->method('findTaxRatesByAllIdProductAbstractsAndCountryIso2Code')->willReturn($mockData);

        $productItemTaxRateCalculatorMock->recalculate($quoteTransfer);
        $taxAverage = $this->getProductItemsTaxRateAverage($quoteTransfer);

        return $taxAverage;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\TaxProductConnector\Business\Model\ProductItemTaxRateCalculator
     */
    protected function createProductItemTaxRateCalculator()
    {
        return $this->getMockBuilder(ProductItemTaxRateCalculator::class)
            ->setMethods(['findTaxRatesByAllIdProductAbstractsAndCountryIso2Code'])
            ->setConstructorArgs([
                $this->createQueryContainerMock(),
                $this->createTaxFacadeMock(),
            ])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Tax\Business\Model\TaxDefault
     */
    protected function createTaxFacadeMock()
    {
        $taxDefaultMock = $this->getMockBuilder(TaxProductConnectorToTaxInterface::class)
            ->getMock();

        $taxDefaultMock
            ->expects($this->any())
            ->method('getDefaultTaxCountryIso2Code')
            ->willReturn('DE');

        $taxDefaultMock
            ->expects($this->any())
            ->method('getDefaultTaxRate')
            ->willReturn(19);

        return $taxDefaultMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface
     */
    protected function createQueryContainerMock()
    {
        return $this->getMockBuilder(TaxProductConnectorQueryContainer::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return float
     */
    protected function getProductItemsTaxRateAverage(QuoteTransfer $quoteTransfer)
    {
        $taxSum = 0;
        foreach ($quoteTransfer->getItems() as $item) {
            $taxSum += $item->getTaxRate();
        }

        $taxAverage = $taxSum / count($quoteTransfer->getItems());

        return $taxAverage;
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
        $quoteTransfer->addItem($itemTransfer1);

        $itemTransfer2 = $this->createProductItemTransfer(2);
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
                TaxProductConnectorQueryContainer::COL_ID_ABSTRACT_PRODUCT => 1,
                TaxProductConnectorQueryContainer::COL_MAX_TAX_RATE => 11,
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getMockCountryBasedTaxRates()
    {
        return [
            [
                TaxProductConnectorQueryContainer::COL_ID_ABSTRACT_PRODUCT => 1,
                TaxProductConnectorQueryContainer::COL_MAX_TAX_RATE => 20,
            ],
            [
                TaxProductConnectorQueryContainer::COL_ID_ABSTRACT_PRODUCT => 2,
                TaxProductConnectorQueryContainer::COL_MAX_TAX_RATE => 14,
            ],
        ];
    }
}
