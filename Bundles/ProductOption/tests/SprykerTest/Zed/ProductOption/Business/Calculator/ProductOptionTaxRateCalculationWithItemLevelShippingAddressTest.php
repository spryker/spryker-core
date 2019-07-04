<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business\Calculator;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\ProductOptionBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery;
use Pyz\Zed\ProductOption\ProductOptionDependencyProvider;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxFacadeBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxFacadeInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Business
 * @group Calculator
 * @group ProductOptionTaxRateCalculationWithItemLevelShippingAddressTest
 * Add your own group annotations below this line
 */
class ProductOptionTaxRateCalculationWithItemLevelShippingAddressTest extends Unit
{
    protected const TAX_SET_NAME = 'test.tax.set';

    protected const PRODUCT_OPTION_VALUE_SKU_1 = 'test.product.option.value.sku.1';
    protected const PRODUCT_OPTION_VALUE_SKU_2 = 'test.product.option.value.sku.2';

    /**
     * @var \SprykerTest\Zed\ProductOption\ProductOptionBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $taxSetTransfer = $this->tester->haveTaxSetWithTaxRates([TaxSetTransfer::NAME => static::TAX_SET_NAME], [
            [
                TaxRateTransfer::FK_COUNTRY => $this->tester->getCountryIdByIso2Code('DE'),
                TaxRateTransfer::RATE => 15.00,
            ],
            [
                TaxRateTransfer::FK_COUNTRY => $this->tester->getCountryIdByIso2Code('FR'),
                TaxRateTransfer::RATE => 20.00,
            ],
        ]);

        $this->tester->haveProductOptionGroupWithValues(
            [ProductOptionGroupTransfer::FK_TAX_SET => $taxSetTransfer->getIdTaxSet()],
            [
                [
                    [ProductOptionValueTransfer::SKU => static::PRODUCT_OPTION_VALUE_SKU_1],
                    [
                        ['netAmount' => 10000],
                    ],
                ],
                [
                    [ProductOptionValueTransfer::SKU => static::PRODUCT_OPTION_VALUE_SKU_2],
                    [
                        ['netAmount' => 10000],
                    ],
                ],
            ]
        );
    }

    /**
     * @dataProvider productOptionTaxRateCalculatorShouldUseItemShippingAddress
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $expectedTaxRates
     *
     * @return void
     */
    public function testProductOptionTaxCalculatorShouldUseItemShippingAddress(
        QuoteTransfer $quoteTransfer,
        array $expectedTaxRates
    ): void {
        // Arrange
        $this->tester->setDependency(
            ProductOptionDependencyProvider::FACADE_TAX,
            $this->createProductOptionToTaxFacadeBridgeMock('MOON', 0.00)
        );

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $productOptionTransfer->setIdProductOptionValue(
                    $this->getProductOptionValueIdBySku($productOptionTransfer->getSku())
                );
            }
        }

        // Act
        $this->tester->getFacade()->calculateProductOptionTaxRate($quoteTransfer);

        // Assert
        foreach ($quoteTransfer->getItems() as $iterator => $itemTransfer) {
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $iso2Code = $itemTransfer->getShipment()
                    ->getShippingAddress()
                    ->getIso2Code();
                $this->assertEquals(
                    $expectedTaxRates[$iso2Code],
                    $productOptionTransfer->getTaxRate(),
                    sprintf(
                        'Actual tax rate for product option is not valid. Expected %.2f, %.2f given. Iteration #%d, option sku %s',
                        $expectedTaxRates[$iso2Code],
                        $productOptionTransfer->getTaxRate(),
                        $iterator,
                        $productOptionTransfer->getSku()
                    )
                );
            }
        }
    }

    /**
     * @return array
     */
    public function productOptionTaxRateCalculatorShouldUseItemShippingAddress(): array
    {
        return [
            'quote has one item with two options, shipping address: Germany, expected tax rate 15%' => $this->getQuoteWithOneItemWithTwoOptionsAndShippingAddressToGermany(),
            'quote has two items with one option, shipping address: France and Germany, expected tax rate 20% and 15%' => $this->getQuoteWithTwoItemsWithOneOptionAndDifferentShippingAddresses(),
        ];
    }

    /**
     * @return array
     */
    public function getQuoteWithOneItemWithTwoOptionsAndShippingAddressToGermany(): array
    {
        $quoteTransfer = (new QuoteBuilder())
            ->build();

        $shipmentBuilder = (new ShipmentBuilder())
            ->withShippingAddress([AddressTransfer::ISO2_CODE => 'DE']);

        $itemTransfer = (new ItemBuilder())
            ->withShipment($shipmentBuilder)
            ->withProductOption([ProductOptionTransfer::SKU => static::PRODUCT_OPTION_VALUE_SKU_1])
            ->withAnotherProductOption([ProductOptionTransfer::SKU => static::PRODUCT_OPTION_VALUE_SKU_2])
            ->build();

        $quoteTransfer->addItem($itemTransfer);

        return [$quoteTransfer, ['DE' => 15.00]];
    }

    /**
     * @return array
     */
    public function getQuoteWithTwoItemsWithOneOptionAndDifferentShippingAddresses(): array
    {
        $quoteTransfer = (new QuoteBuilder())
            ->build();

        $productOptionBuilder = (new ProductOptionBuilder([ProductOptionTransfer::SKU => static::PRODUCT_OPTION_VALUE_SKU_1]));

        $shipmentBuilder1 = (new ShipmentBuilder())
            ->withShippingAddress([AddressTransfer::ISO2_CODE => 'FR'])
            ->build();

        $itemTransfer1 = (new ItemBuilder())
            ->withShipment($shipmentBuilder1->toArray())
            ->withProductOption($productOptionBuilder)
            ->build();

        $shipmentBuilder2 = (new ShipmentBuilder())
            ->withAnotherShippingAddress([AddressTransfer::ISO2_CODE => 'DE'])
            ->build();

        $itemTransfer2 = (new ItemBuilder())
            ->withAnotherShipment($shipmentBuilder2->toArray())
            ->withProductOption($productOptionBuilder)
            ->build();

        $quoteTransfer->addItem($itemTransfer1);
        $quoteTransfer->addItem($itemTransfer2);

        return [$quoteTransfer, ['DE' => 15.00, 'FR' => 20.00]];
    }

    /**
     * @param string $defaultCountryIso2Code
     * @param float $defaultTaxRate
     *
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxFacadeInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createProductOptionToTaxFacadeBridgeMock(string $defaultCountryIso2Code, float $defaultTaxRate): ProductOptionToTaxFacadeInterface
    {
        $bridgeMock = $this->getMockBuilder(ProductOptionToTaxFacadeBridge::class)
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
     * @param string $sku
     *
     * @return int
     */
    protected function getProductOptionValueIdBySku(string $sku): int
    {
        $productOptionValueEntity = SpyProductOptionValueQuery::create()->findOneBySku($sku);

        return $productOptionValueEntity->getIdProductOptionValue();
    }
}
