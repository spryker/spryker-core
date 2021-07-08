<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequestsRestApi;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;

/**
 * @method \Spryker\Client\QuoteRequestsRestApi\QuoteRequestsRestApiFactory getFactory()
 */
interface QuoteRequestsRestApiClientInterface
{
    /**
     * Specification:
     * - Creates a quote request by provided QuoteRequestTransfer.
     * - `QuoteRequest.companyUser.customer` should be provided.
     * - `QuoteRequest.latestVersion.quote.uuid` should be provided.
     * - Finds quote by uuid for customer.
     * - `Quoterequest.latestVersion.quote` should be probided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer;
}
