<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxProductConnector\Business\Facade\ProductItemTaxRateCalculation;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Product\ProductDependencyProvider;
use Spryker\Zed\Tax\TaxDependencyProvider;
use Spryker\Zed\TaxProductConnector\Communication\Plugin\TaxSetProductAbstractAfterCreatePlugin;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxBridge;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface;
use Spryker\Zed\TaxProductConnector\TaxProductConnectorDependencyProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group TaxProductConnector
 * @group Business
 * @group Facade
 * @group ProductItemTaxRateCalculation
 * @group ProductItemTaxRateCalculatorForDefaultTaxRatesTest
 * Add your own group annotations below this line
 */
class ProductItemTaxRateCalculatorForDefaultTaxRatesTest extends Test
{
    protected const FLOAT_COMPARISION_DELTA = 0.001;

    /**
     * @var \SprykerTest\Zed\TaxProductConnector\TaxProductConnectorBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\TaxSetTransfer[]
     */
    protected $taxSetTransferList;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->tester->setDependency(
            ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_AFTER_CREATE,
            [new TaxSetProductAbstractAfterCreatePlugin()]
        );

        $this->taxSetTransferList = [];
        $this->taxSetTransferList['FR'] = $this->tester->createTaxRateWithTaxSetInDb(20.00, 'FR');
        $this->taxSetTransferList['DE'] = $this->tester->createTaxRateWithTaxSetInDb(15.00, 'DE');
    }

    /**
     * @dataProvider taxRateCalculationShouldBeDefaultDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $defaultCountryIso2Code
     * @param float $expectedTaxRate
     *
     * @return void
     */
    public function testTaxRateCalculationWithoutAnyShippingAddressShouldUseDefaultCountrysTaxRateForAllItems(
        QuoteTransfer $quoteTransfer,
        string $defaultCountryIso2Code,
        float $expectedTaxRate
    ): void {
        // Arrange
        $taxSetTransfer = $this->tester->findTaxSetByAddressIso2CodeInTaxSetTransferList(
            $defaultCountryIso2Code,
            $this->taxSetTransferList
        );

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productAbstractTransfer = $this->tester->createProductWithTaxSetInDb($taxSetTransfer);
            $itemTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
        }

        $this->tester->setDependency(
            TaxProductConnectorDependencyProvider::FACADE_TAX,
            $this->createTaxProductConnectorToTaxFacadeBridgeMock($defaultCountryIso2Code, 66.00)
        );

        $this->tester->setDependency(
            TaxDependencyProvider::STORE_CONFIG,
            $this->createStoreMockWithCustomCountry($defaultCountryIso2Code)
        );

        // Act
        $this->tester->getFacade()->calculateProductItemTaxRate($quoteTransfer);

        // Assert
        foreach ($quoteTransfer->getItems() as $i => $itemTransfer) {
            $actualTaxRate = $itemTransfer->getTaxRate();

            $this->assertEqualsWithDelta(
                $expectedTaxRate,
                $actualTaxRate,
                static::FLOAT_COMPARISION_DELTA,
                sprintf('Tax rate should be %.2f, %.2f given at iteration #%d.', $expectedTaxRate, $actualTaxRate, $i)
            );
        }
    }

    /**
     * @return array
     */
    public function taxRateCalculationShouldBeDefaultDataProvider(): array
    {
        return [
            'without quote and item level shipping addresses: France expected tax rate 20%' => $this->getDataWithoutQuoteAndItemLevelShippingAddressesToFrance(),
            'without quote and item level shipping addresses: Germany expected tax rate 15%' => $this->getDataWithoutQuoteAndItemLevelShippingAddressesToGermany(),
            'without quote and item level shipping addresses: Moon expected tax rate 0%' => $this->getDataWithoutQuoteAndItemLevelShippingAddressesToMoon(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithoutQuoteAndItemLevelShippingAddressesToFrance(): array
    {
        $quoteTransfer = (new QuoteBuilder())->withItem()->build();

        return [$quoteTransfer, 'FR', 20.00];
    }

    /**
     * @return array
     */
    protected function getDataWithoutQuoteAndItemLevelShippingAddressesToGermany(): array
    {
        $quoteTransfer = (new QuoteBuilder())->withItem()->build();

        return [$quoteTransfer, 'DE', 15.00];
    }

    /**
     * @return array
     */
    protected function getDataWithoutQuoteAndItemLevelShippingAddressesToMoon(): array
    {
        $quoteTransfer = (new QuoteBuilder())->withItem()->build();

        return [$quoteTransfer, 'MOON', 66.00];
    }

    /**
     * @param string $defaultCountryIso2Code
     * @param float $defaultTaxRate
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface
     */
    protected function createTaxProductConnectorToTaxFacadeBridgeMock(string $defaultCountryIso2Code, float $defaultTaxRate): TaxProductConnectorToTaxInterface
    {
        $bridgeMock = $this->getMockBuilder(TaxProductConnectorToTaxBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $bridgeMock
            ->expects($this->any())
            ->method('getDefaultTaxCountryIso2Code')
            ->willReturn($defaultCountryIso2Code);

        $bridgeMock
            ->expects($this->any())
            ->method('getDefaultTaxRate')
            ->willReturn($defaultTaxRate);

        return $bridgeMock;
    }

    /**
     * @param string $defaultCountryIso2Code
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Kernel\Store
     */
    protected function createStoreMockWithCustomCountry(string $defaultCountryIso2Code): Store
    {
        $storeMock = $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->getMock();

        $storeMock
            ->expects($this->any())
            ->method('getCurrentCountry')
            ->willReturn($defaultCountryIso2Code);

        return $storeMock;
    }
}
