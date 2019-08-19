<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Quote\Persistence\Propel\Mapper;

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
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Zed\Quote\Dependency\Service\QuoteToUtilEncodingServiceBridge;
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
     * @param \Orm\Zed\Quote\Persistence\SpyQuote $quoteEntity
     * @param array $expectedQuoteData
     *
     * @return void
     */
    public function testMapTransferToEntity(
        QuoteTransfer $quoteTransfer,
        SpyQuote $quoteEntity,
        array $expectedQuoteData
    ): void {
        // Arrange
        $utilEncodingServiceMock = new QuoteToUtilEncodingServiceBridge(new UtilEncodingService());
        $mapper = new QuoteMapper($utilEncodingServiceMock, $this->createQuoteConfig());

        // Act
        $updatedQuoteEntity = $mapper->mapTransferToEntity(
            $quoteTransfer,
            $quoteEntity
        );
        $decodedQuoteData = $utilEncodingServiceMock->decodeJson($updatedQuoteEntity->getQuoteData(), true);

        // Assert
        $this->assertEquals($expectedQuoteData, $decodedQuoteData);
    }

    /**
     * @return \Spryker\Zed\Quote\QuoteConfig
     */
    protected function createQuoteConfig(): QuoteConfig
    {
        $quoteConfigMock = $this->getMockBuilder(QuoteConfig::class)
            ->getMock();

        $quoteConfigMock->method('getQuoteFieldsAllowedForSaving')->willReturn([
            QuoteTransfer::ITEMS => [
                ItemTransfer::ID,
                ItemTransfer::SKU,
                ItemTransfer::QUANTITY,
                ItemTransfer::ID_PRODUCT_ABSTRACT,
                ItemTransfer::IMAGES,
                ItemTransfer::NAME,
                ItemTransfer::UNIT_PRICE,
                ItemTransfer::SUM_PRICE,
                ItemTransfer::UNIT_GROSS_PRICE,
                ItemTransfer::SUM_GROSS_PRICE,
                ItemTransfer::IS_ORDERED,
            ],
            QuoteTransfer::TOTALS,
            QuoteTransfer::CURRENCY,
            QuoteTransfer::PRICE_MODE,
        ]);

        return $quoteConfigMock;
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
     * @return \Orm\Zed\Quote\Persistence\SpyQuote
     */
    protected function createQuoteEntity(): SpyQuote
    {
        return (SpyQuoteQuery::create())->findOneOrCreate();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        $quoteDefaultProductImageTransfer = (new ProductImageBuilder([
            ProductImageTransfer::ID_PRODUCT_IMAGE => 27,
            ProductImageTransfer::ID_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE => null,
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
                    $quoteDefaultProductImageTransfer->toArray(),
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
                CurrencyTransfer::ID_CURRENCY => '93',
                CurrencyTransfer::CODE => 'EUR',
                CurrencyTransfer::NAME => 'Euro',
                CurrencyTransfer::SYMBOL => '€',
                CurrencyTransfer::IS_DEFAULT => true,
                CurrencyTransfer::FRACTION_DIGITS => 2,
            ])
            ->build();

        return $quoteTransfer;
    }

    /**
     * @return array
     */
    protected function getDataForMapTransferToEntityProvider(): array
    {
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
                            'idProductImageSetToProductImage' => null,
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
                'priceToPay' => 4900,
                'refundTotal' => 4900,
            ],
            'currency' => [
                'idCurrency' => 93,
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => '€',
                'isDefault' => true,
                'fractionDigits' => 2,
            ],
            'priceMode' => 'GROSS_MODE',
        ];

        return [
            $this->createQuoteTransfer(),
            $this->createQuoteEntity(),
            $expectedQuoteData,
        ];
    }
}
