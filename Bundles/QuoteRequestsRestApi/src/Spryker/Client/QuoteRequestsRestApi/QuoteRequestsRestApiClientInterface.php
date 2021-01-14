<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequestsRestApi;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestsRequestTransfer;

/**
 * @method \Spryker\Client\QuoteRequestsRestApi\QuoteRequestsRestApiFactory getFactory()
 */
interface QuoteRequestsRestApiClientInterface
{
    /**
     *  Specification:
     * - Creates a quote request by provided QuoteRequestsRequestTransfer.
     * - cartUuid should be provided.
     * - customer should be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestsRequestTransfer $quoteRequestsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestsRequestTransfer $quoteRequestsRequestTransfer): QuoteRequestResponseTransfer;
}
