<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade;

use BadMethodCallException;
use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\SequenceNumberSettingsBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrier;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Orm\Zed\Tax\Persistence\Map\SpyTaxRateTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxRateQuery;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxSetTax;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\Product\ProductDependencyProvider;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxBridge;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainer;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;
use Spryker\Zed\Tax\TaxDependencyProvider;
use Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorBusinessFactory;
use Spryker\Zed\TaxProductConnector\Communication\Plugin\TaxSetProductAbstractAfterCreatePlugin;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxBridge;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface;
use Spryker\Zed\TaxProductConnector\TaxProductConnectorDependencyProvider;

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

    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @var \Orm\Zed\Shipment\Persistence\SpyShipmentMethod[]
     */
    protected $shipmentMethodEntityList;

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

        $this->shipmentMethodEntityList = [];
        $this->shipmentMethodEntityList['FR'] = $this->createShipmentMethodWithTaxSet(20.00, 'FR');
        $this->shipmentMethodEntityList['DE'] = $this->createShipmentMethodWithTaxSet(15.00, 'DE');
    }

    /**
     * @dataProvider taxRateCalculationShouldUseQuoteShippingAddressAndShipmentDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $defaultCountryIso2Code
     * @param float $defaultTaxRate
     * @param flaot $expectedValue
     *
     * @return void
     */
    public function testTaxRateCalculationShouldUseQuoteShippingAddressAndShipment(
        QuoteTransfer $quoteTransfer,
        string $defaultCountryIso2Code,
        float $defaultTaxRate,
        float $expectedValue
    ): void {
        // Arrange
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productAbstractTransfer = $this->haveProductWithTaxSetInDb($quoteTransfer->getShippingAddress()->getIso2Code());
            $itemTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
        }

        $shipmentMethodEntity = $this->findShipmentMethodEntityByAddressIso2Code($quoteTransfer->getShipment()->getShippingAddress());
        $this->mapShipmentMethodEntityToTransfer($quoteTransfer->getShipment()->getMethod(), $shipmentMethodEntity);

        $this->tester->setDependency(
            ShipmentDependencyProvider::FACADE_TAX,
            $this->createShipmentToTaxFacadeBridgeMock($defaultCountryIso2Code, $defaultTaxRate)
        );

        $shipmentFacade = $this->tester->getFacade();

        // Act
        $shipmentFacade->calculateShipmentTaxRate($quoteTransfer);

        // Assert
        $this->assertShipmentMethodTaxeRate($quoteTransfer->getShipment()->getMethod(), $expectedValue);
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
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productAbstractTransfer = $this->haveProductWithTaxSetInDb($itemTransfer->getShipment()->getShippingAddress()->getIso2Code());
            $itemTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());

            $shipmentMethodEntity = $this->findShipmentMethodEntityByAddressIso2Code($itemTransfer->getShipment()->getShippingAddress());
            $this->mapShipmentMethodEntityToTransfer($itemTransfer->getShipment()->getMethod(), $shipmentMethodEntity);
        }

        $this->tester->setDependency(
            ShipmentDependencyProvider::FACADE_TAX,
            $this->createShipmentToTaxFacadeBridgeMock($defaultCountryIso2Code, $defaultTaxRate)
        );

        $this->tester->setDependency(
            TaxDependencyProvider::STORE_CONFIG,
            $this->createTaxStoreMock($defaultCountryIso2Code)
        );

        $shipmentFacade = $this->tester->getFacade();

        // Act
        $shipmentFacade->calculateShipmentTaxRate($quoteTransfer);

        // Assert
        $this->assertItemsForTaxes($quoteTransfer, $expectedValues);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param float $expectedValue
     *
     * @return void
     */
    protected function assertShipmentMethodTaxeRate(?ShipmentMethodTransfer $shipmentMethodTransfer, float $expectedValue): void
    {
        if ($shipmentMethodTransfer === null) {
            return;
        }

        $currentTaxRate = $shipmentMethodTransfer->getTaxRate();
        $this->assertEqualsWithDelta($expectedValue, $currentTaxRate, static::FLOAT_COMPARISION_DELTA,
            'tax rate should be ' . $expectedValue
            . ' for shipment method ID ' . $shipmentMethodTransfer->getIdShipmentMethod()
            . ', ' . $currentTaxRate . ' given.'
        );
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

            $this->assertShipmentMethodTaxeRate($itemTransfer->getShipment()->getMethod(), $expectedValues[$itemTransfer->getSku()]);
        }
    }

    /**
     * @return array
     */
    public function taxRateCalculationShouldUseQuoteShippingAddressAndShipmentDataProvider(): array
    {
        return [
            'address: France; expected tax rate: 20%' => $this->getDataWithQuoteLevelShippingAddressAndShipmentToFrance(),
            'address: Moon; expected tax rate: 0%' => $this->getDataWithQuoteLevelShippingAddressAndShipmentToMoon(),
        ];
    }

    /**
     * @return array
     */
    public function taxRateCalculationShouldUseItemShippingAddressAndShipmentDataProvider(): array
    {
        return [
            'address: France, tax rate 20%; expected tax rate: 20%' => $this->getDataWithItemLevelShippingAddressesToFranceWithTaxRate20(),
            'addresses: France, tax rate 20%; expected tax rate: 20%; Germany, tax rate 15%; expected tax rate: 15%' => $this->getDataWithItemLevelShippingAddressesToFranceWithTaxRate20AndGermanyWithTaxRate15(),
            'address: Mars, tax rate: undefined; expected tax rate: 0%' => $this->getDataWithItemLevelShippingAddressesToMarsWithTaxRateUndefined(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShippingAddressAndShipmentToFrance(): array
    {
        $defaultCountryIso2Code = 'MOON';
        $defaultTaxRate = 0.00;

        $skuProductAbstract = 'france_20';
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));
        $itemBuilder = $this->createItemTransferBuilder([
            'sku' => 'france_20',
        ]);

        $shipmentTransfer = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod()
            ->build();

        $expenseTransfer = (new ExpenseBuilder([
            'type' => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
        ]))->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $expenseTransfer->setShipment($shipmentTransfer);
        $quoteTransfer->setShipment($shipmentTransfer);
        $quoteTransfer->addExpense($expenseTransfer);

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, 20.00];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShippingAddressAndShipmentToMoon(): array
    {
        $defaultCountryIso2Code = 'MOON';
        $defaultTaxRate = 0.00;

        $skuProductAbstract = 'mars_0';
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'MARS']));
        $itemBuilder = $this->createItemTransferBuilder([
            'sku' => $skuProductAbstract,
        ]);

        $shipmentTransfer = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod(
                new ShipmentMethodBuilder([
                    'idShipmentMethod' => -999999,
                    'taxRate' => 0,
                ])
            )
            ->build();

        $expenseTransfer = (new ExpenseBuilder([
            'type' => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
        ]))->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $expenseTransfer->setShipment($shipmentTransfer);
        $quoteTransfer->setShipment($shipmentTransfer);
        $quoteTransfer->addExpense($expenseTransfer);

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, $defaultTaxRate];
    }

    /**
     * @return array
     */
    protected function getDataWithItemLevelShippingAddressesToFranceWithTaxRate20(): array
    {
        $defaultCountryIso2Code = 'MOON';
        $defaultTaxRate = 66.00;

        $skuProductAbstract = 'france_20';
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentTransfer = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod()
            ->build();

        $expenseTransfer = (new ExpenseBuilder([
            'type' => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
        ]))->build();

        $itemTransfer = $this->createItemTransferBuilder([
            'sku' => $skuProductAbstract,
        ])->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $expenseTransfer->setShipment($shipmentTransfer);
        $quoteTransfer->addExpense($expenseTransfer);
        $itemTransfer->setShipment($shipmentTransfer);
        $quoteTransfer->addItem($itemTransfer);

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, [$skuProductAbstract => 20.00]];
    }

    /**
     * @return array
     */
    protected function getDataWithItemLevelShippingAddressesToFranceWithTaxRate20AndGermanyWithTaxRate15(): array
    {
        $defaultCountryIso2Code = 'MOON';
        $defaultTaxRate = 0.00;

        $skuProductAbstract1 = 'france_20';
        $addressBuilder1 = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentTransfer1 = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder1)
            ->withAnotherMethod()
            ->build();

        $expenseTransfer1 = (new ExpenseBuilder([
            'type' => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
        ]))->build();

        $itemTransfer1 = $this->createItemTransferBuilder([
            'sku' => $skuProductAbstract1,
        ])->build();


        $skuProductAbstract2 = 'germany_15';
        $addressBuilder2 = (new AddressBuilder(['iso2Code' => 'DE']));

        $shipmentTransfer2 = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder2)
            ->withAnotherMethod()
            ->build();

        $expenseTransfer2 = (new ExpenseBuilder([
            'type' => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
        ]))->build();

        $itemTransfer2 = $this->createItemTransferBuilder([
            'sku' => $skuProductAbstract2,
        ])->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $expenseTransfer1->setShipment($shipmentTransfer1);
        $quoteTransfer->addExpense($expenseTransfer1);
        $itemTransfer1->setShipment($shipmentTransfer1);
        $quoteTransfer->addItem($itemTransfer1);

        $expenseTransfer2->setShipment($shipmentTransfer2);
        $quoteTransfer->addExpense($expenseTransfer2);
        $itemTransfer2->setShipment($shipmentTransfer2);
        $quoteTransfer->addItem($itemTransfer2);

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, [$skuProductAbstract1 => 20.00, $skuProductAbstract2 => 15.00]];
    }

    /**
     * @return array
     */
    protected function getDataWithItemLevelShippingAddressesToMarsWithTaxRateUndefined(): array
    {
        $defaultCountryIso2Code = 'MOON';
        $defaultTaxRate = 0.00;

        $skuProductAbstract = 'mars_0';
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'MARS']));

        $shipmentTransfer = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod(
                new ShipmentMethodBuilder([
                    'idShipmentMethod' => -999999,
                    'taxRate' => 0,
                ])
            )
            ->build();

        $expenseTransfer = (new ExpenseBuilder([
            'type' => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
        ]))->build();

        $itemTransfer = $this->createItemTransferBuilder([
            'sku' => $skuProductAbstract,
        ])->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $expenseTransfer->setShipment($shipmentTransfer);
        $quoteTransfer->addExpense($expenseTransfer);
        $itemTransfer->setShipment($shipmentTransfer);
        $quoteTransfer->addItem($itemTransfer);

        return [$quoteTransfer, $defaultCountryIso2Code, $defaultTaxRate, [$skuProductAbstract => 0.00]];
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface
     */
    protected function createShipmentToTaxFacadeBridgeMock(string $defaultCountryIso2Code, float $defaultTaxRate): ShipmentToTaxInterface
    {
        $bridgeMock = $this->getMockBuilder(ShipmentToTaxBridge::class)
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
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethod
     */
    protected function createShipmentMethodWithTaxSet(float $taxRate, string $iso2Code): SpyShipmentMethod
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

    /**
     * @param string $countryIso2Code
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function haveProductWithTaxSetInDb(string $countryIso2Code): ProductAbstractTransfer
    {
        $productAbstractOverride = [];
        if (isset($this->shipmentMethodEntityList[$countryIso2Code])) {
            $shipmentMethodEntity = $this->shipmentMethodEntityList[$countryIso2Code];
            $productAbstractOverride[ProductAbstractTransfer::ID_TAX_SET] = $shipmentMethodEntity->getFkTaxSet();
        }

        $productAbstractTransfer = $this->tester->haveProductAbstract($productAbstractOverride);

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethod
     */
    protected function findShipmentMethodEntityByAddressIso2Code(
        AddressTransfer $addressTransfer
    ): ?SpyShipmentMethod {
        if (!isset($this->shipmentMethodEntityList[$addressTransfer->getIso2Code()])) {
            return null;
        }

        return $this->shipmentMethodEntityList[$addressTransfer->getIso2Code()];
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param null|\Orm\Zed\Shipment\Persistence\SpyShipmentMethod $spyShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function mapShipmentMethodEntityToTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        ?SpyShipmentMethod $shipmentMethodEntity
    ): ShipmentMethodTransfer {
        if ($shipmentMethodEntity === null) {
            return $shipmentMethodTransfer;
        }

        return $shipmentMethodTransfer->fromArray($shipmentMethodEntity->toArray(), true);
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