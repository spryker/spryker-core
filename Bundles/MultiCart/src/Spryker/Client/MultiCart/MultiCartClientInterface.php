<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface MultiCartClientInterface
{
    /**
     * Specification:
     * - Create new quote in database
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createCart(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Updates existing quote in database
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateCart(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Deletes existing quote in database
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteCart(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Mark existing quote as active
     * - Saves quote in database
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setActiveCart(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Gets active quote
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getActiveCart(): QuoteTransfer;

    /**
     * Specification:
     * - Gets customer quote collection
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollection(): QuoteCollectionTransfer;
}
