<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi;

use ArrayObject;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

/**
 * @method \Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiFactory getFactory()
 */
interface QuoteRequestsRestApiResourceInterface
{
    /**
     * Specification:
     * - Creates a failed response if errors happened.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageTransfer[]|\ArrayObject $errors
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createFailedErrorResponse(ArrayObject $errors): RestResponseInterface;

    /**
     * Specification:
     * - Creates a rest response.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createQuoteRequestRestResponse(QuoteRequestTransfer $quoteRequestTransfer): RestResponseInterface;
}
