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
 * @group ShipmentTaxRateCalculatorForQuoteLevelShipmentTest
 * Add your own group annotations below this line
 */
class ShipmentTaxRateCalculatorForQuoteLevelShipmentTest extends Test
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
     * @dataProvider taxRateCalculationShouldUseQuoteShippingAddressAndShipmentDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param float $expectedValue
     *
     * @return void
     */
    public function testTaxRateCalculationShouldUseQuoteShippingAddressAndShipment(
        QuoteTransfer $quoteTransfer,
        float $expectedValue
    ): void {
        // Arrange
        $shipmentMethodTransfer = $quoteTransfer->getShipment()->getMethod();
        $shipmentMethodEntity = $this->tester->findShipmentMethodEntityByAddressIso2CodeInShipmentMethodEntityList(
            $quoteTransfer->getShipment()->getShippingAddress(),
            $this->shipmentMethodEntityList
        );
        $shipmentMethodTransfer = $this->tester->mapShipmentMethodEntityToTransfer($shipmentMethodTransfer, $shipmentMethodEntity);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productAbstractTransfer = $this->tester->haveProductWithTaxSetInDb($shipmentMethodEntity);
            $itemTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
        }

        // Act
        $this->tester->getFacade()->calculateShipmentTaxRate($quoteTransfer);

        // Assert
        $actualTaxRate = $shipmentMethodTransfer->getTaxRate();

        $this->assertEqualsWithDelta($expectedValue, $actualTaxRate, static::FLOAT_COMPARISION_DELTA,
            sprintf('Tax rate should be %.2f for shipment method ID %d, %.2f given.',
                $expectedValue, $shipmentMethodTransfer->getIdShipmentMethod(), $actualTaxRate
            )
        );
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
    protected function getDataWithQuoteLevelShippingAddressAndShipmentToFrance(): array
    {
        $skuProductAbstract = 'france_20';
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));
        $itemBuilder = (new ItemBuilder([
            'sku' => 'france_20',
        ]));

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

        return [$quoteTransfer, 20.00];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShippingAddressAndShipmentToMoon(): array
    {
        $skuProductAbstract = 'mars_0';
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'MARS']));
        $itemBuilder = (new ItemBuilder([
            'sku' => $skuProductAbstract,
        ]));

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

        return [$quoteTransfer, 0.00];
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
}