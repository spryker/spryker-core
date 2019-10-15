<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractRestResource;

/**
 * @method \Spryker\Glue\CartsRestApi\CartsRestApiFactory getFactory()
 */
class CartsRestApiResource extends AbstractRestResource implements CartsRestApiResourceInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param QuoteTransfer $quoteTransfer
     * @param RestRequestInterface $restRequest
     *
     * @return RestResourceInterface
     */
    public function mapCartsResource(QuoteTransfer $quoteTransfer, RestRequestInterface $restRequest): RestResourceInterface
    {
        return $this->getFactory()
            ->createCartsResourceMapper()
            ->mapCartsResource($quoteTransfer, $restRequest);
    }
}
