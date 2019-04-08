<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxProductConnector\Business\Facade\ProductItemTaxRateCalculation;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Product\ProductDependencyProvider;
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
 * @group ProductItemTaxRateCalculatorForQuoteLevelShippingAddressTest
 * Add your own group annotations below this line
 */
class ProductItemTaxRateCalculatorForQuoteLevelShippingAddressTest extends Test
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
            TaxProductConnectorDependencyProvider::FACADE_TAX,
            $this->createTaxProductConnectorToTaxFacadeBridgeMock('FOO', 66.00)
        );

        $this->tester->setDependency(
            ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_AFTER_CREATE,
            [new TaxSetProductAbstractAfterCreatePlugin()]
        );

        $this->taxSetTransferList = [];
        $this->taxSetTransferList['FR'] = $this->tester->createTaxRateWithTaxSetInDb(20.00, 'FR');
        $this->taxSetTransferList['DE'] = $this->tester->createTaxRateWithTaxSetInDb(15.00, 'DE');
    }

    /**
     * @dataProvider taxRateCalculationShouldUseQuoteShippingAddressDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $expectedValues
     *
     * @return void
     */
    public function testTaxRateCalculationShouldUseQuoteShippingAddress(
        QuoteTransfer $quoteTransfer,
        array $expectedValues
    ): void {
        // Arrange
        $taxSetTransfer = $this->tester->findTaxSetByAddressIso2CodeInTaxSetTransferList(
            $quoteTransfer->getShippingAddress()->getIso2Code(),
            $this->taxSetTransferList
        );

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productAbstractTransfer = $this->tester->createProductWithTaxSetInDb($taxSetTransfer);
            $itemTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
        }

        // Act
        $this->tester->getFacade()->calculateProductItemTaxRate($quoteTransfer);

        // Assert
        foreach ($quoteTransfer->getItems() as $i => $itemTransfer) {
            $expectedTaxRate = $expectedValues[$itemTransfer->getSku()];
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
    public function taxRateCalculationShouldUseQuoteShippingAddressDataProvider(): array
    {
        return [
            'with quote level shipping address: France, expected tax rate 20%' => $this->getDataWithQuoteLevelShippingAddressToFrance(),
            'with quote level shipping address: Moon, expected tax rate 66%' => $this->getDataWithQuoteLevelShippingAddressToMoon(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShippingAddressToFrance(): array
    {
        $addressBuilder = (new AddressBuilder([AddressTransfer::ISO2_CODE => 'FR']));

        $itemTransfer = (new ItemBuilder())->build();

        $quoteTransfer = (new QuoteBuilder())->withShippingAddress($addressBuilder)->build();
        $quoteTransfer->addItem($itemTransfer);

        return [$quoteTransfer, [$itemTransfer->getSku() => 20.00]];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShippingAddressToMoon(): array
    {
        $addressBuilder = (new AddressBuilder([AddressTransfer::ISO2_CODE => 'MOON']));

        $itemTransfer = (new ItemBuilder())->build();

        $quoteTransfer = (new QuoteBuilder())->withShippingAddress($addressBuilder)->build();
        $quoteTransfer->addItem($itemTransfer);

        return [$quoteTransfer, [$itemTransfer->getSku() => 66.00]];
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
}
