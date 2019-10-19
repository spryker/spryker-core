<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractRestResource;

/**
 * @method \Spryker\Glue\CartsRestApi\CartsRestApiFactory getFactory()
 */
class CartsRestApiResource extends AbstractRestResource implements CartsRestApiResourceInterface
{
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
        return $this->getFactory()->createCartRestResponseBuilder()->createCartRestResponse($quoteTransfer, $restRequest);
    }
}
