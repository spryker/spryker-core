<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Spryker\Glue\CartCodesRestApi\Processor\Mapper\CartCodeMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResource;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

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
     * @param RestResourceBuilderInterface $restResourceBuilder
     * @param CartCodeMapperInterface $cartCodeMapper
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder, CartCodeMapperInterface $cartCodeMapper)
    {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->cartCodeMapper = $cartCodeMapper;
    }

    /**
     * @param CartCodeOperationResultTransfer $cartCodeOperationResultTransfer
     * @return RestResponseInterface
     */
    public function buildCartRestResponse(CartCodeOperationResultTransfer $cartCodeOperationResultTransfer): RestResponseInterface
    {
        //TODO:
        return $this->restResourceBuilder->createRestResponse()->addResource(new RestResource('test'));
    }
}
