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
 * @group ProductOptionTaxRateCalculatonWithDefaultTaxRatesTest
 * Add your own group annotations below this line
 */
class ProductOptionTaxRateCalculatonWithDefaultTaxRatesTest extends Unit
{
    protected const TAX_SET_NAME = 'test.tax.set';

    protected const PRODUCT_OPTION_VALUE = 'test.product.option.value';

    /**
     * @var array
     */
    protected $defaultCountryListWithExpectedRate = [
        'DE' => 15.00,
        'FR' => 20.00,
        'MOON' => 0.00,
    ];

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
                TaxRateTransfer::FK_COUNTRY => '60',
                TaxRateTransfer::NAME => 'test tax rate 1',
                TaxRateTransfer::RATE => 15.00,
            ],
            [
                TaxRateTransfer::FK_COUNTRY => '79',
                TaxRateTransfer::NAME => 'test tax rate 2',
                TaxRateTransfer::RATE => 20.00,
            ],
        ]);

        $productOptionGroupTransfer = $this->tester->haveProductOptionGroupWithValues(
            [ProductOptionGroupTransfer::FK_TAX_SET => $taxSetTransfer->getIdTaxSet()],
            [
                [
                    [ProductOptionValueTransfer::SKU => static::PRODUCT_OPTION_VALUE],
                    [
                        ['netAmount' => 1000],
                    ],
                ],
            ]
        );
    }

    /**
     * @return void
     */
    public function testProductOptionTaxCalculatorShouldUseDefaultStoreCountry(): void
    {
        $productOptionValueEntity = SpyProductOptionValueQuery::create()->findBySku(static::PRODUCT_OPTION_VALUE);
        $quoteTransfer = (new QuoteBuilder())
            ->withItem(
                (new ItemBuilder())
                    ->withProductOption([
                        'id_product_option_value' => $productOptionValueEntity[0]->getIdProductOptionValue(),
                    ])
            )
            ->build();

        foreach ($this->defaultCountryListWithExpectedRate as $defaultCountryIso2Code => $taxRate) {
            $this->tester->setDependency(
                ProductOptionDependencyProvider::FACADE_TAX,
                $this->createProductOptionToTaxFacadeBridgeMock($defaultCountryIso2Code, 0.00)
            );

            $this->tester->getFacade()->calculateProductOptionTaxRate($quoteTransfer);

            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                    $this->assertEquals($taxRate, $productOptionTransfer->getTaxRate(), "Expected result - {$taxRate}, Actual result - {$productOptionTransfer->getTaxRate()}");
                }
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
}
