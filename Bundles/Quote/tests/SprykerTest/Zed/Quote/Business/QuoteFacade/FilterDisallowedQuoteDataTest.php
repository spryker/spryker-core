<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Quote\Business\QuoteFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Quote\Dependency\Service\QuoteToUtilEncodingServiceInterface;
use Spryker\Zed\Quote\Persistence\Propel\Mapper\QuoteMapper;
use Spryker\Zed\Quote\QuoteConfig;
use ArrayObject;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Quote
 * @group Business
 * @group QuoteFacade
 * @group FilterDisallowedQuoteDataTest
 * Add your own group annotations below this line
 */
class FilterDisallowedQuoteDataTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Quote\QuoteBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider quoteDataDataProvider
     *
     * @return void
     */
    public function testFilterDisallowedQuoteData(array $allowedQuoteFields, array $quoteData, array $expectedQuoteData)
    {
        $utilEncodingServiceMock = $this->getMockBuilder(QuoteToUtilEncodingServiceInterface::class)
            ->getMock();
        $quoteConfigMock = $this->getMockBuilder(QuoteConfig::class)
            ->getMock();

        $mapper = new QuoteMapper($utilEncodingServiceMock, $quoteConfigMock);
        $mapperReflection = new \ReflectionClass(get_class($mapper));
        $filterDisallowedQuoteDataMethod = $mapperReflection->getMethod('filterDisallowedQuoteData');
        $filterDisallowedQuoteDataMethod->setAccessible(true);

        $filteredDisallowedQuoteData = $filterDisallowedQuoteDataMethod->invokeArgs($mapper, [
            $quoteData,
            $allowedQuoteFields
        ]);

        $this->assertEquals($expectedQuoteData, $filteredDisallowedQuoteData);
    }

    /**
     * @return array
     */
    public function quoteDataDataProvider(): array
    {
        return [
            $this->getDataForQuoteDataDataProvider(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataForQuoteDataDataProvider(): array
    {
        $quote = (new QuoteBuilder([
            QuoteTransfer::PRICE_MODE => 'GROSS_MODE',
        ]))
            ->withItem([
                ItemTransfer::ID => 27,
                ItemTransfer::SKU => '027_26976107',
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::ID_PRODUCT_ABSTRACT => 27,
                ItemTransfer::NAME => 'Sony Cyber-shot DSC-WX500',
                ItemTransfer::UNIT_PRICE => 4900,
                ItemTransfer::SUM_PRICE => 4900,
                ItemTransfer::UNIT_GROSS_PRICE => 4900,
                ItemTransfer::SUM_GROSS_PRICE => 4900,
            ])
            ->withTotals([
                TotalsTransfer::SUBTOTAL => 4900,
                TotalsTransfer::EXPENSE_TOTAL => 0,
                TotalsTransfer::DISCOUNT_TOTAL => 0,
                TotalsTransfer::TAX_TOTAL => [
                    'taxRate' => null,
                    'amount' => 782,
                ],
                TotalsTransfer::GRAND_TOTAL => 4900,
                TotalsTransfer::NET_TOTAL => 4118,
                TotalsTransfer::NET_TOTAL => 4118,
                TotalsTransfer::PRICE_TO_PAY => 4900,
                TotalsTransfer::REFUND_TOTAL => 4900,
            ])
            ->withCurrency([
                CurrencyTransfer::CODE => 'EUR',
                CurrencyTransfer::NAME => 'Euro',
                CurrencyTransfer::SYMBOL => '€',
                CurrencyTransfer::IS_DEFAULT => true,
                CurrencyTransfer::FRACTION_DIGITS => 2,
            ])
            ->build();
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->fromArray($quote->toArray());

        $quoteData = $quoteTransfer->modifiedToArray(true, true);

        $allowedQuoteFields = [
            'items' =>
                [
                    'id',
                    'sku',
                    'quantity',
                    'idProductAbstract',
                    'images',
                    'name',
                    'unitPrice',
                    'sumPrice',
                    'unitGrossPrice',
                    'sumGrossPrice',
                    'isOrdered',
                ],
            'totals',
            'currency',
            'priceMode',
            'bundleItems',
            'cartNote',
            'expenses',
            'voucherDiscounts',
            'cartRuleDiscounts',
            'promotionItems',
            'isLocked',
        ];

        $expectedQuoteData = [
            'items' => [
                [
                    'id' => 27,
                    'sku' => '027_26976107',
                    'quantity' => 1,
                    'idProductAbstract' => 27,
                    'images' => new ArrayObject(),
                    'name' => 'Sony Cyber-shot DSC-WX500',
                    'unitPrice' => 4900,
                    'sumPrice' => 4900,
                    'unitGrossPrice' => 4900,
                    'sumGrossPrice' => 4900,
                ],
            ],
            'totals' => [
                'subtotal' => 4900,
                'expenseTotal' => 0,
                'discountTotal' => 0,
                'taxTotal' => [
                    'taxRate' => null,
                    'amount' => 782,
                ],
                'grandTotal' => 4900,
                'netTotal' => 4118,
                'canceledTotal' => null,
                'hash' => null,
                'priceToPay' => 4900,
                'refundTotal' => 4900,
            ],
            'currency' => [
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => '€',
                'isDefault' => true,
                'fractionDigits' => 2,
                'idCurrency' => null,
            ],
            'priceMode' => 'GROSS_MODE',
            'bundleItems' => new ArrayObject(),
            'expenses' => new ArrayObject(),
            'voucherDiscounts' => new ArrayObject(),
            'promotionItems' => new ArrayObject(),
            'cartRuleDiscounts' => new ArrayObject(),
        ];

        return [
            $allowedQuoteFields,
            $quoteData,
            $expectedQuoteData,
        ];
    }
}
