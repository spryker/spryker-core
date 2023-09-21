<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Processor\Builder;

use Generated\Shared\Transfer\RestProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Processor\Mapper\ProductOfferServicePointAvailabilityMapperInterface;
use Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\ProductOfferServicePointAvailabilitiesRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductOfferServicePointAvailabilityResponseBuilder implements ProductOfferServicePointAvailabilityResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected RestResourceBuilderInterface $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Processor\Mapper\ProductOfferServicePointAvailabilityMapperInterface
     */
    protected ProductOfferServicePointAvailabilityMapperInterface $productOfferServicePointAvailabilityMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Processor\Mapper\ProductOfferServicePointAvailabilityMapperInterface $productOfferServicePointAvailabilityMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ProductOfferServicePointAvailabilityMapperInterface $productOfferServicePointAvailabilityMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productOfferServicePointAvailabilityMapper = $productOfferServicePointAvailabilityMapper;
    }

    /**
     * @param array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>> $productOfferServicePointAvailabilities
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferServicePointAvailabilityCollectionRestResponse(
        array $productOfferServicePointAvailabilities
    ): RestResponseInterface {
        $restProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer = $this->productOfferServicePointAvailabilityMapper->mapProductOfferServicePointAvailabilitiesToRestProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer(
            $productOfferServicePointAvailabilities,
            new RestProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer(),
        );

        $productOfferServicePointAvailabilityRestResource = $this->restResourceBuilder->createRestResource(
            ProductOfferServicePointAvailabilitiesRestApiConfig::RESOURCE_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITIES,
            null,
            $restProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer,
        );

        return $this->restResourceBuilder
            ->createRestResponse()
            ->setStatus(Response::HTTP_OK)
            ->addResource($productOfferServicePointAvailabilityRestResource);
    }
}
