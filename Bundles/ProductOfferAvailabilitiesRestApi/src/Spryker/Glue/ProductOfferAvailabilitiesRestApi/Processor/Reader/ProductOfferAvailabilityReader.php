<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Dependency\Client\ProductOfferAvailabilitiesRestApiToProductOfferAvailabilityStorageClientInterface;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Dependency\Client\ProductOfferAvailabilitiesRestApiToStoreClientInterface;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\RestResponseBuilder\ProductOfferAvailabilityRestResponseBuilderInterface;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\ProductOfferAvailabilitiesRestApiConfig;

class ProductOfferAvailabilityReader implements ProductOfferAvailabilityReaderInterface
{
    /**
     * @var \Spryker\Glue\ProductOfferAvailabilitiesRestApi\Dependency\Client\ProductOfferAvailabilitiesRestApiToProductOfferAvailabilityStorageClientInterface
     */
    protected $productOfferAvailabilityStorageClient;

    /**
     * @var \Spryker\Glue\ProductOfferAvailabilitiesRestApi\Dependency\Client\ProductOfferAvailabilitiesRestApiToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @var \Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\RestResponseBuilder\ProductOfferAvailabilityRestResponseBuilderInterface
     */
    protected $productOfferAvailabilityRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ProductOfferAvailabilitiesRestApi\Dependency\Client\ProductOfferAvailabilitiesRestApiToProductOfferAvailabilityStorageClientInterface $productOfferAvailabilityStorageClient
     * @param \Spryker\Glue\ProductOfferAvailabilitiesRestApi\Dependency\Client\ProductOfferAvailabilitiesRestApiToStoreClientInterface $storeClient
     * @param \Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\RestResponseBuilder\ProductOfferAvailabilityRestResponseBuilderInterface $productOfferAvailabilityRestResponseBuilder
     */
    public function __construct(
        ProductOfferAvailabilitiesRestApiToProductOfferAvailabilityStorageClientInterface $productOfferAvailabilityStorageClient,
        ProductOfferAvailabilitiesRestApiToStoreClientInterface $storeClient,
        ProductOfferAvailabilityRestResponseBuilderInterface $productOfferAvailabilityRestResponseBuilder
    ) {
        $this->productOfferAvailabilityStorageClient = $productOfferAvailabilityStorageClient;
        $this->storeClient = $storeClient;
        $this->productOfferAvailabilityRestResponseBuilder = $productOfferAvailabilityRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getProductOfferAvailabilities(RestRequestInterface $restRequest): RestResponseInterface
    {
        $productOfferRestResource = $restRequest->findParentResourceByType(ProductOfferAvailabilitiesRestApiConfig::RESOURCE_PRODUCT_OFFERS);

        if (!$productOfferRestResource || !$productOfferRestResource->getId()) {
            return $this->productOfferAvailabilityRestResponseBuilder->createProductOfferIdNotSpecifiedErrorResponse();
        }

        $idProductOfferRestResource = mb_strtolower($productOfferRestResource->getId());
        $productOfferAvailabilityRestResources = $this->getProductOfferAvailabilityRestResources([$idProductOfferRestResource]);

        $productOfferAvailabilityRestResource = $productOfferAvailabilityRestResources[$idProductOfferRestResource] ?? null;
        if (!isset($productOfferAvailabilityRestResource)) {
            return $this->productOfferAvailabilityRestResponseBuilder->createProductOfferAvailabilityEmptyRestResponse();
        }

        return $this->productOfferAvailabilityRestResponseBuilder->createProductOfferAvailabilityRestResponse($productOfferAvailabilityRestResource);
    }

    /**
     * @param string[] $productOfferReferences
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getProductOfferAvailabilityRestResources(array $productOfferReferences): array
    {
        $currentStoreName = $this->storeClient->getCurrentStore()->getName();

        $productOfferAvailabilityStorageTransfers = $this->productOfferAvailabilityStorageClient
            ->getByProductOfferReferences($productOfferReferences, $currentStoreName);

        return $this->productOfferAvailabilityRestResponseBuilder
            ->createProductOfferAvailabilityRestResources($productOfferAvailabilityStorageTransfers);
    }
}
