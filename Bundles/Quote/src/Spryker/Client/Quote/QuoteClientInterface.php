<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteClientInterface
{
    /**
     * Specification:
     * - Returns the stored quote from session.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote();

    /**
     * Specification:
     * - Set quote in session.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function setQuote(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Empty existing quote and store to session.
     * - In case of persistent strategy the quote is also deleted from database.
     *
     * @api
     *
     * @return void
     */
    public function clearQuote();

    /**
     * Specification:
     * - Get quote storage strategy type.
     *
     * @api
     *
     * @return string
     */
    public function getStorageStrategy();

    /**
     * Specification:
     * - Returns true if quote is locked.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteLocked(QuoteTransfer $quoteTransfer): bool;

    /**
     * Specification:
     * - Returns false if quote locked.
     * - Returns true if quote has empty id.
     * - Returns true if customer has `WriteSharedCartPermission`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteEditable(QuoteTransfer $quoteTransfer): bool;

    /**
     * Specification:
     *  - Locks quote by setting `isLocked` transfer property to true.
     *  - Low level Quote locking (use CartClientInterface for features).
     *
     * @see CartClientInterface::lockQuote()
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function lockQuote(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
