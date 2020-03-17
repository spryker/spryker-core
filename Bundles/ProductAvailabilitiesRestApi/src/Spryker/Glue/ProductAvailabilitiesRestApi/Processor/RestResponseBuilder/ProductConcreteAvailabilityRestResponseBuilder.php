<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\RestConcreteProductAvailabilityAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\ConcreteProductAvailabilitiesResourceMapperInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\ProductAvailabilitiesRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductConcreteAvailabilityRestResponseBuilder implements ProductConcreteAvailabilityRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\ConcreteProductAvailabilitiesResourceMapperInterface
     */
    protected $concreteProductsAvailabilityResourceMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\ConcreteProductAvailabilitiesResourceMapperInterface $concreteProductsAvailabilityResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ConcreteProductAvailabilitiesResourceMapperInterface $concreteProductsAvailabilityResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->concreteProductsAvailabilityResourceMapper = $concreteProductsAvailabilityResourceMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createProductConcreteAvailabilityResource(
        ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
    ): RestResourceInterface {
        $restProductsConcreteAvailabilityAttributesTransfer = $this->concreteProductsAvailabilityResourceMapper
            ->mapProductConcreteAvailabilityTransferToRestConcreteProductAvailabilityAttributesTransfer(
                $productConcreteAvailabilityTransfer,
                new RestConcreteProductAvailabilityAttributesTransfer()
            );

        $restResource = $this->restResourceBuilder->createRestResource(
            ProductAvailabilitiesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_AVAILABILITIES,
            $productConcreteAvailabilityTransfer->getSku(),
            $restProductsConcreteAvailabilityAttributesTransfer
        );

        $restResource->addLink(
            RestLinkInterface::LINK_SELF,
            $this->getProductConcreteAvailabilityResourceSelfLink($productConcreteAvailabilityTransfer->getSku())
        );

        return $restResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $productConcreteAvailabilityRestResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductConcreteAvailabilityResponse(RestResourceInterface $productConcreteAvailabilityRestResource): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        return $restResponse->addResource($productConcreteAvailabilityRestResource);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductConcreteSkuIsNotSpecifiedErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductConcreteAvailabilityNotFoundErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductAvailabilitiesRestApiConfig::RESPONSE_CODE_CONCRETE_PRODUCT_AVAILABILITY_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductAvailabilitiesRestApiConfig::RESPONSE_DETAILS_CONCRETE_PRODUCT_AVAILABILITY_NOT_FOUND);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @param string $productConcreteSku
     *
     * @return string
     */
    protected function getProductConcreteAvailabilityResourceSelfLink(string $productConcreteSku): string
    {
        return sprintf(
            '%s/%s/%s',
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku,
            ProductAvailabilitiesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_AVAILABILITIES
        );
    }
}
