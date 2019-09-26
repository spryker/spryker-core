<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade\ShipmentTaxRateCalculation;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Shipment\ShipmentConfig;
use Spryker\Zed\Product\ProductDependencyProvider;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxBridge;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;
use Spryker\Zed\Tax\TaxDependencyProvider;
use Spryker\Zed\TaxProductConnector\Communication\Plugin\TaxSetProductAbstractAfterCreatePlugin;

/**
 * Auto-generated group annotations
 *
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
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    protected $shipmentMethodTransferList;

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

        $this->tester->setDependency(
            TaxDependencyProvider::STORE_CONFIG,
            $this->createTaxStoreMock('MOON')
        );

        $this->shipmentMethodTransferList = [];
        $this->shipmentMethodTransferList['FR'] = $this->tester->createShipmentMethodWithTaxSet(20.00, 'FR');
        $this->shipmentMethodTransferList['DE'] = $this->tester->createShipmentMethodWithTaxSet(15.00, 'DE');
    }

    /**
     * @dataProvider taxRateCalculationShouldUseItemLevelShippingAddressAndShipmentDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $expectedValues
     *
     * @return void
     */
    public function testTaxRateCalculationShouldUseItemLevelShippingAddressAndShipment(
        QuoteTransfer $quoteTransfer,
        array $expectedValues
    ): void {
        // Arrange
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemShipmentMethodTransfer = $itemTransfer->getShipment()->getMethod();
            $shipmentMethodTransfer = $this->tester->findShipmentMethodByAddressIso2CodeInShipmentMethodTransferList(
                $itemTransfer->getShipment()->getShippingAddress()->getIso2Code(),
                $this->shipmentMethodTransferList
            );
            if ($shipmentMethodTransfer !== null) {
                $itemShipmentMethodTransfer->setIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethod());
            }

            $productAbstractTransfer = $this->tester->createProductWithTaxSetInDb($itemShipmentMethodTransfer);
            $itemTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
        }

        $shipmentFacade = $this->tester->getFacade();

        // Act
        $shipmentFacade->calculateShipmentTaxRate($quoteTransfer);

        // Assert
        foreach ($quoteTransfer->getItems() as $i => $itemTransfer) {
            $expectedValue = $expectedValues[$itemTransfer->getSku()];
            $expenseTransfer = $this->findQuoteExpenseByShipment($quoteTransfer, $itemTransfer->getShipment());

            $this->assertEqualsWithDelta(
                $expectedValue,
                $itemTransfer->getShipment()->getMethod()->getTaxRate(),
                static::FLOAT_COMPARISION_DELTA,
                sprintf('The actual shipment method tax rate is invalid (iteration #%d).', $i)
            );

            $this->assertEqualsWithDelta(
                $expectedValue,
                $expenseTransfer->getTaxRate(),
                static::FLOAT_COMPARISION_DELTA,
                sprintf('The actual shipment expense tax rate is invalid (iteration #%d).', $i)
            );
        }
    }

    /**
     * @return array
     */
    public function taxRateCalculationShouldUseItemLevelShippingAddressAndShipmentDataProvider(): array
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
        $quoteTransfer = (new QuoteBuilder())->build();
        $itemTransfer = $this->addNewItemAndExpenseIntoQuoteTransfer($quoteTransfer, 'FR');

        return [$quoteTransfer, [$itemTransfer->getSku() => 20.00]];
    }

    /**
     * @return array
     */
    protected function getDataWithItemLevelShippingAddressesToFranceWithTaxRate20AndGermanyWithTaxRate15(): array
    {
        $quoteTransfer = (new QuoteBuilder())->build();
        $itemTransfer1 = $this->addNewItemAndExpenseIntoQuoteTransfer($quoteTransfer, 'FR');
        $itemTransfer2 = $this->addNewItemAndExpenseIntoQuoteTransfer($quoteTransfer, 'DE');

        return [$quoteTransfer, [$itemTransfer1->getSku() => 20.00, $itemTransfer2->getSku() => 15.00]];
    }

    /**
     * @return array
     */
    protected function getDataWithItemLevelShippingAddressesToMarsWithTaxRateUndefined(): array
    {
        $quoteTransfer = (new QuoteBuilder())->build();
        $itemTransfer = $this->addNewItemAndExpenseIntoQuoteTransfer($quoteTransfer, 'MARS');

        return [$quoteTransfer, [$itemTransfer->getSku() => 0.00]];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $iso2Code
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function addNewItemAndExpenseIntoQuoteTransfer(QuoteTransfer $quoteTransfer, string $iso2Code): ItemTransfer
    {
        $addressBuilder = (new AddressBuilder([AddressTransfer::ISO2_CODE => $iso2Code]));
        $shipmentTransfer = (new ShipmentBuilder())
            ->withShippingAddress($addressBuilder)
            ->withMethod()
            ->build();

        $itemTransfer = (new ItemBuilder())->build();
        $itemTransfer->setShipment($shipmentTransfer);

        $expenseTransfer = (new ExpenseBuilder([
            ExpenseTransfer::TYPE => ShipmentConfig::SHIPMENT_EXPENSE_TYPE,
        ]))->build();
        $expenseTransfer->setShipment($shipmentTransfer);

        $quoteTransfer->addItem($itemTransfer);
        $quoteTransfer->addExpense($expenseTransfer);

        return $itemTransfer;
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer|null
     */
    protected function findQuoteExpenseByShipment(QuoteTransfer $quoteTransfer, ShipmentTransfer $shipmentTransfer): ?ExpenseTransfer
    {
        $itemShipmentKey = $this->tester->getShipmentService()->getShipmentHashKey($shipmentTransfer);
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $expenseShipmentKey = $this->tester->getShipmentService()->getShipmentHashKey($expenseTransfer->getShipment());
            if ($expenseTransfer->getType() === ShipmentConfig::SHIPMENT_EXPENSE_TYPE
                && $expenseTransfer->getShipment() !== null
                && $expenseShipmentKey === $itemShipmentKey
            ) {
                return $expenseTransfer;
            }
        }

        return null;
    }
}
