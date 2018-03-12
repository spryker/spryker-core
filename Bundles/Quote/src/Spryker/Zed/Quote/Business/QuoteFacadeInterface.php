<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteMergeRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteFacadeInterface
{
    /**
     * Specification:
     * - Persists new quote entity if it does not exist.
     * - Updates existing quote entity from QuoteTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function persistQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Find quote for customer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByCustomer(CustomerTransfer $customerTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Remove quote from DB
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Merge two quotes from request
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteMergeRequestTransfer $quoteMergeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mergeQuotes(QuoteMergeRequestTransfer $quoteMergeRequestTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Get quote storage strategy type
     *
     * @api
     *
     * @return string
     */
    public function getStorageStrategy();
}
