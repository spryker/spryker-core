<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade;

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
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrier;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxSetTax;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorBusinessFactory;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxBridge;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group Facade
 * @group ShipmentTaxRateCalculatorTest
 * Add your own group annotations below this line
 */
class ShipmentTaxRateCalculatorTest extends Test
{
    protected const FLOAT_COMPARISION_DELTA = 0.001;

    protected const DEFAULT_TAX_COUNTRY_ISO_2_CODE = 'MOON';

    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->createShipmentMethodWithTaxSet(20.00, 'FR');
        $this->createShipmentMethodWithTaxSet(15.00, 'DE');
    }

    /**
     * @dataProvider taxRateCalculationShouldUseQuoteShippingAddressAndShipmentDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $defaultCountryIso2Code
     * @param float $defaultTaxRate
     * @param array $expectedValues
     *
     * @return void
     */
    public function testTaxRateCalculationShouldUseQuoteShippingAddressAndShipment(
        QuoteTransfer $quoteTransfer,
        string $defaultCountryIso2Code,
        float $defaultTaxRate,
        array $expectedValues
    ): void {
        // Arrange
//        $taxProductConnectorFacade = $this->tester->getTaxProductConnectorFacadeWithMockedFactory(
//            $this->createTaxProductConnectorBusinessFactory(),
//            $this->createTaxProductConnectorToTaxFacadeBridgeMock($defaultCountryIso2Code, $defaultTaxRate)
//        );
        $shipmentFacade = $this->tester->getFacade();

        // Act
        $shipmentFacade->calculateShipmentTaxRate($quoteTransfer);

        // Assert
        $this->assertItemsForTaxes($quoteTransfer, $expectedValues);
    }

    /**
     * @dataProvider taxRateCalculationShouldUseItemShippingAddressAndShipmentDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $defaultCountryIso2Code
     * @param float $defaultTaxRate
     * @param array $expectedValues
     *
     * @return void
     */
    public function testTaxRateCalculationShouldUseItemShippingAddressAndShipment(
        QuoteTransfer $quoteTransfer,
        string $defaultCountryIso2Code,
        float $defaultTaxRate,
        array $expectedValues
    ): void {
        // Arrange
//        $taxProductConnectorFacade = $this->tester->getTaxProductConnectorFacadeWithMockedFactory(
//            $this->createTaxProductConnectorBusinessFactory(),
//            $this->createTaxProductConnectorToTaxFacadeBridgeMock($defaultCountryIso2Code, $defaultTaxRate)
//        );
        $shipmentFacade = $this->tester->getFacade();

        // Act
        $shipmentFacade->calculateShipmentTaxRate($quoteTransfer);

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
    public function taxRateCalculationShouldUseQuoteShippingAddressAndShipmentDataProvider(): array
    {
        return [
            'with quote level shipping address and shipment: France, expected tax rate 20%' => $this->getDataWithQuoteLevelShippingAddressAndShipmentToFrance(),
            'with quote level shipping address and shipment: Moon, expected tax rate 0%' => $this->getDataWithQuoteLevelShippingAddressAndShipmentToMoon(),
        ];
    }

    /**
     * @return array
     */
    public function taxRateCalculationShouldUseItemShippingAddressAndShipmentDataProvider(): array
    {
        return [
            'with item level multiple shipments and shipping addresses: France tax rate 20%, expected tax rate 20%' => $this->getDataWithItemLevelShippingAddressesToFranceWithTaxRate20(),
            'with item level multiple shipments and shipping addresses: France tax rate 20%, expected tax rate 20%; Germany tax rate 15%, expected tax rate 15%' => $this->getDataWithItemLevelShippingAddressesToFranceWithTaxRate20AndGermanyWithTaxRate15(),
            'with item level multiple shipments and shipping addresses: Mars tax rate undefined, expected tax rate 0%' => $this->getDataWithItemLevelShippingAddressesToMarsWithTaxRateUndefined(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShippingAddressAndShipmentToFrance(): array
    {
        $defaultCountryIso2Code = static::DEFAULT_TAX_COUNTRY_ISO_2_CODE;
        $defaultTaxRate = 0.00;

        $idProductAbstract = 141;
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));
        $itemBuilder = $this->createItemTransferBuilder([
            'unitNetPrice' => 10000,
            'sumNetPrice' => 10000,
            'idProductAbstract' => $idProductAbstract,
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress($addressBuilder)
            ->withAnotherShipment(
                (new ShipmentBuilder())
                    ->withAnotherShippingAddress($addressBuilder)
                    ->withAnotherMethod()
            )
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
    protected function getDataWithQuoteLevelShippingAddressAndShipmentToMoon(): array
    {
        $defaultCountryIso2Code = static::DEFAULT_TAX_COUNTRY_ISO_2_CODE;
        $defaultTaxRate = 0.00;

        $idProductAbstract = 141;
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'MARS']));
        $itemBuilder = $this->createItemTransferBuilder([
            'unitGrossPrice' => 10000,
            'sumGrossPrice' => 10000,
            'idProductAbstract' => $idProductAbstract,
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress($addressBuilder)
            ->withAnotherShipment(
                (new ShipmentBuilder())
                    ->withAnotherShippingAddress($addressBuilder)
                    ->withAnotherMethod()
            )
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
    protected function getDataWithItemLevelShippingAddressesToFranceWithTaxRate20(): array
    {
        $defaultCountryIso2Code = static::DEFAULT_TAX_COUNTRY_ISO_2_CODE;
        $defaultTaxRate = 66.00;

        $idProductAbstract = 141;
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));
        $itemBuilder = $this->createItemTransferBuilder([
            'unitNetPrice' => 10000,
            'sumNetPrice' => 10000,
            'idProductAbstract' => $idProductAbstract,
        ])
            ->withAnotherShipment(
                (new ShipmentBuilder())
                ->withAnotherShippingAddress($addressBuilder)
                ->withAnotherMethod()
            );

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
    protected function getDataWithItemLevelShippingAddressesToFranceWithTaxRate20AndGermanyWithTaxRate15(): array
    {
        $defaultCountryIso2Code = static::DEFAULT_TAX_COUNTRY_ISO_2_CODE;
        $defaultTaxRate = 0.00;

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
                    ->withAnotherMethod()
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
                    ->withAnotherMethod()
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
    protected function getDataWithItemLevelShippingAddressesToMarsWithTaxRateUndefined(): array
    {
        $defaultCountryIso2Code = static::DEFAULT_TAX_COUNTRY_ISO_2_CODE;
        $defaultTaxRate = 0.00;

        $idProductAbstract = 141;
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'MARS']));
        $itemBuilder = $this->createItemTransferBuilder([
            'unitNetPrice' => 10000,
            'sumNetPrice' => 10000,
            'idProductAbstract' => $idProductAbstract,
        ])
            ->withAnotherShipment(
                (new ShipmentBuilder())
                    ->withAnotherShippingAddress($addressBuilder)
                    ->withAnotherMethod()
            );

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, [$idProductAbstract => 0.00]];
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

    /**
     * @param float $taxRate
     * @param string $iso2Code
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethod
     */
    protected function createShipmentMethodWithTaxSet(float $taxRate, string $iso2Code): SpyShipmentMethod
    {
        $countryEntity = SpyCountryQuery::create()->findOneByIso2Code($iso2Code);

        $taxRateEntity0 = new SpyTaxRate();
        $taxRateEntity0->setFkCountry($countryEntity->getIdCountry());
        $taxRateEntity0->delete();

        $taxRateEntity1 = new SpyTaxRate();
        $taxRateEntity1->setRate($taxRate);
        $taxRateEntity1->setName('test rate 1');
        $taxRateEntity1->setFkCountry($countryEntity->getIdCountry());
        $taxRateEntity1->save();

        $taxRateEntity2 = new SpyTaxRate();
        $taxRateEntity2->setRate(13);
        $taxRateEntity2->setName('tax rate 2');
        $taxRateEntity2->setFkCountry($countryEntity->getIdCountry());
        $taxRateEntity2->save();

        $taxRateExemptEntity = new SpyTaxRate();
        $taxRateExemptEntity->setRate(0);
        $taxRateExemptEntity->setName(TaxConstants::TAX_EXEMPT_PLACEHOLDER);
        $taxRateExemptEntity->save();

        $taxSetEntity = new SpyTaxSet();
        $taxSetEntity->setName('name of tax set');
        $taxSetEntity->save();

        $taxSetTaxRateEntity = new SpyTaxSetTax();
        $taxSetTaxRateEntity->setFkTaxSet($taxSetEntity->getIdTaxSet());
        $taxSetTaxRateEntity->setFkTaxRate($taxRateEntity1->getIdTaxRate());
        $taxSetTaxRateEntity->save();

        $taxSetTaxRateEntity = new SpyTaxSetTax();
        $taxSetTaxRateEntity->setFkTaxSet($taxSetEntity->getIdTaxSet());
        $taxSetTaxRateEntity->setFkTaxRate($taxRateEntity2->getIdTaxRate());
        $taxSetTaxRateEntity->save();

        $taxSetTaxRateEntity = new SpyTaxSetTax();
        $taxSetTaxRateEntity->setFkTaxSet($taxSetEntity->getIdTaxSet());
        $taxSetTaxRateEntity->setFkTaxRate($taxRateExemptEntity->getIdTaxRate());
        $taxSetTaxRateEntity->save();

        $shipmentCarrierEntity = new SpyShipmentCarrier();
        $shipmentCarrierEntity->setName('name carrier');
        $shipmentCarrierEntity->save();

        $shipmentMethodEntity = new SpyShipmentMethod();
        $shipmentMethodEntity->setFkShipmentCarrier($shipmentCarrierEntity->getIdShipmentCarrier());
        $shipmentMethodEntity->setFkTaxSet($taxSetEntity->getIdTaxSet());
        $shipmentMethodEntity->setName('test shipment method');
        $shipmentMethodEntity->save();

        return $shipmentMethodEntity;
    }
}