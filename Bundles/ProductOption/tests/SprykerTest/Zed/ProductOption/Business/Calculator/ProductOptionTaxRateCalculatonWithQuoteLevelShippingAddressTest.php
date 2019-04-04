<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business\Calculator;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
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
 * @group ProductOptionTaxRateCalculatonWithQuoteLevelShippingAddressTest
 * Add your own group annotations below this line
 */
class ProductOptionTaxRateCalculatonWithQuoteLevelShippingAddressTest extends Unit
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
            ],
            [
                [
                    ['sku' => static::PRODUCT_OPTION_VALUE_2],
                ],
            ]
        );
    }

    /**
     * @dataProvider productOptionTaxRateCalculatorShouldUseQuoteShippingAddress
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param float $expectedResult
     *
     * @return void
     */
    public function testProductOptionTaxCalculatorShouldUseQuoteShippingAddress(
        QuoteTransfer $quoteTransfer,
        float $expectedResult
    ): void {
        $this->tester->setDependency(
            ProductOptionDependencyProvider::FACADE_TAX,
            $this->createProductOptionToTaxFacadeBridgeMock('Moon', 66.00)
        );

        $this->tester->getFacade()->calculateProductOptionTaxRate($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $this->assertEquals($expectedResult, $productOptionTransfer->getIdProductOptionValue(), "Expected result - {$expectedResult}, Actual result - {$productOptionTransfer->getTaxRate()}");
            }
        }
    }

    /**
     * @param string $defaultCountryIso2Code
     * @param float $defaultTaxRate
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxFacadeInterface
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
    public function productOptionTaxRateCalculatorShouldUseQuoteShippingAddress(): array
    {
        return [
            'quote has one item with one option, shipping address: France, expected tax rate 20%' => $this->getQuoteWithOneItemWithOneOptionAndQuoteLevelShippingAddressToFrance(),
            'quote has one item with one option, shipping address: Moon, expected tax rate 66%' => $this->getQuoteWithOneItemWithOneOptionAndQuoteLevelShippingAddressToMoon(),
            'quote has one item with two options, shipping address: Germany, expected tax rate 15%' => $this->getQuoteWithOneItemWithTwoOptionsAndQuoteLevelShippingAddressToGermany(),
            'quote has two items item with one option, shipping address: Germany, expected tax rate 15%' => $this->getQuoteWithTwoItemsWithOneOptionAndQuoteLevelShippingAddressToFrance(),
        ];
    }

    /**
     * @return array
     */
    public function getQuoteWithOneItemWithOneOptionAndQuoteLevelShippingAddressToFrance(): array
    {
        $productOptionValueEntity = SpyProductOptionValueQuery::create()->findBySku(static::PRODUCT_OPTION_VALUE_1);
        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress(
                (new AddressBuilder(['iso2Code' => 'DE']))
            )
            ->withItem(
                (new ItemBuilder())
                    ->withProductOption([
                        'id_product_option_value' => $productOptionValueEntity[0]->getIdProductOptionValue(),
                    ])
            )
            ->build();

        return [$quoteTransfer, 15.00];
    }

    /**
     * @return array
     */
    public function getQuoteWithOneItemWithOneOptionAndQuoteLevelShippingAddressToMoon(): array
    {
        $productOptionValueEntity = SpyProductOptionValueQuery::create()->findBySku(static::PRODUCT_OPTION_VALUE_1);
        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress(
                (new AddressBuilder(['iso2Code' => 'Moon']))
            )
            ->withBillingAddress()
            ->withItem(
                (new ItemBuilder())
                    ->withProductOption([
                        'id_product_option_value' => $productOptionValueEntity[0]->getIdProductOptionValue(),
                    ])
            )
            ->withCustomer()
            ->withCurrency()
            ->withTotals()
            ->build();

        return [$quoteTransfer, 66.00];
    }

    /**
     * @return array
     */
    public function getQuoteWithOneItemWithTwoOptionsAndQuoteLevelShippingAddressToGermany(): array
    {
        $productOptionValueEntity1 = SpyProductOptionValueQuery::create()->findBySku(static::PRODUCT_OPTION_VALUE_1);
        $productOptionValueEntity2 = SpyProductOptionValueQuery::create()->findBySku(static::PRODUCT_OPTION_VALUE_2);
        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress(
                (new AddressBuilder(['iso2Code' => 'DE']))
            )
            ->withBillingAddress()
            ->withItem(
                (new ItemBuilder())
                    ->withProductOption([
                        'id_product_option_value' => $productOptionValueEntity1[0]->getIdProductOptionValue(),
                    ])
                    ->withAnotherProductOption([
                        'id_product_option_value' => $productOptionValueEntity2[0]->getIdProductOptionValue(),
                    ])
            )
            ->withCustomer()
            ->withCurrency()
            ->withTotals()
            ->build();

        return [$quoteTransfer, 15.00];
    }

    /**
     * @return array
     */
    public function getQuoteWithTwoItemsWithOneOptionAndQuoteLevelShippingAddressToFrance(): array
    {
        $productOptionValueEntity = SpyProductOptionValueQuery::create()->findBySku(static::PRODUCT_OPTION_VALUE_1);
        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress(
                (new AddressBuilder(['iso2Code' => 'FR']))
            )
            ->withBillingAddress()
            ->withItem(
                (new ItemBuilder())
                    ->withProductOption([
                        'id_product_option_value' => $productOptionValueEntity[0]->getIdProductOptionValue(),
                    ])
            )
            ->withAnotherItem(
                (new ItemBuilder())
                ->withProductOption([
                    'id_product_option_value' => $productOptionValueEntity[0]->getIdProductOptionValue(),
                ])
            )
            ->withCustomer()
            ->withCurrency()
            ->withTotals()
            ->build();

        return [$quoteTransfer, 15.00];
    }
}
