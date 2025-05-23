<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Dependency\RestResource;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class QuoteRequestsRestApiToCartsRestApiResourceBridge implements QuoteRequestsRestApiToCartsRestApiResourceInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\CartsRestApiResourceInterface
     */
    protected $quoteRequestsRestApiResource;

    /**
     * @param \Spryker\Glue\CartsRestApi\CartsRestApiResourceInterface $quoteRequestsRestApiResource
     */
    public function __construct($quoteRequestsRestApiResource)
    {
        $this->quoteRequestsRestApiResource = $quoteRequestsRestApiResource;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartRestResponse(
        QuoteTransfer $quoteTransfer,
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        return $this->quoteRequestsRestApiResource->createCartRestResponse($quoteTransfer, $restRequest);
    }
}
