<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade\ShipmentTaxRateCalculation;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Shipment\ShipmentConfig;
use Spryker\Zed\Product\ProductDependencyProvider;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxBridge;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;
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
 * @group ShipmentTaxRateCalculatorForQuoteLevelShipmentTest
 * Add your own group annotations below this line
 */
class ShipmentTaxRateCalculatorForQuoteLevelShipmentTest extends Test
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

        $this->shipmentMethodTransferList = [];
        $this->shipmentMethodTransferList['FR'] = $this->tester->createShipmentMethodWithTaxSet(20.00, 'FR');
        $this->shipmentMethodTransferList['DE'] = $this->tester->createShipmentMethodWithTaxSet(15.00, 'DE');
    }

    /**
     * @dataProvider taxRateCalculationShouldUseQuoteLevelShippingAddressAndShipmentDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param float $expectedValue
     *
     * @return void
     */
    public function testTaxRateCalculationShouldUseQuoteLevelShippingAddressAndShipment(
        QuoteTransfer $quoteTransfer,
        float $expectedValue
    ): void {
        // Arrange
        $quoteShipmentMethodTransfer = $quoteTransfer->getShipment()->getMethod();
        $shipmentMethodTransfer = $this->tester->findShipmentMethodByAddressIso2CodeInShipmentMethodTransferList(
            $quoteTransfer->getShipment()->getShippingAddress()->getIso2Code(),
            $this->shipmentMethodTransferList
        );
        if ($shipmentMethodTransfer !== null) {
            $quoteShipmentMethodTransfer->setIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethod());
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productAbstractTransfer = $this->tester->createProductWithTaxSetInDb($quoteShipmentMethodTransfer);
            $itemTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
        }

        // Act
        $this->tester->getFacade()->calculateShipmentTaxRate($quoteTransfer);

        // Assert
        $this->assertEqualsWithDelta(
            $expectedValue,
            $quoteTransfer->getShipment()->getMethod()->getTaxRate(),
            static::FLOAT_COMPARISION_DELTA,
            sprintf(
                'The actual shipment methdo tax rate is invalid.'
            )
        );

        $this->assertEqualsWithDelta(
            $expectedValue,
            $quoteTransfer->getExpenses()[0]->getTaxRate(),
            static::FLOAT_COMPARISION_DELTA,
            sprintf(
                'The actual shipment expense tax rate is invalid.'
            )
        );
    }

    /**
     * @return array
     */
    public function taxRateCalculationShouldUseQuoteLevelShippingAddressAndShipmentDataProvider(): array
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
        $addressTransfer = (new AddressBuilder([AddressTransfer::ISO2_CODE => 'FR']))->build();

        $shipmentTransfer = (new ShipmentBuilder())
            ->withMethod()
            ->build();
        $shipmentTransfer->setShippingAddress($addressTransfer);

        $expenseTransfer = (new ExpenseBuilder([
            ExpenseTransfer::TYPE => ShipmentConfig::SHIPMENT_EXPENSE_TYPE,
            ExpenseTransfer::NAME => $shipmentTransfer->getMethod()->getName(),
        ]))->build();
        $expenseTransfer->setShipment($shipmentTransfer);

        $quoteTransfer = (new QuoteBuilder())->build();
        $quoteTransfer->setShippingAddress($addressTransfer);
        $quoteTransfer->setShipment($shipmentTransfer);
        $quoteTransfer->addExpense($expenseTransfer);

        return [$quoteTransfer, 20.00];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShippingAddressAndShipmentToMoon(): array
    {
        $addressTransfer = (new AddressBuilder([AddressTransfer::ISO2_CODE => 'MARS']))->build();

        $shipmentTransfer = (new ShipmentBuilder())
            ->withMethod()
            ->build();
        $shipmentTransfer->setShippingAddress($addressTransfer);

        $expenseTransfer = (new ExpenseBuilder([
            ExpenseTransfer::TYPE => ShipmentConfig::SHIPMENT_EXPENSE_TYPE,
            ExpenseTransfer::NAME => $shipmentTransfer->getMethod()->getName(),
        ]))->build();
        $expenseTransfer->setShipment($shipmentTransfer);

        $quoteTransfer = (new QuoteBuilder())->build();
        $quoteTransfer->setShippingAddress($addressTransfer);
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
