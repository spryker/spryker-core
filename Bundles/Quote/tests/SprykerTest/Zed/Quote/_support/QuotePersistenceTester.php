<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Quote;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\ProductImageBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Quote\Persistence\SpyQuote;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class QuotePersistenceTester extends Actor
{
    use _generated\QuotePersistenceTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * @param array $quoteAllowedFields
     * @param array $decodedQuoteData
     *
     * @return void
     */
    public function assertContainOnlyAllowedFields(array $quoteAllowedFields, array $decodedQuoteData): void
    {
        $this->assertCount(
            count($quoteAllowedFields),
            $decodedQuoteData,
            'Decoded quote data doesn\'t contain required count of indexes'
        );

        foreach ($quoteAllowedFields as $key => $value) {
            $fieldName = is_array($value) ? $key : $value;

            $this->assertArrayHasKey(
                $fieldName,
                $decodedQuoteData,
                sprintf(
                    'Index "%s" was not found in array "%s"',
                    $fieldName,
                    $this->getLocator()->utilEncoding()->service()->encodeJson($decodedQuoteData)
                )
            );

            if (is_array($value)) {
                foreach ($decodedQuoteData[$fieldName] as $nestedQuoteObject) {
                    $this->assertContainOnlyAllowedFields($value, $nestedQuoteObject);
                }
            }
        }
    }

    /**
     * @return \Orm\Zed\Quote\Persistence\SpyQuote
     */
    public function createQuotePropelEntity(): SpyQuote
    {
        return new SpyQuote();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(): QuoteTransfer
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
            QuoteTransfer::CUSTOMER => (new CustomerBuilder())->build(),
            QuoteTransfer::STORE => (new StoreBuilder())->build(),
        ]))
            ->withAnotherItem([
                ItemTransfer::ID => 27,
                ItemTransfer::SKU => '027_26976107',
                ItemTransfer::GROUP_KEY => '',
                ItemTransfer::GROUP_KEY_PREFIX => '',
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
            ->withAnotherTotals([
                TotalsTransfer::SUBTOTAL => 4900,
                TotalsTransfer::EXPENSE_TOTAL => 0,
                TotalsTransfer::DISCOUNT_TOTAL => 0,
                TotalsTransfer::TAX_TOTAL => [
                    TaxTotalTransfer::TAX_RATE => null,
                    TaxTotalTransfer::AMOUNT => 782,
                ],
                TotalsTransfer::GRAND_TOTAL => 4900,
                TotalsTransfer::NET_TOTAL => 4118,
                TotalsTransfer::PRICE_TO_PAY => 4900,
                TotalsTransfer::REFUND_TOTAL => 4900,
            ])
            ->withAnotherCurrency([
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
}
