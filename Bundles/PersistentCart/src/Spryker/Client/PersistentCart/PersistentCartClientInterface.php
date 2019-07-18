<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;

interface PersistentCartClientInterface
{
    /**
     * Specification:
     * - Makes zed request.
     * - Deletes existing quote in database.
     * - Executes update quote plugins.
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
     * - Creates quote in database.
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
     * - Makes Zed request.
     * - Creates quote in database.
     * - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuoteWithReloadedItems(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Updates quote in database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Retrieves a quote from Persistence using the provided customer and store information.
     * - Replaces the retrieved quote with the provided quote and stores it in Persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replaceQuoteByCustomerAndStore(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Replaces active customer cart.
     * - In case of persistent strategy makes Zed request.
     * - In case of persistent strategy replaces the quote also in Persistence.
     * - In case of persistent strategy takes replacer plugin from QuotePersistPluginInterface stack.
     * - In case of persistent strategy uses PersistentCartQuotePersistPlugin by default.
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
     * - Prepends customer reference with anonymous prefix from configuration.
     *
     * @api
     *
     * @param string $customerReference
     *
     * @return string
     */
    public function generateGuestCartCustomerReference(string $customerReference): string;

    /**
     * Specification:
     * - Sets new empty quote in session storage.
     * - In case of persistent strategy makes zed request.
     * - In case of persistent strategy retrieves a quote from Persistence using the provided customer and store information.
     * - In case of persistent strategy merges empty quote with retrieved quote from Persistence.
     * - In case of persistent strategy sets merged quote in session storage.
     * - In case of persistent strategy executes QuoteUpdatePluginExecutorInterface plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function reloadQuoteForCustomer(CustomerTransfer $customerTransfer): void;
}
