<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ProductBundlesRestApi\ProductBundlesRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class BundledProductRestResponseBuilder implements BundledProductRestResponseBuilderInterface
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
    public function createProductConcreteSkuNotSpecifiedErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductBundlesRestApiConfig::RESPONSE_CODE_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setDetail(ProductBundlesRestApiConfig::RESPONSE_DETAIL_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createBundledProductEmptyRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $bundledProductRestResources
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createBundledProductCollectionRestResponse(
        array $bundledProductRestResources
    ): RestResponseInterface {
        $productOffersRestResponse = $this->restResourceBuilder->createRestResponse();
        foreach ($bundledProductRestResources as $bundledProductRestResource) {
            $productOffersRestResponse->addResource($bundledProductRestResource);
        }

        return $productOffersRestResponse;
    }
}
