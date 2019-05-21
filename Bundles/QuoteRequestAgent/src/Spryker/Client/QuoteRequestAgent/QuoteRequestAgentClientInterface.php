<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequestAgent;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface QuoteRequestAgentClientInterface
{
    /**
     * Specification:
     * - Makes Zed request.
     * - Creates "Request for Quote" for the provided company user with "in-progress" status.
     * - Generates unique reference number.
     * - Generates version for the "Request for Quote" entity.
     * - Generates version reference based on unique reference number and version number.
     * - Sets field is_latest_version_visible to false.
     * - Stores empty metadata.
     * - Stores empty quote.
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
     * - Makes Zed request.
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
     * - Makes Zed request.
     * - Looks up one "Request for Quote" by provided quote request reference.
     * - Expects "Request for Quote" status to be "waiting", "ready", "draft".
     * - Creates latest version from previous version.
     * - Sets field is_latest_version_visible to false.
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
     * - Makes Zed request.
     * - Looks up one "Request for Quote" by provided quote request reference.
     * - Expects the related company user to be provided.
     * - Expects "Request for Quote" status to be "draft", "waiting", "ready".
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
     * - Makes Zed request.
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
     * - Makes Zed request.
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

    /**
     * Specification:
     * - Makes Zed request.
     * - Retrieves "Request for Quote" entity filtered by quote request reference.
     * - Selects latestVersion based on latest version id.
     *
     * @api
     *
     * @param string $quoteRequestReference
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    public function findQuoteRequestByReference(string $quoteRequestReference): ?QuoteRequestTransfer;

    /**
     * Specification:
     * - Expects "Request for Quote" status to be "in-progress" or "draft".
     * - Replaces current customer quote by quote from latest version.
     * - Avoids database strategy.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function convertQuoteRequestToQuote(QuoteRequestTransfer $quoteRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Checks cancelable status from config.
     * - If "Request for Quote" cancelable - return true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteRequestCancelable(QuoteRequestTransfer $quoteRequestTransfer): bool;

    /**
     * Specification:
     * - If "Request for Quote" in waiting, ready, draft status - return true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteRequestRevisable(QuoteRequestTransfer $quoteRequestTransfer): bool;

    /**
     * Specification:
     * - If "Request for Quote" in in-progress status - return true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteRequestEditable(QuoteRequestTransfer $quoteRequestTransfer): bool;
}
