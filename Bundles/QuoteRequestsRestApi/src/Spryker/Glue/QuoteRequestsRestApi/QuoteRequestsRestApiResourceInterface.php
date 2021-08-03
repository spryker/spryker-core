<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

/**
 * @method \Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiFactory getFactory()
 */
interface QuoteRequestsRestApiResourceInterface
{
    /**
     * Specification:
     * - Creates a rest response.
     * - Transfers `quoteRequestResponse.quoteRequest` to `RestQuoteRequestsAttributes` with expanders from `Pyz\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiDependencyProvider::getRestQuoteRequestAttributesExpanderPlugins`.
     * - `RestQuoteRequestsAttributes` is passed to response.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createQuoteRequestRestResponse(
        QuoteRequestResponseTransfer $quoteRequestResponseTransfer,
        string $localeName
    ): RestResponseInterface;
}
