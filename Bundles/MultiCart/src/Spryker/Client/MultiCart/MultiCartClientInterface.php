<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart;

use Generated\Shared\Transfer\QuoteActivationRequestTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
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
     * @param \Generated\Shared\Transfer\QuoteActivationRequestTransfer $quoteActivationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setDefaultQuote(QuoteActivationRequestTransfer $quoteActivationRequestTransfer): QuoteResponseTransfer;

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
    public function isMultiCartAllowed();

    /**
     * Specification:
     * - Get suffix for duplicated quote name.
     *
     * @api
     *
     * @return string
     */
    public function getDuplicatedQuoteNameSuffix();
}
