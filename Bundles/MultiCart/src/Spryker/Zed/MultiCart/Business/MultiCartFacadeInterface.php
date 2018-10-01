<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Business;

use Generated\Shared\Transfer\QuoteActivationRequestTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface MultiCartFacadeInterface
{
    /**
     * Specification:
     * - Mark quote as default.
     * - Mark all other customer quotes as not default.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteActivationRequestTransfer $quoteActivationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setDefaultQuote(QuoteActivationRequestTransfer $quoteActivationRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Finds customer quotes.
     * - Mark first quote as default, if all customers quote are not default.
     *
     * @api
     *
     * @param string $customerReference
     *
     * @return void
     */
    public function initDefaultCustomerQuote(string $customerReference): void;

    /**
     * Specification:
     * - Adds customer quote collection to quote response transfer after cart operation handling.
     * - Replace quote with active quote if it exist.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function expandQuoteResponse(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Mark all customer quotes as not default.
     *
     * @api
     *
     * @param string $customerReference
     *
     * @return void
     */
    public function resetQuoteDefaultFlagByCustomer(string $customerReference): void;

    /**
     * Specification:
     *  - Resolve quote name to make it unique for customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function resolveQuoteName(QuoteTransfer $quoteTransfer): string;

    /**
     * Specification:
     *  - Returns the quotes collection by provided criteria.
     *  - Collection is filtered by customer reference.
     *  - Pagination and ordering options can be passed to criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollectionByCriteria(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer;
}
