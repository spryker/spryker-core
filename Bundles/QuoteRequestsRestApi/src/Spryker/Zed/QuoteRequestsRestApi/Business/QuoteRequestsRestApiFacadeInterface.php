<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestsRestApi\Business;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;

/**
 * @method \Spryker\Zed\QuoteRequestsRestApi\Business\QuoteRequestsRestApiBusinessFactory getFactory()
 */
interface QuoteRequestsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Creates a quote request by provided `QuoteRequestTransfer`.
     * - `QuoteRequest.companyUser.customer` should be provided.
     * - `QuoteRequest.latestVersion.quote.uuid` should be provided.
     * - Finds quote by UUID for the customer.
     * - `Quoterequest.latestVersion.quote` should be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer;
}
