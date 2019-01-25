<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RelatedProductsRestApi\Processor\RelatedProduct;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Spryker\Glue\RelatedProductsRestApi\Dependency\Resource\RelatedProductsRestApiToProductsRestApiResourceInterface;
use Symfony\Component\HttpFoundation\Response;

class RelatedProductRestResponseBuilder implements RelatedProductRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\RelatedProductsRestApi\Dependency\Resource\RelatedProductsRestApiToProductsRestApiResourceInterface
     */
    protected $productsRestApiResource;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\RelatedProductsRestApi\Dependency\Resource\RelatedProductsRestApiToProductsRestApiResourceInterface $productsRestApiResource
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        RelatedProductsRestApiToProductsRestApiResourceInterface $productsRestApiResource,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->productsRestApiResource = $productsRestApiResource;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAbstractProductsRestResponse(RestRequestInterface $restRequest, array $productViewTransfers): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($productViewTransfers as $productViewTransfer) {
            $abstractProductResource = $this->productsRestApiResource->findProductAbstractBySku(
                $productViewTransfer->getSku(),
                $restRequest
            );

            if ($abstractProductResource) {
                $restResponse->addResource($abstractProductResource);
            }
        }

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAbstractSkuMissingError(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAbstractNotFoundError(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CANT_FIND_ABSTRACT_PRODUCT)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_ABSTRACT_PRODUCT);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }
}
