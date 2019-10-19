<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Spryker\Glue\CartCodesRestApi\Processor\Mapper\CartCodeMapperInterface;
use Spryker\Glue\CartsRestApi\CartsRestApiResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartCodeRestResponseBuilder implements CartCodeRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CartCodesRestApi\Processor\Mapper\CartCodeMapperInterface
     */
    protected $cartCodeMapper;

    /**
     * @var \Spryker\Glue\CartsRestApi\CartsRestApiResourceInterface
     */
    protected $cartsRestApiResource;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CartCodesRestApi\Processor\Mapper\CartCodeMapperInterface $cartCodeMapper
     * @param \Spryker\Glue\CartsRestApi\CartsRestApiResourceInterface $cartsRestApiResource
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
     * @param \Generated\Shared\Transfer\CartCodeOperationResultTransfer $cartCodeOperationResultTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildCartRestResponse(
        CartCodeOperationResultTransfer $cartCodeOperationResultTransfer,
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        return $this->cartsRestApiResource->createCartRestResponse($cartCodeOperationResultTransfer->getQuote(), $restRequest);
    }
}
