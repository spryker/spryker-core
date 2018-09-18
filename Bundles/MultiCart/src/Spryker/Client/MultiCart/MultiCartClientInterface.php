<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface MultiCartClientInterface
{
    /**
     * Specification:
     * - Gets active quote.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getDefaultCart(): QuoteTransfer;

    /**
     * Specification:
     * - Mark quote as active.
     * - Mark all other customer carts as inactive.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setDefaultQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Gets customer quote collection.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollection(): QuoteCollectionTransfer;

    /**
     * Specification:
     * - Set customer quote collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return void
     */
    public function setQuoteCollection(QuoteCollectionTransfer $quoteCollectionTransfer): void;

    /**
     * Specification:
     * - Find quote by id in customer session.
     *
     * @api
     *
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findQuoteById(int $idQuote): ?QuoteTransfer;

    /**
     * Specification:
     * - Checks if multicart functionality allowed.
     *
     * @api
     *
     * @return bool
     */
    public function isMultiCartAllowed(): bool;

    /**
     * Specification:
     * - Create new quote.
     * - Save quote in database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Update quote name.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Remove quote.
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
     * - Create copy of quote with.
     * - Mark new quote as active.
     * - Add duplicate suffix new quote name.
     * - Save quote in database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function duplicateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Remove all items from quote.
     * - Save quote in database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function clearQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Returns quote collection by criteria from Zed.
     * - Collection is filtered by customer reference.
     * - Pagination and ordering options can be passed to criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollectionByCriteria(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer;

    /**
     * Specification:
     * - Сhecks the possibility of removing the quote
     * - If customer has more one quote - returns TRUE
     *
     * @api
     *
     * @return bool
     */
    public function isQuoteDeletable(): bool;
}
