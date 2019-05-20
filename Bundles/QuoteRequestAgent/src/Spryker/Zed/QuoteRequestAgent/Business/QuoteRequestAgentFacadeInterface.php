<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestAgent\Business;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;

interface QuoteRequestAgentFacadeInterface
{
    /**
     * Specification:
     * - Creates "Request for Quote" for the provided company user with "in-progress" status.
     * - Generates unique reference number.
     * - Generates version for the "Request for Quote" entity.
     * - Generates version reference based on unique reference number and version number.
     * - Stores empty metadata.
     * - Stores empty quote.
     * - Sets hidden visibility for latest version.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer;

    /**
     * Specification:
     * - Finds a "Request for Quote" by QuoteRequestTransfer::idQuoteRequest in the transfer.
     * - Expects "Request for Quote" status to be "draft", "in-progress".
     * - Updates valid_until, is_hidden fields in RfQ entity.
     * - Updates metadata in latest version.
     * - Updates quote in latest version.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer;

    /**
     * Specification:
     * - Looks up one "Request for Quote" by provided quote request reference.
     * - Expects "Request for Quote" status to not be "canceled", "closed".
     * - Sets status to "cancelled".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer;

    /**
     * Specification:
     * - Looks up one "Request for Quote" by provided quote request reference.
     * - Expects "Request for Quote" status to be "waiting", "ready", "draft".
     * - Creates latest version from previous version.
     * - Sets status to "in-progress".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function reviseQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer;

    /**
     * Specification:
     * - Expects quote request reference to be provided.
     * - Retrieves "Request for Quote" entity filtered by reference.
     * - Expects "Request for Quote" status to be "draft", "in-progress".
     * - Updates field is_latest_version_visible to true.
     * - Changes status to "ready".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function sendQuoteRequestToCustomer(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer;

    /**
     * Specification:
     * - Retrieves "Request for Quote" entities according to provided filter.
     * - Sets current "Request for Quote" by quote request reference when provided.
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
}
