<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestProductOfferAvailabilitiesAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Mapper\ProductOfferAvailabilityMapperInterface;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\ProductOfferAvailabilitiesRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductOfferAvailabilityRestResponseBuilder implements ProductOfferAvailabilityRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Mapper\ProductOfferAvailabilityMapperInterface
     */
    protected $productOfferAvailabilityMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Mapper\ProductOfferAvailabilityMapperInterface $productOfferAvailabilityMapper
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder, ProductOfferAvailabilityMapperInterface $productOfferAvailabilityMapper)
    {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productOfferAvailabilityMapper = $productOfferAvailabilityMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer[] $productOfferAvailabilityStorageTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createProductOfferAvailabilityRestResources(array $productOfferAvailabilityStorageTransfers): array
    {
        $productOfferAvailabilityRestResources = [];
        foreach ($productOfferAvailabilityStorageTransfers as $productOfferReference => $productOfferAvailabilityStorageTransfer) {
            $productOfferAvailabilityRestResources[$productOfferReference] = $this->createProductOfferAvailabilityRestResource(
                $productOfferAvailabilityStorageTransfer,
                $productOfferReference
            );
        }

        return $productOfferAvailabilityRestResources;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferAvailabilityEmptyRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $productOfferAvailabilityRestResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferAvailabilityRestResponse(
        RestResourceInterface $productOfferAvailabilityRestResource
    ): RestResponseInterface {
        return $this->restResourceBuilder->createRestResponse()->addResource($productOfferAvailabilityRestResource);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferIdNotSpecifiedErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductOfferAvailabilitiesRestApiConfig::RESPONSE_CODE_PRODUCT_OFFER_ID_IS_NOT_SPECIFIED)
            ->setDetail(ProductOfferAvailabilitiesRestApiConfig::RESPONSE_DETAIL_PRODUCT_OFFER_ID_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer $productOfferAvailabilityStorageTransfer
     * @param string $productOfferReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createProductOfferAvailabilityRestResource(
        ProductOfferAvailabilityStorageTransfer $productOfferAvailabilityStorageTransfer,
        string $productOfferReference
    ): RestResourceInterface {
        $restProductOfferAvailabilitiesAttributesTransfer = $this->productOfferAvailabilityMapper
            ->mapProductOfferAvailabilityStorageTransferToRestProductOfferAvailabilitiesAttributesTransfer(
                $productOfferAvailabilityStorageTransfer,
                new RestProductOfferAvailabilitiesAttributesTransfer()
            );

        $productOfferAvailabilityRestResource = $this->restResourceBuilder->createRestResource(
            ProductOfferAvailabilitiesRestApiConfig::RESOURCE_PRODUCT_OFFER_AVAILABILITIES,
            $productOfferReference,
            $restProductOfferAvailabilitiesAttributesTransfer
        );

        $productOfferAvailabilityRestResource->addLink(
            RestLinkInterface::LINK_SELF,
            sprintf(
                '%s/%s/%s',
                ProductOfferAvailabilitiesRestApiConfig::RESOURCE_PRODUCT_OFFERS,
                $productOfferReference,
                ProductOfferAvailabilitiesRestApiConfig::RESOURCE_PRODUCT_OFFER_AVAILABILITIES
            )
        );

        return $productOfferAvailabilityRestResource;
    }
}
