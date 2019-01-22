<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartsRestApi;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;

interface CartsRestApiClientInterface
{
    /**
     * Specification:
     * - Finds quote by uuid.
     * - Uuid must be set in the QuoteTransfer taken as parameter.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByUuid(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Updates customer quote.
     * - Quote and customerReference must be set in the RestQuoteRequestTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Creates customer quote.
     * - Quote and customerReference must be set in the RestQuoteRequestTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Deletes customer quote.
     * - Quote and customerReference must be set in the RestQuoteRequestTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuote(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Updates guest cart.
     * - Quote and customerReference must be set in the RestQuoteRequestTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateGuestQuote(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Creates guest cart.
     * - Quote and customerReference must be set in the RestQuoteRequestTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createGuestQuote(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Updates cart item quantity.
     * - Quote and customerReference must be set in the RestQuoteRequestTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateItemQuantity(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Adds an item to the cart.
     * - Quote and customerReference must be set in the RestQuoteRequestTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addItem(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Removes item from cart.
     * - Quote and customerReference must be set in the RestQuoteRequestTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteItem(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer;
}
