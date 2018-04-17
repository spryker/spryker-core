<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartNote\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

interface CartNoteToQuoteClientInterface
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
     * - Get quote storage strategy type.
     *
     * @api
     *
     * @return string
     */
    public function getStorageStrategy();
}
