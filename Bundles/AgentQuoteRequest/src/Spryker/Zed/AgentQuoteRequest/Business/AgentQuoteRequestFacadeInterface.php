<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest\Business;

use Generated\Shared\Transfer\CompanyUserAutocompleteResponseTransfer;
use Generated\Shared\Transfer\CompanyUserQueryTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;

interface AgentQuoteRequestFacadeInterface
{
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

    /**
     * Specification:
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
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function setQuoteRequestEditable(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer;

    /**
     * Specification:
     * - Returns CompanyUserAutocompleteResponseTransfer with list of company users found by query.
     * - Search works by first name, last name and email.
     * - If company users by query are not exist, collection will be empty.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserQueryTransfer $customerQueryTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserAutocompleteResponseTransfer
     */
    public function findCompanyUsersByQuery(CompanyUserQueryTransfer $customerQueryTransfer): CompanyUserAutocompleteResponseTransfer;
}
