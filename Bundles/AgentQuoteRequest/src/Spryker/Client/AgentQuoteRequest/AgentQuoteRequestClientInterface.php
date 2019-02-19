<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AgentQuoteRequest;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;

interface AgentQuoteRequestClientInterface
{
    /**
     * Specification:
     * - Makes Zed request.
     * - Retrieves "Request for Quote" entities according to provided filter.
     * - Sets current "Request for Quote" by quote request reference when provided.
     * - Excludes "Request for Quote" with status "closed".
     * - Selects latestVersion based on latest version id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer
     */
    public function getQuoteRequestOverviewCollection(
        QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
    ): QuoteRequestOverviewCollectionTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Looks up one "Request for Quote" by provided quote request reference.
     * - Expects "Request for Quote" status to not be "canceled".
     * - Sets status to "Cancelled".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelByReference(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer;
}
