<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Dependency\RestApiResource;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

class CartCodesRestApiToCartsRestApiResourceBridge implements CartCodesRestApiToCartsRestApiResourceInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\CartsRestApiResourceInterface
     */
    protected $cartsRestApiResource;

    /**
     * @param \Spryker\Glue\CartsRestApi\CartsRestApiResourceInterface $cartsRestApiResource
     */
    public function __construct($cartsRestApiResource)
    {
        $this->cartsRestApiResource = $cartsRestApiResource;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createGuestCartRestResponse(QuoteTransfer $quoteTransfer): RestResponseInterface
    {
        return $this->cartsRestApiResource->createGuestCartRestResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartRestResponse(QuoteTransfer $quoteTransfer): RestResponseInterface
    {
        return $this->cartsRestApiResource->createCartRestResponse($quoteTransfer);
    }
}
