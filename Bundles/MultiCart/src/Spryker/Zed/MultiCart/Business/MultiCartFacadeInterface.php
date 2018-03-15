<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Business;

use Generated\Shared\Transfer\QuoteActivatorRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface MultiCartFacadeInterface
{
    /**
     * Specification:
     * -
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
}
