<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business\Calculator;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
 * @group ProductOptionTaxRateCalculatonWithQuoteItemLevelShippingAddressTest
 * Add your own group annotations below this line
 */
class ProductOptionTaxRateCalculatonWithQuoteItemLevelShippingAddressTest extends Unit
{
    protected const TAX_SET_NAME = 'test.tax.set';

    protected const PRODUCT_OPTION_VALUE_1 = 'test.product.option.value.1';
    protected const PRODUCT_OPTION_VALUE_2 = 'test.product.option.value.2';

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

        $taxSetTransfer = $this->tester->haveTaxSetWithTaxRates(['name' => static::TAX_SET_NAME], [
            [
                'fk_country' => '60',
                'name' => 'test tax rate 1',
                'rate' => 15.00,
            ],
            [
                'fk_country' => '79',
                'name' => 'test tax rate 2',
                'rate' => 20.00,
            ],
        ]);

        $productOptionGroupTransfer = $this->tester->haveProductOptionGroupWithValues(
            ['fk_tax_set' => $taxSetTransfer->getIdTaxSet()],
            [
                [
                    ['sku' => static::PRODUCT_OPTION_VALUE_1],
                ],
                [
                    ['sku' => static::PRODUCT_OPTION_VALUE_2],
                ],
            ]
        );
    }

    /**
     * @dataProvider productOptionTaxRateCalculatorShouldUseItemShippingAddress
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $expectedResult
     *
     * @return void
     */
    public function testProductOptionTaxCalculatorShouldUseItemShippingAddress(
        QuoteTransfer $quoteTransfer,
        array $expectedResult
    ): void {
        $this->tester->setDependency(
            ProductOptionDependencyProvider::FACADE_TAX,
            $this->createProductOptionToTaxFacadeBridgeMock('Moon', 66.00)
        );

        $this->tester->getFacade()->calculateProductOptionTaxRate($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $iso2Code = $itemTransfer->getShipment()
                    ->getShippingAddress()
                    ->getIso2Code();
                $this->assertEquals($expectedResult[$iso2Code], $productOptionTransfer->getTaxRate(), "Expected result - {$expectedResult[$iso2Code]}, Actual result - {$productOptionTransfer->getTaxRate()}");
            }
        }
    }

    /**
     * @param string $defaultCountryIso2Code
     * @param float $defaultTaxRate
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxFacadeInterface
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
     * @return array
     */
    public function productOptionTaxRateCalculatorShouldUseItemShippingAddress(): array
    {
        return [
            'quote has one item with two options, shipping address: France, expected tax rate 15%' => $this->getQuoteWithOneItemWithTwoOptionsAndShippingAddressToGermany(),
            'quote has one item with one option, shipping address: Moon, expected tax rate 20% and 15%' => $this->getQuoteWithTwoItemsWithOneOptionAndDifferentShippinggAdresses(),
        ];
    }

    /**
     * @return array
     */
    public function getQuoteWithOneItemWithTwoOptionsAndShippingAddressToGermany(): array
    {
        $expectedResult = ['DE' => 15.00];
        $productOptionValueEntity1 = SpyProductOptionValueQuery::create()->findBySku(static::PRODUCT_OPTION_VALUE_1);
        $productOptionValueEntity2 = SpyProductOptionValueQuery::create()->findBySku(static::PRODUCT_OPTION_VALUE_2);
        $quoteTransfer = (new QuoteBuilder())
            ->withItem(
                (new ItemBuilder())
                    ->withShipment(
                        (new ShipmentBuilder())
                        ->withShippingAddress([AddressTransfer::ISO2_CODE => 'DE'])
                    )
                    ->withProductOption([
                        'id_product_option_value' => $productOptionValueEntity1[0]->getIdProductOptionValue(),
                    ])
                    ->withAnotherProductOption([
                        'id_product_option_value' => $productOptionValueEntity2[0]->getIdProductOptionValue(),
                    ])
            )
            ->build();

        return [$quoteTransfer, $expectedResult];
    }

    /**
     * @return array
     */
    public function getQuoteWithTwoItemsWithOneOptionAndDifferentShippinggAdresses(): array
    {
        $expectedResult = ['FR' => 20.00, 'DE' => 15.00];
        $productOptionValueEntity = SpyProductOptionValueQuery::create()->findBySku(static::PRODUCT_OPTION_VALUE_1);
        $quoteTransfer = (new QuoteBuilder())
            ->withItem(
                (new ItemBuilder())
                    ->withShipment(
                        (new ShipmentBuilder())
                        ->withShippingAddress([AddressTransfer::ISO2_CODE => 'FR'])
                    )
                    ->withProductOption([
                        'id_product_option_value' => $productOptionValueEntity[0]->getIdProductOptionValue(),
                    ])
            )
            ->withAnotherItem(
                (new ItemBuilder())
                    ->withShipment(
                        (new ShipmentBuilder())
                            ->withShippingAddress([AddressTransfer::ISO2_CODE => 'DE'])
                    )
                    ->withProductOption([
                        'id_product_option_value' => $productOptionValueEntity[0]->getIdProductOptionValue(),
                    ])
            )
            ->build();

        return [$quoteTransfer, $expectedResult];
    }
}
