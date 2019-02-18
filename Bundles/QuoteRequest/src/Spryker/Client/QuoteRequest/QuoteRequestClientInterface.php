<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest;

use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;

interface QuoteRequestClientInterface
{
    /**
     * Specification:
     * - Makes Zed request.
     * - Creates "Request for Quote" for the provided company user with "Waiting" status.
     * - Generates unique reference number.
     * - Generates 1st version for the "Request for Quote" entity.
     * - Generates version reference based on unique reference number and version number.
     * - Stores provided metadata.
     * - Stores provided quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function create(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Retrieves "Request for Quote" entities filtered by company user.
     * - Filters by quote request reference when provided.
     * - Excludes hidden "Request for Quote" entities.
     * - Selects latestVersion based on latest version id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getQuoteRequestCollectionByFilter(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestCollectionTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Looks up one "Request for Quote" by provided quote request reference.
     * - Expects the related company user to be provided.
     * - Expects "Request for Quote" status to be "waiting".
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
