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
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Sales\SalesConstants;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacade;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;

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

    /**
     * @see \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @see \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @var \SprykerTest\Zed\TaxProductConnector\TaxProductConnectorBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider taxRateCalculationShouldUseQuoteShippingAddressDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $expectedValues
     *
     * @return void
     */
    public function testTaxRateCalculationShouldUseQuoteShippingAddress(QuoteTransfer $quoteTransfer, array $expectedValues)
    {
        // Arrange
//        $this->tester->haveTaxRate($expectedVAT);
        $taxProductConnectorFacade = $this->tester->getFacade();

        // Act
        $taxProductConnectorFacade->calculateProductItemTaxRate($quoteTransfer);

        // Assert
        $this->assertItemsForTaxes($quoteTransfer, $expectedValues);
    }

    /**
     * @dataProvider taxRateCalculationShouldUseItemShippingAddressDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $expectedValues
     *
     * @return void
     */
    public function testTaxRateCalculationShouldUseItemShippingAddress(QuoteTransfer $quoteTransfer, array $expectedValues)
    {
        // Arrange
        $taxProductConnectorFacade = $this->tester->getFacade();

        // Act
        $taxProductConnectorFacade->calculateProductItemTaxRate($quoteTransfer);

        // Assert
        $this->assertItemsForTaxes($quoteTransfer, $expectedValues);
    }

    /**
     * @dataProvider taxRateCalculationShouldBeDefaultDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $expectedValues
     *
     * @return void
     */
    public function testTaxRateCalculationShouldBeDefault(QuoteTransfer $quoteTransfer, array $expectedValues)
    {
        // Arrange
        $taxProductConnectorFacade = $this->tester->getFacade();

        // Act
        $taxProductConnectorFacade->calculateProductItemTaxRate($quoteTransfer);

        // Assert
        $this->assertItemsForTaxes($quoteTransfer, $expectedValues);
    }

    /**
     * @return array
     */
    public function taxRateCalculationShouldUseQuoteShippingAddressDataProvider()
    {
        return [
            'with quote level shipping address: France tax 20%, Net price' => $this->getDataWithQuoteLevelShippingAddressToFranceWithTax20(),
            'with quote level shipping address: Germany tax 15%, Gross price' => $this->getDataWithQuoteLevelShippingAddressToGermanyWithTax15(),
        ];
    }

    /**
     * @return array
     */
    public function taxRateCalculationShouldUseItemShippingAddressDataProvider()
    {
        return [
            'with item level shipping addresses: France tax 20%, Germany tax 15%, Net price' => $this->getDataWithItemLevelShippingAddressesToFranceAndGermanyWithNetPrice(),
            'with item level shipping addresses: France tax 20%, Germany tax 15%, Gross price' => $this->getDataWithItemLevelShippingAddressesToFranceAndGermanyWithGrossPrice(),
        ];
    }

    /**
     * @return array
     */
    public function taxRateCalculationShouldBeDefaultDataProvider()
    {
        return [
            'without quote and item level shipping addresses: France tax 20%, Net price' => $this->getDataWithoutQuoteAndItemLevelShippingAddressesToFranceWithTax20NetPrice(),
            'without quote and item level shipping addresses: Germany tax 15%, Gross price' => $this->getDataWithoutQuoteAndItemLevelShippingAddressesToGermanyWithTax15GrossPrice(),
            'without quote and item level shipping addresses: Moon tax undefined, Gross price' => $this->getDataWithoutQuoteAndItemLevelShippingAddressesToMoonWithTaxNullGrossPrice(),
            'without quote and item level shipping addresses: Moon tax undefined, Net price' => $this->getDataWithoutQuoteAndItemLevelShippingAddressesToMoonWithTaxNullNetPrice(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShippingAddressToFranceWithTax20()
    {
        $itemBuilder = $this->createItemTransferBuilder([
            'unitNetPrice' => 10000,
            'sumNetPrice' => 10000,
            'idProductAbstract' => 141, // @todo Save product into DB. Add tax rate.
        ]);

        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $quoteTransfer = (new QuoteBuilder([
                'priceMode' => static::PRICE_MODE_NET,
            ]))
            ->withShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, [141 => 20.00]];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShippingAddressToGermanyWithTax15()
    {
        $itemBuilder = $this->createItemTransferBuilder([
            'unitGrossPrice' => 10000,
            'sumGrossPrice' => 10000,
            'idProductAbstract' => 141, // @todo Save product into DB. Add tax rate.
        ]);

        $addressBuilder = (new AddressBuilder(['iso2Code' => 'DE']));

        $quoteTransfer = (new QuoteBuilder([
                'priceMode' => static::PRICE_MODE_GROSS,
            ]))
            ->withShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, [141 => 13.04]];
    }

    /**
     * @return array
     */
    protected function getDataWithItemLevelShippingAddressesToFranceAndGermanyWithNetPrice()
    {
        $addressBuilder1 = (new AddressBuilder(['iso2Code' => 'FR']));
        $itemBuilder1 = $this->createItemTransferBuilder([
            'unitNetPrice' => 10000,
            'sumNetPrice' => 10000,
            'idProductAbstract' => 141, // @todo Save product into DB. Add tax rate.
        ])
        ->withAnotherShipment(
            (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder1)
        );

        $addressBuilder2 = (new AddressBuilder(['iso2Code' => 'DE']));
        $itemBuilder2 = $this->createItemTransferBuilder([
            'unitNetPrice' => 10000,
            'sumNetPrice' => 10000,
            'idProductAbstract' => 142, // @todo Save product into DB. Add tax rate.
        ])
            ->withAnotherShipment(
                (new ShipmentBuilder())
                    ->withAnotherShippingAddress($addressBuilder2)
            );

        $quoteTransfer = (new QuoteBuilder([
            'priceMode' => static::PRICE_MODE_NET,
        ]))
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder1)
            ->withAnotherItem($itemBuilder2)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, [141 => 20.00, 142 => 13.04]];
    }

    /**
     * @return array
     */
    protected function getDataWithItemLevelShippingAddressesToFranceAndGermanyWithGrossPrice()
    {
        $addressBuilder1 = (new AddressBuilder(['iso2Code' => 'FR']));
        $itemBuilder1 = $this->createItemTransferBuilder([
            'unitNetPrice' => 10000,
            'sumNetPrice' => 10000,
            'idProductAbstract' => 141, // @todo Save product into DB. Add tax rate.
        ])
            ->withAnotherShipment(
                (new ShipmentBuilder())
                    ->withAnotherShippingAddress($addressBuilder1)
            );

        $addressBuilder2 = (new AddressBuilder(['iso2Code' => 'DE']));
        $itemBuilder2 = $this->createItemTransferBuilder([
            'unitNetPrice' => 10000,
            'sumNetPrice' => 10000,
            'idProductAbstract' => 142, // @todo Save product into DB. Add tax rate.
        ])
            ->withAnotherShipment(
                (new ShipmentBuilder())
                    ->withAnotherShippingAddress($addressBuilder2)
            );

        $quoteTransfer = (new QuoteBuilder([
            'priceMode' => static::PRICE_MODE_GROSS,
        ]))
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder1)
            ->withAnotherItem($itemBuilder2)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, [141 => 16.67, 142 => 13.04]];
    }

    /**
     * @return array
     */
    protected function getDataWithoutQuoteAndItemLevelShippingAddressesToFranceWithTax20NetPrice()
    {
        $itemBuilder = $this->createItemTransferBuilder([
            'unitNetPrice' => 10000,
            'sumNetPrice' => 10000,
            'idProductAbstract' => 141, // @todo Save product into DB. Add tax rate.
        ]);

        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $quoteTransfer = (new QuoteBuilder([
            'priceMode' => static::PRICE_MODE_NET,
        ]))
            ->withShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, [141 => 20.00]];
    }

    /**
     * @return array
     */
    protected function getDataWithoutQuoteAndItemLevelShippingAddressesToGermanyWithTax15GrossPrice()
    {
        $itemBuilder = $this->createItemTransferBuilder([
            'unitGrossPrice' => 10000,
            'sumGrossPrice' => 10000,
            'idProductAbstract' => 141, // @todo Save product into DB. Add tax rate.
        ]);

        $addressBuilder = (new AddressBuilder(['iso2Code' => 'DE']));

        $quoteTransfer = (new QuoteBuilder([
            'priceMode' => static::PRICE_MODE_GROSS,
        ]))
            ->withShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, [141 => 13.04]];
    }

    /**
     * @return array
     */
    protected function getDataWithoutQuoteAndItemLevelShippingAddressesToMoonWithTaxNullGrossPrice()
    {
        $itemBuilder = $this->createItemTransferBuilder([
            'unitGrossPrice' => 10000,
            'sumGrossPrice' => 10000,
            'idProductAbstract' => 141, // @todo Save product into DB. Add tax rate.
        ]);

        $addressBuilder = (new AddressBuilder(['iso2Code' => 'MOON']));

        $quoteTransfer = (new QuoteBuilder([
            'priceMode' => static::PRICE_MODE_GROSS,
        ]))
            ->withShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, [141 => 0]];
    }

    /**
     * @return array
     */
    protected function getDataWithoutQuoteAndItemLevelShippingAddressesToMoonWithTaxNullNetPrice()
    {
        $itemBuilder = $this->createItemTransferBuilder([
            'unitGrossPrice' => 10000,
            'sumGrossPrice' => 10000,
            'idProductAbstract' => 141, // @todo Save product into DB. Add tax rate.
        ]);

        $addressBuilder = (new AddressBuilder(['iso2Code' => 'MOON']));

        $quoteTransfer = (new QuoteBuilder([
            'priceMode' => static::PRICE_MODE_GROSS,
        ]))
            ->withShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, [141 => 0]];
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

            $expectedVAT = $expectedValues[$itemTransfer->getIdProductAbstract()];
            $this->assertEqualsWithDelta($expectedVAT, $itemTransfer->getTaxRate(), static::FLOAT_COMPARISION_DELTA,
                'VAT should be ' . $expectedVAT . ' for product ID ' . $itemTransfer->getIdProductAbstract()
                . ', ' . $itemTransfer->getTaxRate() . ' given.'
            );
        }
    }
}