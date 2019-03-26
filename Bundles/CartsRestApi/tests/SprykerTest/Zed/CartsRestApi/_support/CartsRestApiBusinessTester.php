<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartsRestApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\QuoteCollectionBuilder;
use Generated\Shared\DataBuilder\QuoteCriteriaFilterBuilder;
use Generated\Shared\DataBuilder\QuoteResponseBuilder;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

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

    public const TEST_QUOTE_UUID = 'test-quote-uuid';

    public const TEST_CUSTOMER_REFERENCE = 'DE--666';

    /**
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function prepareQuoteResponseTransferWithQuote(): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = (new QuoteResponseBuilder(['isSuccessful' => true]))
            ->withQuoteTransfer(['uuid' => static::TEST_QUOTE_UUID, 'customerReference' => static::TEST_CUSTOMER_REFERENCE])
            ->build();

        return $quoteResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function prepareQuoteResponseTransferWithoutQuote(): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = (new QuoteResponseBuilder(['isSuccessful' => false]))->build();

        return $quoteResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer
     */
    public function prepareQuoteCriteriaFilterTransfer(): QuoteCriteriaFilterTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer */
        $quoteCriteriaFilterTransfer = (new QuoteCriteriaFilterBuilder(['customerReference' => static::TEST_CUSTOMER_REFERENCE]))
            ->build();

        return $quoteCriteriaFilterTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer
     */
    public function prepareEmptyQuoteCriteriaFilterTransfer(): QuoteCriteriaFilterTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer */
        $quoteCriteriaFilterTransfer = (new QuoteCriteriaFilterBuilder())->build();

        return $quoteCriteriaFilterTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prepareQuoteTransfer(): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder(['uuid' => static::TEST_QUOTE_UUID, 'customerReference' => static::TEST_CUSTOMER_REFERENCE]))->build();

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prepareQuoteTransferWithoutCustomerReference(): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder(['uuid' => static::TEST_QUOTE_UUID]))->build();

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prepareQuoteTransferWithoutCartUuid(): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder(['customerReference' => static::TEST_CUSTOMER_REFERENCE]))->build();

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function prepareEmptyQuoteCollectionTransfer(): QuoteCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer */
        $quoteCollectionTransfer = (new QuoteCollectionBuilder())->build();

        return $quoteCollectionTransfer;
    }
}
