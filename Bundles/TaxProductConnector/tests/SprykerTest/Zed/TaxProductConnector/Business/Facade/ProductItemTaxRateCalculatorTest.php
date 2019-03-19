<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxProductConnector\Business\Facade;

use BadMethodCallException;
use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\SequenceNumberSettingsBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorBusinessFactory;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxBridge;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group TaxProductConnector
 * @group Business
 * @group Facade
 * @group ProductItemTaxRateCalculatorTest
 * Add your own group annotations below this line
 */
class ProductItemTaxRateCalculatorTest extends Test
{
    protected const FLOAT_COMPARISION_DELTA = 0.001;

    protected const DEFAULT_TAX_COUNTRY_ISO_2_CODE = 'MOON';

    /**
     * @var \SprykerTest\Zed\TaxProductConnector\TaxProductConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

    }

    /**
     * @dataProvider taxRateCalculationShouldUseQuoteShippingAddressDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $defaultCountryIso2Code
     * @param float $defaultTaxRate
     * @param array $expectedValues
     *
     * @return void
     */
    public function testTaxRateCalculationShouldUseQuoteShippingAddress(
        QuoteTransfer $quoteTransfer,
        string $defaultCountryIso2Code,
        float $defaultTaxRate,
        array $expectedValues
    ): void {
        // Arrange
        $taxProductConnectorFacade = $this->tester->getTaxProductConnectorFacadeWithMockedFactory(
            $this->createTaxProductConnectorBusinessFactory(),
            $this->createTaxProductConnectorToTaxFacadeBridgeMock($defaultCountryIso2Code, $defaultTaxRate)
        );

        // Act
        $taxProductConnectorFacade->calculateProductItemTaxRate($quoteTransfer);

        // Assert
        $this->assertItemsForTaxes($quoteTransfer, $expectedValues);
    }

    /**
     * @dataProvider taxRateCalculationShouldUseItemShippingAddressDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $defaultCountryIso2Code
     * @param float $defaultTaxRate
     * @param array $expectedValues
     *
     * @return void
     */
    public function testTaxRateCalculationShouldUseItemShippingAddress(
        QuoteTransfer $quoteTransfer,
        string $defaultCountryIso2Code,
        float $defaultTaxRate,
        array $expectedValues
    ): void {
        // Arrange
        $taxProductConnectorFacade = $this->tester->getTaxProductConnectorFacadeWithMockedFactory(
            $this->createTaxProductConnectorBusinessFactory(),
            $this->createTaxProductConnectorToTaxFacadeBridgeMock($defaultCountryIso2Code, $defaultTaxRate)
        );

        // Act
        $taxProductConnectorFacade->calculateProductItemTaxRate($quoteTransfer);

        // Assert
        $this->assertItemsForTaxes($quoteTransfer, $expectedValues);
    }

    /**
     * @dataProvider taxRateCalculationShouldBeDefaultDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $defaultCountryIso2Code
     * @param float $defaultTaxRate
     * @param array $expectedValues
     *
     * @return void
     */
    public function testTaxRateCalculationShouldBeDefault(
        QuoteTransfer $quoteTransfer,
        string $defaultCountryIso2Code,
        float $defaultTaxRate,
        array $expectedValues
    ): void {
        // Arrange
        $taxProductConnectorFacade = $this->tester->getTaxProductConnectorFacadeWithMockedFactory(
            $this->createTaxProductConnectorBusinessFactory(),
            $this->createTaxProductConnectorToTaxFacadeBridgeMock($defaultCountryIso2Code, $defaultTaxRate)
        );

        // Act
        $taxProductConnectorFacade->calculateProductItemTaxRate($quoteTransfer);

        // Assert
        $this->assertItemsForTaxes($quoteTransfer, $expectedValues);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $expectedValues
     *
     * @return void
     */
    protected function assertItemsForTaxes(QuoteTransfer $quoteTransfer, array $expectedValues): void
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!isset($expectedValues[$itemTransfer->getIdProductAbstract()])) {
                continue;
            }

            $expectedTaxRate = $expectedValues[$itemTransfer->getIdProductAbstract()];
            $this->assertEqualsWithDelta($expectedTaxRate, $itemTransfer->getTaxRate(), static::FLOAT_COMPARISION_DELTA,
                'tax rate should be ' . $expectedTaxRate . ' for product ID ' . $itemTransfer->getIdProductAbstract()
                . ', ' . $itemTransfer->getTaxRate() . ' given.'
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
    public function taxRateCalculationShouldUseItemShippingAddressDataProvider(): array
    {
        return [
            'with item level shipping addresses: France expected tax rate 20%, Germany expected tax rate 15%' => $this->getDataWithItemLevelShippingAddressesToFranceAndGermany(),
        ];
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
    protected function getDataWithQuoteLevelShippingAddressToFrance(): array
    {
        $defaultCountryIso2Code = static::DEFAULT_TAX_COUNTRY_ISO_2_CODE;
        $defaultTaxRate = 66.00;

        $idProductAbstract = 141;
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));
        $itemBuilder = $this->createItemTransferBuilder([
            'unitNetPrice' => 10000,
            'sumNetPrice' => 10000,
            'idProductAbstract' => $idProductAbstract,
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, [$idProductAbstract => 20.00]];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShippingAddressToMoon(): array
    {
        $defaultCountryIso2Code = static::DEFAULT_TAX_COUNTRY_ISO_2_CODE;
        $defaultTaxRate = 66.00;

        $idProductAbstract = 141;
        $addressBuilder = (new AddressBuilder(['iso2Code' => $defaultCountryIso2Code]));
        $itemBuilder = $this->createItemTransferBuilder([
            'unitGrossPrice' => 10000,
            'sumGrossPrice' => 10000,
            'idProductAbstract' => $idProductAbstract,
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, [$idProductAbstract => $defaultTaxRate]];
    }

    /**
     * @return array
     */
    protected function getDataWithItemLevelShippingAddressesToFranceAndGermany(): array
    {
        $defaultCountryIso2Code = static::DEFAULT_TAX_COUNTRY_ISO_2_CODE;
        $defaultTaxRate = 66.00;

        $idProductAbstract1 = 141;
        $addressBuilder1 = (new AddressBuilder(['iso2Code' => 'FR']));
        $itemBuilder1 = $this->createItemTransferBuilder([
            'unitNetPrice' => 10000,
            'sumNetPrice' => 10000,
            'idProductAbstract' => $idProductAbstract1,
        ])
        ->withAnotherShipment(
            (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder1)
        );

        $idProductAbstract2 = 142;
        $addressBuilder2 = (new AddressBuilder(['iso2Code' => 'DE']));
        $itemBuilder2 = $this->createItemTransferBuilder([
            'unitNetPrice' => 10000,
            'sumNetPrice' => 10000,
            'idProductAbstract' => $idProductAbstract2,
        ])
            ->withAnotherShipment(
                (new ShipmentBuilder())
                    ->withAnotherShippingAddress($addressBuilder2)
            );

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder1)
            ->withAnotherItem($itemBuilder2)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, [$idProductAbstract1 => 20.00, $idProductAbstract2 => 15.00]];
    }

    /**
     * @return array
     */
    protected function getDataWithoutQuoteAndItemLevelShippingAddressesToFrance(): array
    {
        $defaultCountryIso2Code = 'FR';
        $defaultTaxRate = 66.00;

        $idProductAbstract = 141;
        $itemBuilder = $this->createItemTransferBuilder([
            'unitNetPrice' => 10000,
            'sumNetPrice' => 10000,
            'idProductAbstract' => $idProductAbstract,
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, [$idProductAbstract => 20.00]];
    }

    /**
     * @return array
     */
    protected function getDataWithoutQuoteAndItemLevelShippingAddressesToGermany(): array
    {
        $defaultCountryIso2Code = 'DE';
        $defaultTaxRate = 66.00;

        $idProductAbstract = 141;
        $itemBuilder = $this->createItemTransferBuilder([
            'unitGrossPrice' => 10000,
            'sumGrossPrice' => 10000,
            'idProductAbstract' => $idProductAbstract,
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, [$idProductAbstract => 15.00]];
    }

    /**
     * @return array
     */
    protected function getDataWithoutQuoteAndItemLevelShippingAddressesToMoon(): array
    {
        $defaultCountryIso2Code = static::DEFAULT_TAX_COUNTRY_ISO_2_CODE;
        $defaultTaxRate = 66.00;

        $idProductAbstract = 141;
        $itemBuilder = $this->createItemTransferBuilder([
            'unitGrossPrice' => 10000,
            'sumGrossPrice' => 10000,
            'idProductAbstract' => $idProductAbstract,
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, [$idProductAbstract => $defaultTaxRate]];
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\DataBuilder\ItemBuilder
     */
    protected function createItemTransferBuilder(array $seed = []): ItemBuilder
    {
        return (new ItemBuilder($seed));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\TaxProductConnector\TaxProductConnectorBusinessFactory
     */
    protected function createTaxProductConnectorBusinessFactory(): TaxProductConnectorBusinessFactory
    {
        return $this->getMockBuilder(TaxProductConnectorBusinessFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
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