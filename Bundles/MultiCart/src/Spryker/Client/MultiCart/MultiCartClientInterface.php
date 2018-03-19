<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart;

use Generated\Shared\Transfer\QuoteActivatorRequestTransfer;
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
    public function getActiveCart(): QuoteTransfer;

    /**
     * Specification:
     * - Mark quote as active.
     * - Mark all other customer carts as inactive.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteActivatorRequestTransfer $quoteActivatorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setActiveQuote(QuoteActivatorRequestTransfer $quoteActivatorRequestTransfer): QuoteResponseTransfer;

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
     * - Find quote by name in customer session.
     *
     * @api
     *
     * @param string $quoteName
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findQuoteByName($quoteName): ?QuoteTransfer;

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
