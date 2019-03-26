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
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrier;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Tax\Persistence\Map\SpyTaxRateTableMap;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxRateQuery;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Orm\Zed\Tax\Persistence\SpyTaxSetTax;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\Product\ProductDependencyProvider;
use Spryker\Zed\Tax\TaxDependencyProvider;
use Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorBusinessFactory;
use Spryker\Zed\TaxProductConnector\Communication\Plugin\TaxSetProductAbstractAfterCreatePlugin;
use Spryker\Zed\TaxProductConnector\Communication\Plugin\TaxSetProductAbstractAfterUpdatePlugin;
use Spryker\Zed\TaxProductConnector\Communication\Plugin\TaxSetProductAbstractReadPlugin;
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
 * @group ProductItemTaxRateCalculatorTest
 * Add your own group annotations below this line
 */
class ProductItemTaxRateCalculatorTest extends Test
{
    protected const FLOAT_COMPARISION_DELTA = 0.001;

    /**
     * @var \SprykerTest\Zed\TaxProductConnector\TaxProductConnectorBusinessTester
     */
    protected $tester;

    /**
     * @var \Orm\Zed\Tax\Persistence\SpyTaxSetTax[]
     */
    protected $taxSetEntityList;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->tester->setDependency(
            ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_AFTER_CREATE,
            $this->getProductAbstractAfterCreatePlugins()
        );

        SpyTaxRateQuery::create()->update(['Rate' => '1']);

        $this->taxSetEntityList = [];
        $this->taxSetEntityList['FR'] = $this->haveTaxRateWithTaxSetInDb(20.00, 'FR');
        $this->taxSetEntityList['DE'] = $this->haveTaxRateWithTaxSetInDb(15.00, 'DE');
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
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productAbstractTransfer = $this->haveProductWithTaxSetInDb($quoteTransfer->getShippingAddress()->getIso2Code());
            $itemTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
        }

        $this->tester->setDependency(
            TaxProductConnectorDependencyProvider::FACADE_TAX,
            $this->createTaxProductConnectorToTaxFacadeBridgeMock($defaultCountryIso2Code, $defaultTaxRate)
        );

        // Act
        $this->tester->getFacade()->calculateProductItemTaxRate($quoteTransfer);

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
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productAbstractTransfer = $this->haveProductWithTaxSetInDb($itemTransfer->getShipment()->getShippingAddress()->getIso2Code());
            $itemTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
        }

        $this->tester->setDependency(
            TaxProductConnectorDependencyProvider::FACADE_TAX,
            $this->createTaxProductConnectorToTaxFacadeBridgeMock($defaultCountryIso2Code, $defaultTaxRate)
        );

        // Act
        $this->tester->getFacade()->calculateProductItemTaxRate($quoteTransfer);

        // Assert
        $this->assertItemsForTaxes($quoteTransfer, $expectedValues);
    }

    /**
     * @dataProvider taxRateCalculationShouldBeDefaultDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $defaultCountryIso2Code
     * @param float $defaultTaxRate
     * @param array $expectedValue
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
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productAbstractTransfer = $this->haveProductWithTaxSetInDb($defaultCountryIso2Code);
            $itemTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
        }

        $this->tester->setDependency(
            TaxProductConnectorDependencyProvider::FACADE_TAX,
            $this->createTaxProductConnectorToTaxFacadeBridgeMock($defaultCountryIso2Code, $defaultTaxRate)
        );

        $this->tester->setDependency(
            TaxDependencyProvider::STORE_CONFIG,
            $this->createTaxStoreMock($defaultCountryIso2Code)
        );

        // Act
        $this->tester->getFacade()->calculateProductItemTaxRate($quoteTransfer);

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
            if (!isset($expectedValues[$itemTransfer->getSku()])) {
                continue;
            }

            $expectedTaxRate = $expectedValues[$itemTransfer->getSku()];
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
        $defaultCountryIso2Code = 'MOON';
        $defaultTaxRate = 66.00;

        $skuProductAbstract = 'france_20';
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));
        $itemBuilder = $this->createItemTransferBuilder([
            'sku' => $skuProductAbstract,
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, [$skuProductAbstract => 20.00]];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShippingAddressToMoon(): array
    {
        $defaultCountryIso2Code = 'MOON';
        $defaultTaxRate = 66.00;

        $skuProductAbstract = 'moon_0';
        $addressBuilder = (new AddressBuilder(['iso2Code' => $defaultCountryIso2Code]));
        $itemBuilder = $this->createItemTransferBuilder([
            'sku' => $skuProductAbstract,
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, [$skuProductAbstract => $defaultTaxRate]];
    }

    /**
     * @return array
     */
    protected function getDataWithItemLevelShippingAddressesToFranceAndGermany(): array
    {
        $defaultCountryIso2Code = 'MOON';
        $defaultTaxRate = 66.00;

        $skuProductAbstract1 = 'france_20';
        $addressBuilder1 = (new AddressBuilder(['iso2Code' => 'FR']));
        $itemBuilder1 = $this->createItemTransferBuilder([
            'sku' => $skuProductAbstract1,
        ])
            ->withAnotherShipment(
                (new ShipmentBuilder())
                ->withAnotherShippingAddress($addressBuilder1)
            );

        $skuProductAbstract2 = 'germany_15';
        $addressBuilder2 = (new AddressBuilder(['iso2Code' => 'DE']));
        $itemBuilder2 = $this->createItemTransferBuilder([
            'sku' => $skuProductAbstract2,
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

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, [$skuProductAbstract1 => 20.00, $skuProductAbstract2 => 15.00]];
    }

    /**
     * @return array
     */
    protected function getDataWithoutQuoteAndItemLevelShippingAddressesToFrance(): array
    {
        $defaultCountryIso2Code = 'FR';
        $defaultTaxRate = 0.00;

        $skuProductAbstract = 'france_20';
        $itemBuilder = $this->createItemTransferBuilder([
            'sku' => $skuProductAbstract,
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, [$skuProductAbstract => 20.00]];
    }

    /**
     * @return array
     */
    protected function getDataWithoutQuoteAndItemLevelShippingAddressesToGermany(): array
    {
        $defaultCountryIso2Code = 'DE';
        $defaultTaxRate = 0.00;

        $skuProductAbstract = 'germany_15';
        $itemBuilder = $this->createItemTransferBuilder([
            'sku' => $skuProductAbstract,
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, [$skuProductAbstract => 15.00]];
    }

    /**
     * @return array
     */
    protected function getDataWithoutQuoteAndItemLevelShippingAddressesToMoon(): array
    {
        $defaultCountryIso2Code = 'MOON';
        $defaultTaxRate = 0.00;

        $skuProductAbstract = 'moon_0';
        $itemBuilder = $this->createItemTransferBuilder([
            'sku' => $skuProductAbstract,
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, [$skuProductAbstract => $defaultTaxRate]];
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
    protected function createTaxStoreMock(string $defaultCountryIso2Code): Store
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

    /**
     * @param float $taxRate
     * @param string $iso2Code
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetTax
     */
    protected function haveTaxRateWithTaxSetInDb(float $taxRate, string $iso2Code): SpyTaxSet
    {
        $countryEntity = SpyCountryQuery::create()->findOneByIso2Code($iso2Code);

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

        return $taxSetEntity;
    }

    /**
     * @param string $countryIso2Code
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function haveProductWithTaxSetInDb(string $countryIso2Code): ProductAbstractTransfer
    {
        $productAbstractOverride = [];
        if (isset($this->taxSetEntityList[$countryIso2Code])) {
            $taxSetEntity = $this->taxSetEntityList[$countryIso2Code];
            $productAbstractOverride[ProductAbstractTransfer::ID_TAX_SET] = $taxSetEntity->getIdTaxSet();
        }

        $productAbstractTransfer = $this->tester->haveProductAbstract($productAbstractOverride);

        return $productAbstractTransfer;
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface[]
     */
    protected function getProductAbstractAfterCreatePlugins(): array
    {
        return [
            new TaxSetProductAbstractAfterCreatePlugin(),
        ];
    }
}