<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxProductConnector\Business\Facade\ProductItemTaxRateCalculation;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Product\ProductDependencyProvider;
use Spryker\Zed\TaxProductConnector\Communication\Plugin\TaxSetProductAbstractAfterCreatePlugin;
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
 * @group ProductItemTaxRateCalculation
 * @group ProductItemTaxRateCalculatorForItemLevelShippingAddressTest
 * Add your own group annotations below this line
 */
class ProductItemTaxRateCalculatorForItemLevelShippingAddressTest extends Test
{
    protected const FLOAT_COMPARISION_DELTA = 0.001;

    /**
     * @var \SprykerTest\Zed\TaxProductConnector\TaxProductConnectorBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\TaxSetTransfer[]
     */
    protected $taxSetTransferList;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->tester->setDependency(
            TaxProductConnectorDependencyProvider::FACADE_TAX,
            $this->createTaxProductConnectorToTaxFacadeBridgeMock('MOON', 66.00)
        );

        $this->tester->setDependency(
            ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_AFTER_CREATE,
            [new TaxSetProductAbstractAfterCreatePlugin()]
        );

        $this->taxSetTransferList = [];
        $this->taxSetTransferList['FR'] = $this->tester->createTaxRateWithTaxSetInDb(20.00, 'FR');
        $this->taxSetTransferList['DE'] = $this->tester->createTaxRateWithTaxSetInDb(15.00, 'DE');
    }

    /**
     * @dataProvider taxRateCalculationShouldUseItemShippingAddressDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $expectedValues
     *
     * @return void
     */
    public function testTaxRateCalculationShouldUseItemShippingAddress(
        QuoteTransfer $quoteTransfer,
        array $expectedValues
    ): void {
        // Arrange
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $taxSetTransfer = $this->tester->findTaxSetByAddressIso2CodeInTaxSetTransferList(
                $itemTransfer->getShipment()->getShippingAddress()->getIso2Code(),
                $this->taxSetTransferList
            );
            $productAbstractTransfer = $this->tester->createProductWithTaxSetInDb($taxSetTransfer);
            $itemTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
        }

        // Act
        $this->tester->getFacade()->calculateProductItemTaxRate($quoteTransfer);

        // Assert
        foreach ($quoteTransfer->getItems() as $i => $itemTransfer) {
            $expectedTaxRate = $expectedValues[$itemTransfer->getSku()];
            $actualTaxRate = $itemTransfer->getTaxRate();

            $this->assertEqualsWithDelta(
                $expectedTaxRate,
                $actualTaxRate,
                static::FLOAT_COMPARISION_DELTA,
                sprintf('Tax rate should be %.2f, %.2f given at iteration #%d.', $expectedTaxRate, $actualTaxRate, $i)
            );
        }
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
    protected function getDataWithItemLevelShippingAddressesToFranceAndGermany(): array
    {
        $itemTransfer1 = $this->createItemTransfer('FR');
        $itemTransfer2 = $this->createItemTransfer('DE');

        $quoteTransfer = (new QuoteBuilder())->build();
        $quoteTransfer->addItem($itemTransfer1);
        $quoteTransfer->addItem($itemTransfer2);

        return [$quoteTransfer, [$itemTransfer1->getSku() => 20.00, $itemTransfer2->getSku() => 15.00]];
    }

    /**
     * @param string $iso2Code
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer(string $iso2Code): ItemTransfer
    {
        $addressBuilder1 = (new AddressBuilder([AddressTransfer::ISO2_CODE => $iso2Code]));

        return (new ItemBuilder())->withShipment(
            (new ShipmentBuilder())->withShippingAddress($addressBuilder1)
        )->build();
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
