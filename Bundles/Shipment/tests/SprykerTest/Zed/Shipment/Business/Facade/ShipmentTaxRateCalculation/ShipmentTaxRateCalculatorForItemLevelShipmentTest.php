<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade\ShipmentTaxRateCalculation;

use Exception;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use BadMethodCallException;
use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\SaveOrderBuilder;
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
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
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
use Propel\Runtime\ActiveQuery\ModelCriteria;
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
 * @group ShipmentTaxRateCalculation
 * @group ShipmentTaxRateCalculatorForItemLevelShipmentTest
 * Add your own group annotations below this line
 */
class ShipmentTaxRateCalculatorForItemLevelShipmentTest extends Test
{
    protected const FLOAT_COMPARISION_DELTA = 0.001;

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
            ShipmentDependencyProvider::FACADE_TAX,
            $this->createShipmentToTaxFacadeBridgeMock('MOON', 0.00)
        );

        $this->tester->setDependency(
            ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_AFTER_CREATE,
            [new TaxSetProductAbstractAfterCreatePlugin()]
        );

        SpyTaxRateQuery::create()->update(['Rate' => '1']);

        $this->shipmentMethodEntityList = [];
        $this->shipmentMethodEntityList['FR'] = $this->tester->createShipmentMethodWithTaxSet(20.00, 'FR');
        $this->shipmentMethodEntityList['DE'] = $this->tester->createShipmentMethodWithTaxSet(15.00, 'DE');
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
        array $expectedValues
    ): void {
        // Arrange
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shipmentMethodEntity = $this->tester->findShipmentMethodEntityByAddressIso2CodeInShipmentMethodEntityList(
                $itemTransfer->getShipment()->getShippingAddress(),
                $this->shipmentMethodEntityList
            );
            $this->tester->mapShipmentMethodEntityToTransfer($itemTransfer->getShipment()->getMethod(), $shipmentMethodEntity);

            $productAbstractTransfer = $this->tester->haveProductWithTaxSetInDb($shipmentMethodEntity);
            $itemTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
        }

        $this->tester->setDependency(
            TaxDependencyProvider::STORE_CONFIG,
            $this->createTaxStoreMock('MOON')
        );

        $shipmentFacade = $this->tester->getFacade();

        // Act
        $shipmentFacade->calculateShipmentTaxRate($quoteTransfer);

        // Assert
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $expectedValue = $expectedValues[$itemTransfer->getSku()];
            $actualTaxRate = $itemTransfer->getShipment()->getMethod()->getTaxRate();

            $this->assertEqualsWithDelta($expectedValue, $actualTaxRate, static::FLOAT_COMPARISION_DELTA,
                sprintf('Tax rate should be %.2f, %.2f given.', $expectedValue, $actualTaxRate)
            );
        }
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
    protected function getDataWithItemLevelShippingAddressesToFranceWithTaxRate20(): array
    {
        $skuProductAbstract = 'france_20';
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentTransfer = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod()
            ->build();

        $expenseTransfer = (new ExpenseBuilder([
            'type' => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
        ]))->build();

        $itemTransfer = (new ItemBuilder([
            'sku' => $skuProductAbstract,
        ]))->build();

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

        return [$quoteTransfer, [$skuProductAbstract => 20.00]];
    }

    /**
     * @return array
     */
    protected function getDataWithItemLevelShippingAddressesToFranceWithTaxRate20AndGermanyWithTaxRate15(): array
    {
        $skuProductAbstract1 = 'france_20';
        $addressBuilder1 = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentTransfer1 = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder1)
            ->withAnotherMethod()
            ->build();

        $expenseTransfer1 = (new ExpenseBuilder([
            'type' => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
        ]))->build();

        $itemTransfer1 = (new ItemBuilder([
            'sku' => $skuProductAbstract1,
        ]))->build();


        $skuProductAbstract2 = 'germany_15';
        $addressBuilder2 = (new AddressBuilder(['iso2Code' => 'DE']));

        $shipmentTransfer2 = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder2)
            ->withAnotherMethod()
            ->build();

        $expenseTransfer2 = (new ExpenseBuilder([
            'type' => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
        ]))->build();

        $itemTransfer2 = (new ItemBuilder([
            'sku' => $skuProductAbstract2,
        ]))->build();

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

        return [$quoteTransfer, [$skuProductAbstract1 => 20.00, $skuProductAbstract2 => 15.00]];
    }

    /**
     * @return array
     */
    protected function getDataWithItemLevelShippingAddressesToMarsWithTaxRateUndefined(): array
    {
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

        $itemTransfer = (new ItemBuilder([
            'sku' => $skuProductAbstract,
        ]))->build();

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

        return [$quoteTransfer, [$skuProductAbstract => 0.00]];
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
}