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
 * @group ProductOptionTaxRateCalculationWithQuoteLevelShippingAddressTest
 * Add your own group annotations below this line
 */
class ProductOptionTaxRateCalculationWithQuoteLevelShippingAddressTest extends Unit
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
                        ['netAmount' => 20000],
                    ],
                ],
            ]
        );
    }

    /**
     * @dataProvider productOptionTaxRateCalculatorShouldUseQuoteShippingAddress
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param float $expectedTaxRate
     *
     * @return void
     */
    public function testProductOptionTaxCalculatorShouldUseQuoteShippingAddress(
        QuoteTransfer $quoteTransfer,
        float $expectedTaxRate
    ): void {
        // Arrange
        $this->tester->setDependency(
            ProductOptionDependencyProvider::FACADE_TAX,
            $this->createProductOptionToTaxFacadeBridgeMock('MOON', 66.00)
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
                $this->assertEquals(
                    $expectedTaxRate,
                    $productOptionTransfer->getTaxRate(),
                    sprintf(
                        'Actual tax rate for product option is not valid. Expected %.2f, %.2f given. Iteration #%d, option sku %s',
                        $expectedTaxRate,
                        $productOptionTransfer->getTaxRate(),
                        $iterator,
                        $productOptionTransfer->getSku()
                    )
                );
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
    public function productOptionTaxRateCalculatorShouldUseQuoteShippingAddress(): array
    {
        return [
            'quote has one item with one option, shipping address: France, expected tax rate 20%' => $this->getQuoteWithOneItemWithOneOptionAndQuoteLevelShippingAddressToFrance(),
            'quote has one item with one option, shipping address: Moon, expected tax rate 66%' => $this->getQuoteWithOneItemWithOneOptionAndQuoteLevelShippingAddressToMoon(),
            'quote has one item with two options, shipping address: Germany, expected tax rate 15%' => $this->getQuoteWithOneItemWithTwoOptionsAndQuoteLevelShippingAddressToGermany(),
            'quote has two items item with one option, shipping address: Germany, expected tax rate 15%' => $this->getQuoteWithTwoItemsWithOneOptionAndQuoteLevelShippingAddressToGermany(),
        ];
    }

    /**
     * @return array
     */
    public function getQuoteWithOneItemWithOneOptionAndQuoteLevelShippingAddressToFrance(): array
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress(
                (new AddressBuilder(['iso2Code' => 'FR']))
            )
            ->build();

        $itemTransfer = (new ItemBuilder())
            ->withProductOption([ProductOptionValueTransfer::SKU => static::PRODUCT_OPTION_VALUE_SKU_1])
            ->build();

        $quoteTransfer->addItem($itemTransfer);

        return [$quoteTransfer, 20.00];
    }

    /**
     * @return array
     */
    public function getQuoteWithOneItemWithOneOptionAndQuoteLevelShippingAddressToMoon(): array
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress(
                (new AddressBuilder(['iso2Code' => 'MOON']))
            )
            ->build();

        $itemTransfer = (new ItemBuilder())
            ->withProductOption([ProductOptionValueTransfer::SKU => static::PRODUCT_OPTION_VALUE_SKU_1])
            ->build();

        $quoteTransfer->addItem($itemTransfer);

        return [$quoteTransfer, 66.00];
    }

    /**
     * @return array
     */
    public function getQuoteWithOneItemWithTwoOptionsAndQuoteLevelShippingAddressToGermany(): array
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress(
                (new AddressBuilder(['iso2Code' => 'DE']))
            )
            ->build();

        $itemTransfer = (new ItemBuilder())
            ->withProductOption([ProductOptionValueTransfer::SKU => static::PRODUCT_OPTION_VALUE_SKU_1])
            ->withAnotherProductOption([ProductOptionTransfer::SKU => static::PRODUCT_OPTION_VALUE_SKU_2])
            ->build();

        $quoteTransfer->addItem($itemTransfer);

        return [$quoteTransfer, 15.00];
    }

    /**
     * @return array
     */
    public function getQuoteWithTwoItemsWithOneOptionAndQuoteLevelShippingAddressToGermany(): array
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress(
                (new AddressBuilder(['iso2Code' => 'DE']))
            )
            ->build();

        $itemTransfer1 = (new ItemBuilder())
            ->withProductOption([ProductOptionTransfer::SKU => static::PRODUCT_OPTION_VALUE_SKU_1])
            ->build();
        $itemTransfer2 = (new ItemBuilder())
            ->withAnotherProductOption([ProductOptionTransfer::SKU => static::PRODUCT_OPTION_VALUE_SKU_2])
            ->build();

        $quoteTransfer
            ->addItem($itemTransfer1)
            ->addItem($itemTransfer2);

        return [$quoteTransfer, 15.00];
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
