<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteRequestFacadeInterface
{
    /**
     * Specification:
     * - Creates "Request for Quote" for the provided company user with "Waiting" status.
     * - Generates unique reference number.
     * - Generates 1st version for the "Request for Quote" entity.
     * - Generates version reference based on unique reference number and version number.
     * - Maps Quote to CalculableObject and runs all calculator plugins before saving.
     * - Stores provided metadata.
     * - Stores provided quote.
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
     * - Creates "Request for Quote" for the provided company user with "in-progress" status.
     * - Generates unique reference number.
     * - Sets hidden visibility for customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createUserQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer;

    /**
     * Specification:
     * - Looks up one "Request for Quote" by provided quote request reference.
     * - Expects "Request for Quote" status to not be "canceled".
     * - Sets status to "Cancelled".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelUserQuoteRequest(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer;

    /**
     * Specification:
     * - Finds a "Request for Quote" by QuoteRequestTransfer::idQuoteRequest in the transfer.
     * - Updates fields in a "Request for Quote" entity.
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
     * - Expects the related company user to be provided.
     * - Expects "Request for Quote" status to be "waiting".
     * - Sets status to "Cancelled".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelQuoteRequest(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer;

    /**
     * Specification:
     * - Expects quoter request reference to be provided.
     * - Retrieves "Request for Quote" entity filtered by reference.
     * - Expects "Request for Quote" status to be "in-progress".
     * - Expects "Request for Quote" quoteInProgress property exists.
     * - Expects "Request for Quote" validUntil property exists and greater than current time.
     * - Changes status from "in-progress" to "ready".
     * - Resets isHidden flag to false.
     * - Creates version from quoteInProgress property.
     * - Sets latest version.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function sendQuoteRequestToCustomer(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer;

    /**
     * Specification:
     * - Looks up one "Request for Quote" by provided quote request reference.
     * - Expects "Request for Quote" status to be "waiting".
     * - Requires latest version inside QuoteRequestTransfer.
     * - Requires quote inside QuoteRequestVersionTransfer.
     * - Sets status to "in-progress".
     * - Copies latest version quote to quoteInProgress property.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function markQuoteRequestInProgress(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer;

    /**
     * Specification:
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
     * - Retrieves "Request for Quote" versions.
     * - Filters by "Request for Quote" id when provided.
     * - Filters by quote request version reference when provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer
     */
    public function getQuoteRequestVersionCollectionByFilter(QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer): QuoteRequestVersionCollectionTransfer;

    /**
     * Specification:
     * - Validates quote request if quote request reference exists in quote.
     * - Checks if quote request version exists in database.
     * - Checks status from quote request.
     * - Checks that the current version is the latest.
     * - Checks valid until from quote request with current time.
     * - Returns true if quote requests pass all checks.
     * - Adds error message if not valid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCheckoutQuoteRequest(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool;

    /**
     * Specification:
     * - Retrieves requests for quote where valid_until less than current time and status is "ready".
     * - Updates requests of quote status to "closed".
     *
     * @api
     *
     * @return void
     */
    public function closeOutdatedQuoteRequests(): void;
}
