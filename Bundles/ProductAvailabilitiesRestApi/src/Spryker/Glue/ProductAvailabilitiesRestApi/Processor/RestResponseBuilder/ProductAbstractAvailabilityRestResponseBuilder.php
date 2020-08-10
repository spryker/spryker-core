<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\RestAbstractProductAvailabilityAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapperInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\ProductAvailabilitiesRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductAbstractAvailabilityRestResponseBuilder implements ProductAbstractAvailabilityRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapperInterface
     */
    protected $productsAvailabilityResourceMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapperInterface $abstractProductAvailabilityResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        AbstractProductAvailabilitiesResourceMapperInterface $abstractProductAvailabilityResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productsAvailabilityResourceMapper = $abstractProductAvailabilityResourceMapper;
    }

    /**
     * @param string $productAbstractSku
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createProductAbstractAvailabilityResource(
        string $productAbstractSku,
        ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
    ): RestResourceInterface {
        $restProductsAbstractAvailabilityAttributesTransfer = $this->productsAvailabilityResourceMapper
            ->mapProductAbstractAvailabilityTransferToRestAbstractProductAvailabilityAttributesTransfer(
                $productAbstractAvailabilityTransfer,
                new RestAbstractProductAvailabilityAttributesTransfer()
            );

        $restResource = $this->restResourceBuilder->createRestResource(
            ProductAvailabilitiesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_AVAILABILITIES,
            $productAbstractSku,
            $restProductsAbstractAvailabilityAttributesTransfer
        );

        $restResource->addLink(
            RestLinkInterface::LINK_SELF,
            $this->getProductAbstractAvailabilityResourceSelfLink($productAbstractSku)
        );

        return $restResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $productAbstractAvailabilityRestResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAbstractAvailabilityResponse(RestResourceInterface $productAbstractAvailabilityRestResource): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        return $restResponse->addResource($productAbstractAvailabilityRestResource);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAbstractSkuIsNotSpecifiedErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAbstractAvailabilityNotFoundErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductAvailabilitiesRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_AVAILABILITY_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductAvailabilitiesRestApiConfig::RESPONSE_DETAILS_ABSTRACT_PRODUCT_AVAILABILITY_NOT_FOUND);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @param string $productAbstractSku
     *
     * @return string
     */
    protected function getProductAbstractAvailabilityResourceSelfLink(string $productAbstractSku): string
    {
        return sprintf(
            '%s/%s/%s',
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            $productAbstractSku,
            ProductAvailabilitiesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_AVAILABILITIES
        );
    }
}
