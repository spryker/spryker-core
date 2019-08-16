<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Quote\Persistence\Propel\Mapper;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductImageBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Quote\Persistence\SpyQuote;
use Orm\Zed\Quote\Persistence\SpyQuoteQuery;
use Spryker\Zed\Quote\Dependency\Service\QuoteToUtilEncodingServiceInterface;
use Spryker\Zed\Quote\Persistence\Propel\Mapper\QuoteMapper;
use Spryker\Zed\Quote\QuoteConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Quote
 * @group Persistence
 * @group Propel
 * @group Mapper
 * @group QuoteMapperTest
 * Add your own group annotations below this line
 */
class QuoteMapperTest extends Unit
{
    /**
     * @dataProvider mapTransferToEntityProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Quote\Persistence\SpyQuote $quote
     * @param array $expectedQuoteData
     *
     * @return void
     */
    public function testMapTransferToEntity(
        QuoteTransfer $quoteTransfer,
        SpyQuote $quote,
        array $expectedQuoteData
    ): void {
        $utilEncodingServiceMock = $this->getMockBuilder(QuoteToUtilEncodingServiceInterface::class)
            ->getMock();
        $quoteConfigMock = $this->getMockBuilder(QuoteConfig::class)
            ->getMock();

        $mapper = new QuoteMapper($utilEncodingServiceMock, $quoteConfigMock);
        $updatedQuote = $mapper->mapTransferToEntity(
            $quoteTransfer,
            $quote
        );

        $this->assertEquals($expectedQuoteData, $updatedQuote->toArray());
    }

    /**
     * @return \Orm\Zed\Quote\Persistence\SpyQuote
     */
    protected function createQuoteEntity(): SpyQuote
    {
        return (SpyQuoteQuery::create())->findOneOrCreate();
    }

    /**
     * @return array
     */
    public function mapTransferToEntityProvider(): array
    {
        return [
            $this->getDataForMapTransferToEntityProvider(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataForMapTransferToEntityProvider(): array
    {
        $expectedQuoteDefaultProductImageTransfer = (new ProductImageBuilder([
            ProductImageTransfer::ID_PRODUCT_IMAGE => 27,
            ProductImageTransfer::SORT_ORDER => 0,
            ProductImageTransfer::EXTERNAL_URL_SMALL => "//images.icecat.biz/img\/norm/low/7822599-Sony.jpg",
            ProductImageTransfer::EXTERNAL_URL_LARGE => "//images.icecat.biz/img\/norm/medium/7822599-Sony.jpg",
        ]))
            ->build();

        $quoteTransfer = (new QuoteBuilder([
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
                ItemTransfer::IMAGES => [
                    $expectedQuoteDefaultProductImageTransfer->toArray(),
                ],
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

        $expectedQuoteData = [
            'items' => [
                [
                    'id' => 27,
                    'sku' => '027_26976107',
                    'quantity' => 1,
                    'idProductAbstract' => 27,
                    'images' => [
                        [
                            'externalUrlSmall' => '//images.icecat.biz/img\\/norm/low/7822599-Sony.jpg',
                            'idProductImage' => 27,
                            'sortOrder' => 0,
                            'externalUrlLarge' => '//images.icecat.biz/img\\/norm/medium/7822599-Sony.jpg',
                        ],
                    ],
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

        $quote = $this->createQuoteEntity();

        return [
            $quoteTransfer,
            $quote,
            $expectedQuoteData,
        ];
    }
}
