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
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface;

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
    public function testCalculateTaxRateForDefaultCountry(): void
    {
        $quoteTransfer = $this->createQuoteTransferWithoutShippingAddress();

        $taxAverage = $this->getEffectiveTaxRateByQuoteTransfer($quoteTransfer, $this->getMockDefaultTaxRates());
        $this->assertSame(15, $taxAverage);
    }

    /**
     * @return void
     */
    public function testCalculateTaxRateForDifferentCountry(): void
    {
        $quoteTransfer = $this->createQuoteTransferWithShippingAddress();

        $taxAverage = $this->getEffectiveTaxRateByQuoteTransfer($quoteTransfer, $this->getMockCountryBasedTaxRates());
        $this->assertSame(17, $taxAverage);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $mockData
     *
     * @return float
     */
    protected function getEffectiveTaxRateByQuoteTransfer(QuoteTransfer $quoteTransfer, array $mockData): float
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
    protected function createProductItemTaxRateCalculator(): ProductItemTaxRateCalculator
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
     * @return \Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createTaxFacadeMock(): TaxProductConnectorToTaxInterface
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
    protected function createQueryContainerMock(): TaxProductConnectorQueryContainerInterface
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
    protected function getProductItemsTaxRateAverage(QuoteTransfer $quoteTransfer): float
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
    protected function createQuoteTransferWithoutShippingAddress(): QuoteTransfer
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $this->createItemTransfers($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransferWithShippingAddress(): QuoteTransfer
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
    protected function createItemTransfers(QuoteTransfer $quoteTransfer): void
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
    protected function createProductItemTransfer(int $id): ItemTransfer
    {
        $itemTransfer = $this->createItemTransfer();
        $itemTransfer->setIdProductAbstract($id);

        return $itemTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        return new QuoteTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer(): ItemTransfer
    {
        return new ItemTransfer();
    }

    /**
     * @return array
     */
    protected function getMockDefaultTaxRates(): array
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
    protected function getMockCountryBasedTaxRates(): array
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
