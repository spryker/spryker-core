<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Spryker\Glue\CartCodesRestApi\Processor\Mapper\CartCodeMapperInterface;
use Spryker\Glue\CartsRestApi\CartsRestApiResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResource;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartCodeRestResponseBuilder implements CartCodeRestResponseBuilderInterface
{
    /**
     * @var RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var CartCodeMapperInterface
     */
    protected $cartCodeMapper;

    /**
     * @var CartsRestApiResourceInterface
     */
    protected $cartsRestApiResource;

    /**
     * @param RestResourceBuilderInterface $restResourceBuilder
     * @param CartCodeMapperInterface $cartCodeMapper
     * @param CartsRestApiResourceInterface $cartsRestApiResource
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CartCodeMapperInterface $cartCodeMapper,
        CartsRestApiResourceInterface $cartsRestApiResource
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->cartCodeMapper = $cartCodeMapper;
        $this->cartsRestApiResource = $cartsRestApiResource;
    }

    /**
     * @param CartCodeOperationResultTransfer $cartCodeOperationResultTransfer
     * @param RestRequestInterface $restRequest
     * @return RestResponseInterface
     */
    public function buildCartRestResponse(
        CartCodeOperationResultTransfer $cartCodeOperationResultTransfer,
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        $cartResource = $this->cartsRestApiResource->mapCartsResource($cartCodeOperationResultTransfer->getQuote(), $restRequest);

        return $this->restResourceBuilder->createRestResponse()->addResource($cartResource);
    }
}
