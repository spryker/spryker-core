<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business\Calculator;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxFacadeBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxFacadeInterface;
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Business
 * @group Calculator
 * @group ProductOptionTaxRateCalculationWithDefaultTaxRatesTest
 * Add your own group annotations below this line
 */
class ProductOptionTaxRateCalculationWithDefaultTaxRatesTest extends Unit
{
    protected const TAX_SET_NAME = 'test.tax.set';

    protected const PRODUCT_OPTION_VALUE_SKU = 'test.product.option.value.sku';

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
                    [ProductOptionValueTransfer::SKU => static::PRODUCT_OPTION_VALUE_SKU],
                    [
                        ['netAmount' => 10000],
                    ],
                ],
            ]
        );
    }

    /**
     * @dataProvider productOptionTaxRateCalculatorWithDefaultTaxRates
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $defaultCountryIso2Code
     * @param float $expectedTaxRate
     *
     * @return void
     */
    public function testProductOptionTaxCalculatorShouldUseDefaultStoreCountry(
        QuoteTransfer $quoteTransfer,
        string $defaultCountryIso2Code,
        float $expectedTaxRate
    ): void {
        // Arrange
        $this->tester->setDependency(
            ProductOptionDependencyProvider::FACADE_TAX,
            $this->createProductOptionToTaxFacadeBridgeMock($defaultCountryIso2Code, 0.00)
        );

        $productOptionValueEntity = SpyProductOptionValueQuery::create()->findOneBySku(static::PRODUCT_OPTION_VALUE_SKU);
        $quoteTransfer->getItems()[0]
            ->getProductOptions()[0]
            ->setIdProductOptionValue($productOptionValueEntity->getIdProductOptionValue());

        // Act
        $this->tester->getFacade()->calculateProductOptionTaxRate($quoteTransfer);

        // Assert
        $this->assertEquals(
            $expectedTaxRate,
            $quoteTransfer->getItems()[0]->getProductOptions()[0]->getTaxRate(),
            sprintf(
                'Actual tax rate for product option is not valid. Expected %.2f, %.2f given.',
                $expectedTaxRate,
                $quoteTransfer->getItems()[0]->getProductOptions()[0]->getTaxRate()
            )
        );
    }

    /**
     * @return array
     */
    public function productOptionTaxRateCalculatorWithDefaultTaxRates(): array
    {
        return [
            'quote has one item with one option; no shipping address; default store country France; expected rate 20%' => $this->getQuoteWithOneItemWithoutShippingAddressesAndDefaultCountryIsFrance(),
            'quote has one item with one option; no shipping address; default store country Germany; expected rate 15%' => $this->getQuoteWithOneItemWithoutShippingAddressesAndDefaultCountryIsGermany(),
            'quote has one item with one option; no shipping address; default store country Moon; expected rate 0%' => $this->getQuoteWithOneItemWithoutShippingAddressesAndDefaultCountryIsMoon(),
        ];
    }

    /**
     * @return array
     */
    public function getQuoteWithOneItemWithoutShippingAddressesAndDefaultCountryIsFrance(): array
    {
        $itemTransfer = (new ItemBuilder())
            ->withProductOption([ProductOptionValueTransfer::SKU => static::PRODUCT_OPTION_VALUE_SKU])
            ->build();

        $quoteTransfer = (new QuoteBuilder())->build();
        $quoteTransfer->addItem($itemTransfer);

        return [$quoteTransfer, 'FR', 20.00];
    }

    /**
     * @return array
     */
    public function getQuoteWithOneItemWithoutShippingAddressesAndDefaultCountryIsGermany(): array
    {
        $itemTransfer = (new ItemBuilder())
            ->withProductOption([ProductOptionValueTransfer::SKU => static::PRODUCT_OPTION_VALUE_SKU])
            ->build();

        $quoteTransfer = (new QuoteBuilder())->build();
        $quoteTransfer->addItem($itemTransfer);

        return [$quoteTransfer, 'DE', 15.00];
    }

    /**
     * @return array
     */
    public function getQuoteWithOneItemWithoutShippingAddressesAndDefaultCountryIsMoon(): array
    {
        $itemTransfer = (new ItemBuilder())
            ->withProductOption([ProductOptionValueTransfer::SKU => static::PRODUCT_OPTION_VALUE_SKU])
            ->build();

        $quoteTransfer = (new QuoteBuilder())->build();
        $quoteTransfer->addItem($itemTransfer);

        return [$quoteTransfer, 'MOON', 0.00];
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
}
