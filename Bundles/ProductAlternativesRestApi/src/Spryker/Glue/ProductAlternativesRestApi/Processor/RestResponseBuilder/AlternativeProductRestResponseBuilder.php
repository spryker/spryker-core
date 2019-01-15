<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAlternativesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class AlternativeProductRestResponseBuilder implements AlternativeProductRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createConcreteProductSkuMissingError(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED);

        return $this->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createConcreteProductNotFoundError(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CANT_FIND_CONCRETE_PRODUCT)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_CONCRETE_PRODUCT);

        return $this->createRestResponse()->addError($restErrorTransfer);
    }
}
