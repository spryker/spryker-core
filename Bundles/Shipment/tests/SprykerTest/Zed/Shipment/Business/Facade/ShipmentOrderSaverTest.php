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
 * @group ShipmentOrderSaverTest
 * Add your own group annotations below this line
 */
class ShipmentOrderSaverTest extends Test
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

//        $this->tester->setDependency(
//            ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_AFTER_CREATE,
//            $this->getProductAbstractAfterCreatePlugins()
//        );
//
//        SpyTaxRateQuery::create()->update(['Rate' => '1']);
//
//        $this->shipmentMethodEntityList = [];
//        $this->shipmentMethodEntityList['FR'] = $this->createShipmentMethodWithTaxSet(20.00, 'FR');
//        $this->shipmentMethodEntityList['DE'] = $this->createShipmentMethodWithTaxSet(15.00, 'DE');
    }

    /**
     * @dataProvider shipmentOrderSaverShouldUseQuoteShipmentDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseQuoteShipment(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
//        // Arrange
//        foreach ($quoteTransfer->getItems() as $itemTransfer) {
//            $productAbstractTransfer = $this->haveProductWithTaxSetInDb($quoteTransfer->getShippingAddress()->getIso2Code());
//            $itemTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
//        }
//
//        $shipmentMethodEntity = $this->findShipmentMethodEntityByAddressIso2Code($quoteTransfer->getShipment()->getShippingAddress());
//        $this->mapShipmentMethodEntityToTransfer($quoteTransfer->getShipment()->getMethod(), $shipmentMethodEntity);
//
//        $this->tester->setDependency(
//            ShipmentDependencyProvider::FACADE_TAX,
//            $this->createShipmentToTaxFacadeBridgeMock($defaultCountryIso2Code, $defaultTaxRate)
//        );
//
//        $shipmentFacade = $this->tester->getFacade();
//
//        // Act
//        $shipmentFacade->calculateShipmentTaxRate($quoteTransfer);
//
//        // Assert
//        $this->assertShipmentMethodTaxeRate($quoteTransfer->getShipment()->getMethod(), $expectedValue);
    }

    /**
     * @dataProvider shipmentOrderSaverShouldUseQuoteShipmentAndExpenseDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseQuoteShipmentAndExpense(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
//        // Arrange
//        foreach ($quoteTransfer->getItems() as $itemTransfer) {
//            $productAbstractTransfer = $this->haveProductWithTaxSetInDb($quoteTransfer->getShippingAddress()->getIso2Code());
//            $itemTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
//        }
//
//        $shipmentMethodEntity = $this->findShipmentMethodEntityByAddressIso2Code($quoteTransfer->getShipment()->getShippingAddress());
//        $this->mapShipmentMethodEntityToTransfer($quoteTransfer->getShipment()->getMethod(), $shipmentMethodEntity);
//
//        $this->tester->setDependency(
//            ShipmentDependencyProvider::FACADE_TAX,
//            $this->createShipmentToTaxFacadeBridgeMock($defaultCountryIso2Code, $defaultTaxRate)
//        );
//
//        $shipmentFacade = $this->tester->getFacade();
//
//        // Act
//        $shipmentFacade->calculateShipmentTaxRate($quoteTransfer);
//
//        // Assert
//        $this->assertShipmentMethodTaxeRate($quoteTransfer->getShipment()->getMethod(), $expectedValue);
    }

    /**
     * @dataProvider shipmentOrderSaverShouldUseQuoteShipmentAndShippingAddressDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseQuoteShipmentAndShippingAddress(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
//        // Arrange
//        foreach ($quoteTransfer->getItems() as $itemTransfer) {
//            $productAbstractTransfer = $this->haveProductWithTaxSetInDb($quoteTransfer->getShippingAddress()->getIso2Code());
//            $itemTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
//        }
//
//        $shipmentMethodEntity = $this->findShipmentMethodEntityByAddressIso2Code($quoteTransfer->getShipment()->getShippingAddress());
//        $this->mapShipmentMethodEntityToTransfer($quoteTransfer->getShipment()->getMethod(), $shipmentMethodEntity);
//
//        $this->tester->setDependency(
//            ShipmentDependencyProvider::FACADE_TAX,
//            $this->createShipmentToTaxFacadeBridgeMock($defaultCountryIso2Code, $defaultTaxRate)
//        );
//
//        $shipmentFacade = $this->tester->getFacade();
//
//        // Act
//        $shipmentFacade->calculateShipmentTaxRate($quoteTransfer);
//
//        // Assert
//        $this->assertShipmentMethodTaxeRate($quoteTransfer->getShipment()->getMethod(), $expectedValue);
    }

    /**
     * @dataProvider shipmentOrderSaverShouldUseQuoteShipmentAndItemsDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseQuoteShipmentAndItems(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
//        // Arrange
//        foreach ($quoteTransfer->getItems() as $itemTransfer) {
//            $productAbstractTransfer = $this->haveProductWithTaxSetInDb($quoteTransfer->getShippingAddress()->getIso2Code());
//            $itemTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
//        }
//
//        $shipmentMethodEntity = $this->findShipmentMethodEntityByAddressIso2Code($quoteTransfer->getShipment()->getShippingAddress());
//        $this->mapShipmentMethodEntityToTransfer($quoteTransfer->getShipment()->getMethod(), $shipmentMethodEntity);
//
//        $this->tester->setDependency(
//            ShipmentDependencyProvider::FACADE_TAX,
//            $this->createShipmentToTaxFacadeBridgeMock($defaultCountryIso2Code, $defaultTaxRate)
//        );
//
//        $shipmentFacade = $this->tester->getFacade();
//
//        // Act
//        $shipmentFacade->calculateShipmentTaxRate($quoteTransfer);
//
//        // Assert
//        $this->assertShipmentMethodTaxeRate($quoteTransfer->getShipment()->getMethod(), $expectedValue);
    }

    /**
     * @dataProvider shipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentsArePersistedDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentsArePersisted(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
//        // Arrange
//        foreach ($quoteTransfer->getItems() as $itemTransfer) {
//            $productAbstractTransfer = $this->haveProductWithTaxSetInDb($itemTransfer->getShipment()->getShippingAddress()->getIso2Code());
//            $itemTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
//
//            $shipmentMethodEntity = $this->findShipmentMethodEntityByAddressIso2Code($itemTransfer->getShipment()->getShippingAddress());
//            $this->mapShipmentMethodEntityToTransfer($itemTransfer->getShipment()->getMethod(), $shipmentMethodEntity);
//        }
//
//        $this->tester->setDependency(
//            ShipmentDependencyProvider::FACADE_TAX,
//            $this->createShipmentToTaxFacadeBridgeMock($defaultCountryIso2Code, $defaultTaxRate)
//        );
//
//        $this->tester->setDependency(
//            TaxDependencyProvider::STORE_CONFIG,
//            $this->createTaxStoreMock($defaultCountryIso2Code)
//        );
//
//        $shipmentFacade = $this->tester->getFacade();
//
//        // Act
//        $shipmentFacade->calculateShipmentTaxRate($quoteTransfer);
//
//        // Assert
//        $this->assertItemsForTaxes($quoteTransfer, $expectedValues);
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
    public function shipmentOrderSaverShouldUseQuoteShipmentDataProvider(): array
    {
        return [
            'France; expected: shipment in DB' => $this->getDataWithQuoteLevelShipmentToFrance(),
        ];
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseQuoteShipmentAndExpenseDataProvider(): array
    {
        return [
            'France, expense set; expected: shipment and expense in DB' => $this->getDataWithQuoteLevelShipmentToFranceWithExpense(),
        ];
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseQuoteShipmentAndShippingAddressDataProvider(): array
    {
        return [
            'France, address already persisted in DB; expected: shipment with shipping address in DB' => $this->getDataWithQuoteLevelShipmentAndShippingAddressToFrance(),
        ];
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseQuoteShipmentAndItemsDataProvider(): array
    {
        return [
            'France, 2 items; expected: shipment connected to order items in DB' => $this->getDataWithQuoteLevelShipmentAnd2ItemsToFrance(),
        ];
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentsArePersistedDataProvider(): array
    {
        return [
            'France 1 item; expected: 1 order shipment in DB' => $this->getDataWithMultipleShipmentsAnd1ItemToFrance(),
            'France 2 items, Germany 1 item; expected: 2 order shipments in DB' => $this->getDataWithMultipleShipmentsAnd2ItemsToFranceAnd1ItemToGermany(),
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
    protected function getDataWithQuoteLevelShipmentToFrance(): array
    {
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod();

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherShipment($shipmentBuilder)
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, new SaveOrderTransfer()];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShipmentToFranceWithExpense(): array
    {
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod();

        $expenseBuilder = (new ExpenseBuilder([
            'type' => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
        ]))
            ->withAnotherShipment($shipmentBuilder);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherShipment($shipmentBuilder)
            ->withAnotherExpense($expenseBuilder)
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, new SaveOrderTransfer()];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShipmentAndShippingAddressToFrance(): array
    {
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod();

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherShipment($shipmentBuilder)
            ->withAnotherItem()
            ->withAnotherItem()
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, new SaveOrderTransfer()];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShipmentAnd2ItemsToFrance(): array
    {
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod();

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherShipment($shipmentBuilder)
            ->withAnotherItem()
            ->withAnotherItem()
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, new SaveOrderTransfer()];
    }

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAnd1ItemToFrance(): array
    {
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod();

        $itemBuilder = (new ItemBuilder())
            ->withAnotherShipment($shipmentBuilder);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherItem($itemBuilder)
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, new SaveOrderTransfer()];
    }

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAnd2ItemsToFranceAnd1ItemToGermany(): array
    {
        $addressBuilder1 = (new AddressBuilder(['iso2Code' => 'FR']));
        $shipmentTransfer1 = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder1)
            ->withAnotherMethod()
            ->build();
        $itemTransfer1 = (new ItemBuilder())->build();
        $itemTransfer1->setShipment($shipmentTransfer1);

        $itemTransfer2 = (new ItemBuilder())->build();
        $itemTransfer2->setShipment($shipmentTransfer1);

        $addressBuilder2 = (new AddressBuilder(['iso2Code' => 'DE']));
        $shipmentTransfer2 = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder2)
            ->withAnotherMethod()
            ->build();
        $itemTransfer3 = (new ItemBuilder())->build();
        $itemTransfer3->setShipment($shipmentTransfer2);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $quoteTransfer->addItem($itemTransfer1);
        $quoteTransfer->addItem($itemTransfer2);
        $quoteTransfer->addItem($itemTransfer3);

        return [$quoteTransfer, new SaveOrderTransfer()];
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