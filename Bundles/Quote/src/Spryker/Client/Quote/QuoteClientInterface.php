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
     * - Returns the stored quote.
     * - TODO: what happens when persistent quote is enabled?
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote();

    /**
     * Specification:
     * - Stores the quote.
     * - TODO: what happens when persistent quote is enabled?
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
     * - Resets all data which is stored in the quote.
     * - TODO: what happens when persistent quote is enabled?
     *
     * @api
     *
     * @return void
     */
    public function clearQuote();

    /**
     * Specification:
     * - Gets quote from storage and save it in customer session.
     * - TODO: what happens when persistent quote is NOT enabled?
     *
     * @api
     *
     * @return void
     */
    public function syncQuote();

    /**
     * Specification:
     * - Gets quote from customer session and save it in storage.
     * - TODO: what happens when persistent quote is NOT enabled?
     *
     * @api
     *
     * @return void
     */
    public function pushQuote();
}
