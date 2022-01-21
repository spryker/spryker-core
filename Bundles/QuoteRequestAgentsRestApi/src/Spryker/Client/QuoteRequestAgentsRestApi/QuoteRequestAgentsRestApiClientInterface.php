<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequestAgentsRestApi;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;

/**
 * @method \Spryker\Client\QuoteRequestAgentsRestApi\QuoteRequestAgentsRestApiFactory getFactory()
 */
interface QuoteRequestAgentsRestApiClientInterface
{
    /**
     * Specification:
     * - Finds a "Request for Quote" by QuoteRequestTransfer::idQuoteRequest in the transfer.
     * - Expects "Request for Quote" status to be "draft", "in-progress".
     * - Updates `valid_until`, `is_hidden` fields in RfQ entity.
     * - Updates `metadata` in latest version.
     * - Updates `quote` in latest version.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer;
}
