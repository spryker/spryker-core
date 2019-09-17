<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartsRestApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\AssignGuestQuoteRequestBuilder;
use Generated\Shared\DataBuilder\CartItemRequestBuilder;
use Generated\Shared\DataBuilder\OauthResponseBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\QuoteCollectionBuilder;
use Generated\Shared\DataBuilder\QuoteCriteriaFilterBuilder;
use Generated\Shared\DataBuilder\QuoteResponseBuilder;
use Generated\Shared\DataBuilder\RestCartItemsAttributesBuilder;
use Generated\Shared\Transfer\AssignGuestQuoteRequestTransfer;
use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class CartsRestApiBusinessTester extends Actor
{
    use _generated\CartsRestApiBusinessTesterActions;

    public const TEST_ID_QUOTE = 67238;
    public const TEST_QUOTE_UUID = 'test-quote-uuid';

    public const TEST_CUSTOMER_REFERENCE = 'DE--666';

    public const TEST_ANONYMOUS_CUSTOMER_REFERENCE = 'anonymous:DE--666';

    public const TEST_QUANTITY = '3';

    public const TEST_SKU = 'test-sku';

    public const COLLECTION_QUOTES = [
        [
            'id_quote' => 1,
            'name' => 'Shopping cart',
            'store' => 'DE',
            'priceMode' => 'GROSS_MODE',
            'currency' => 'EUR',
            'customerReference' => 'tester-de',
            'uuid' => '7fd5cc11-87ff-55e2-b413-7e07f9640404',

        ],
        [
            'id_quote' => 2,
            'name' => 'test quote two',
            'store' => 'DE',
            'priceMode' => 'GROSS_MODE',
            'currency' => 'EUR',
            'customerReference' => 'tester-de',
            'uuid' => '22b43a18-e46c-55bf-bc00-65f4dee0727a',
        ],
    ];

    public const ITEMS = [
        [
            'sku' => 'test sku',
            'quantity' => '666',

        ],
        [
            'sku' => 'test sku 2',
            'quantity' => '666',
        ],
    ];

    /**
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function prepareQuoteResponseTransfer(): QuoteResponseTransfer
    {
        return (new QuoteResponseBuilder(['isSuccessful' => true]))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function prepareQuoteResponseTransferWithQuote(): QuoteResponseTransfer
    {
        $quoteOverride = [
            'uuid' => static::TEST_QUOTE_UUID,
            'customerReference' => static::TEST_CUSTOMER_REFERENCE,
            'idQuote' => static::TEST_ID_QUOTE,
        ];
        $itemOverride = ['groupKey' => static::TEST_SKU, 'sku' => static::TEST_SKU];

        return (new QuoteResponseBuilder(['isSuccessful' => true]))
            ->withQuoteTransfer((new QuoteBuilder($quoteOverride))->withItem($itemOverride))
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function prepareQuoteResponseTransferWithoutQuote(): QuoteResponseTransfer
    {
        return (new QuoteResponseBuilder(['isSuccessful' => false]))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer
     */
    public function prepareQuoteCriteriaFilterTransfer(): QuoteCriteriaFilterTransfer
    {
        return (new QuoteCriteriaFilterBuilder(['customerReference' => static::TEST_CUSTOMER_REFERENCE]))
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer
     */
    public function prepareQuoteCriteriaFilterTransferForGuest(): QuoteCriteriaFilterTransfer
    {
        return (new QuoteCriteriaFilterBuilder(['customerReference' => static::TEST_ANONYMOUS_CUSTOMER_REFERENCE]))
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer
     */
    public function prepareEmptyQuoteCriteriaFilterTransfer(): QuoteCriteriaFilterTransfer
    {
        return (new QuoteCriteriaFilterBuilder())->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prepareQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteBuilder(
            [
                'uuid' => static::TEST_QUOTE_UUID,
                'customerReference' => static::TEST_CUSTOMER_REFERENCE,
                'customer' => (new CustomerTransfer())->setCustomerReference(static::TEST_CUSTOMER_REFERENCE),
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prepareQuoteTransferForGuest(): QuoteTransfer
    {
        return (new QuoteBuilder(
            [
                'uuid' => static::TEST_QUOTE_UUID,
                'customerReference' => static::TEST_ANONYMOUS_CUSTOMER_REFERENCE,
                'customer' => (new CustomerTransfer())->setCustomerReference(static::TEST_ANONYMOUS_CUSTOMER_REFERENCE),
                'items' => static::ITEMS,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prepareEmptyQuoteTransferForGuest(): QuoteTransfer
    {
        return (new QuoteBuilder(
            [
                'uuid' => static::TEST_QUOTE_UUID,
                'customerReference' => static::TEST_ANONYMOUS_CUSTOMER_REFERENCE,
                'customer' => (new CustomerTransfer())->setCustomerReference(static::TEST_ANONYMOUS_CUSTOMER_REFERENCE),
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prepareQuoteTransferWithoutCustomer(): QuoteTransfer
    {
        return (new QuoteBuilder(
            [
                'uuid' => static::TEST_QUOTE_UUID,
                'customerReference' => static::TEST_CUSTOMER_REFERENCE,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\RestCartItemsAttributesTransfer
     */
    public function prepareRestCartItemsAttributesTransferWithoutQuantity(): RestCartItemsAttributesTransfer
    {
        return (new RestCartItemsAttributesBuilder(
            [
                'quoteUuid' => static::TEST_QUOTE_UUID,
                'customerReference' => static::TEST_CUSTOMER_REFERENCE,
                'sku' => static::TEST_SKU,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\RestCartItemsAttributesTransfer
     */
    public function prepareRestCartItemsAttributesTransferWithQuantity(): RestCartItemsAttributesTransfer
    {
        $restCartItemsAttributesTransfer = (new RestCartItemsAttributesBuilder(
            [
                'quoteUuid' => static::TEST_QUOTE_UUID,
                'customerReference' => static::TEST_CUSTOMER_REFERENCE,
                'sku' => static::TEST_SKU,
                'quantity' => static::TEST_QUANTITY,
            ]
        ))->build();

        $restCartItemsAttributesTransfer
            ->setCustomer((new CustomerTransfer())->setCustomerReference($restCartItemsAttributesTransfer->getCustomerReference()));

        return $restCartItemsAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCartItemsAttributesTransfer
     */
    public function prepareRestCartItemsAttributesTransferWithoutCustomerReference(): RestCartItemsAttributesTransfer
    {
        return (new RestCartItemsAttributesBuilder(
            [
                'quoteUuid' => static::TEST_QUOTE_UUID,
                'sku' => static::TEST_SKU,
                'quantity' => static::TEST_QUANTITY,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    public function prepareCartItemRequestTransferWithQuantity(): CartItemRequestTransfer
    {
        return (new CartItemRequestBuilder(
            [
                'quoteUuid' => static::TEST_QUOTE_UUID,
                'quantity' => static::TEST_QUANTITY,
                'customer' => (new CustomerTransfer())->setCustomerReference(static::TEST_CUSTOMER_REFERENCE),
                'sku' => static::TEST_SKU,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    public function prepareCartItemRequestTransferWithoutCustomer(): CartItemRequestTransfer
    {
        return (new CartItemRequestBuilder(
            [
                'quoteUuid' => static::TEST_QUOTE_UUID,
                'quantity' => static::TEST_QUANTITY,
                'sku' => static::TEST_SKU,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function prepareOauthResponseTransfer(): OauthResponseTransfer
    {
        return (new OauthResponseBuilder(
            [
                'customerReference' => static::TEST_CUSTOMER_REFERENCE,
                'anonymousCustomerReference' => static::TEST_ANONYMOUS_CUSTOMER_REFERENCE,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function prepareOauthResponseTransferWithoutCustomerReference(): OauthResponseTransfer
    {
        return (new OauthResponseBuilder(
            [
                'anonymousCustomerReference' => static::TEST_ANONYMOUS_CUSTOMER_REFERENCE,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function prepareOauthResponseTransferWithoutAnonymousCustomerReference(): OauthResponseTransfer
    {
        return (new OauthResponseBuilder(
            [
                'customerReference' => static::TEST_CUSTOMER_REFERENCE,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    public function prepareCartItemRequestTransferWithoutQuantity(): CartItemRequestTransfer
    {
        return (new CartItemRequestBuilder(
            [
                'quoteUuid' => static::TEST_QUOTE_UUID,
                'customer' => (new CustomerTransfer())->setCustomerReference(static::TEST_CUSTOMER_REFERENCE),
                'sku' => static::TEST_SKU,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    public function prepareCartItemRequestTransferWithoutUuid(): CartItemRequestTransfer
    {
        return (new CartItemRequestBuilder(
            [
                'sku' => static::TEST_SKU,
                'customer' => (new CustomerTransfer())->setCustomerReference(static::TEST_CUSTOMER_REFERENCE),
                'quantity' => static::TEST_QUANTITY,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    public function prepareCartItemRequestTransferWithoutSku(): CartItemRequestTransfer
    {
        return (new CartItemRequestBuilder(
            [
                'quoteUuid' => static::TEST_QUOTE_UUID,
                'customer' => (new CustomerTransfer())->setCustomerReference(static::TEST_CUSTOMER_REFERENCE),
                'quantity' => static::TEST_QUANTITY,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\AssignGuestQuoteRequestTransfer
     */
    public function prepareAssignGuestQuoteRequestTransfer(): AssignGuestQuoteRequestTransfer
    {
        return (new AssignGuestQuoteRequestBuilder(
            [
                'anonymousCustomerReference' => static::TEST_ANONYMOUS_CUSTOMER_REFERENCE,
                'customerReference' => static::TEST_CUSTOMER_REFERENCE,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\AssignGuestQuoteRequestTransfer
     */
    public function prepareAssignGuestQuoteRequestTransferWithoutCustomerReference(): AssignGuestQuoteRequestTransfer
    {
        return (new AssignGuestQuoteRequestBuilder(
            [
                'anonymousCustomerReference' => static::TEST_ANONYMOUS_CUSTOMER_REFERENCE,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\AssignGuestQuoteRequestTransfer
     */
    public function prepareAssignGuestQuoteRequestTransferWithoutAnonymousCustomerReference(): AssignGuestQuoteRequestTransfer
    {
        return (new AssignGuestQuoteRequestBuilder(
            [
                'customerReference' => static::TEST_CUSTOMER_REFERENCE,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\RestCartItemsAttributesTransfer
     */
    public function prepareRestCartItemsAttributesTransferWithoutSku(): RestCartItemsAttributesTransfer
    {
        return (new RestCartItemsAttributesBuilder(
            [
                'quoteUuid' => static::TEST_QUOTE_UUID,
                'customerReference' => static::TEST_CUSTOMER_REFERENCE,
                'quantity' => static::TEST_QUANTITY,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\RestCartItemsAttributesTransfer
     */
    public function prepareRestCartItemsAttributesTransferWithoutUuid(): RestCartItemsAttributesTransfer
    {
        return (new RestCartItemsAttributesBuilder(
            [
                'sku' => static::TEST_SKU,
                'customerReference' => static::TEST_CUSTOMER_REFERENCE,
                'quantity' => static::TEST_QUANTITY,
            ]
        ))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prepareQuoteTransferWithoutCustomerReference(): QuoteTransfer
    {
        return (new QuoteBuilder(['uuid' => static::TEST_QUOTE_UUID]))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prepareQuoteTransferWithoutCartUuid(): QuoteTransfer
    {
        return (new QuoteBuilder(['customerReference' => static::TEST_CUSTOMER_REFERENCE]))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function prepareEmptyQuoteCollectionTransfer(): QuoteCollectionTransfer
    {
        return (new QuoteCollectionBuilder())->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function prepareQuotesCollectionTransfer(): QuoteCollectionTransfer
    {
        $quoteCollectionTransfer = new QuoteCollectionTransfer();
        foreach (static::COLLECTION_QUOTES as $quote) {
            $quoteCollectionTransfer->addQuote((new QuoteTransfer())->fromArray($quote));
        }

        return $quoteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function buildQuoteTransfer(CustomerTransfer $customerTransfer): QuoteTransfer
    {
        return (new QuoteBuilder(
            [
                'customerReference' => $customerTransfer->getCustomerReference(),
                'customer' => $customerTransfer,
            ]
        ))->build();
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function buildOauthResponseTransfer(string $customerReference): OauthResponseTransfer
    {
        return (new OauthResponseBuilder(
            [
                'customerReference' => $customerReference,
                'anonymousCustomerReference' => static::TEST_ANONYMOUS_CUSTOMER_REFERENCE,
            ]
        ))->build();
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer
     */
    public function buildQuoteCriteriaFilterTransfer(string $customerReference): QuoteCriteriaFilterTransfer
    {
        return (new QuoteCriteriaFilterBuilder(
            [
                'customerReference' => $customerReference,
            ]
        ))->build();
    }
}
