<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Processor\AbstractProductAvailability;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToAvailabilityStorageClientInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\RestResponseBuilder\ProductAbstractAvailabilityRestResponseBuilderInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

class AbstractProductAvailabilitiesReader implements AbstractProductAvailabilitiesReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var \Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToAvailabilityStorageClientInterface
     */
    protected $availabilityStorageClient;

    /**
     * @var \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\RestResponseBuilder\ProductAbstractAvailabilityRestResponseBuilderInterface
     */
    protected $productAbstractAvailabilityRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToAvailabilityStorageClientInterface $availabilityStorageClient
     * @param \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\RestResponseBuilder\ProductAbstractAvailabilityRestResponseBuilderInterface $productAbstractAvailabilityRestResourceBuilder
     */
    public function __construct(
        ProductAvailabilitiesRestApiToProductStorageClientInterface $productStorageClient,
        ProductAvailabilitiesRestApiToAvailabilityStorageClientInterface $availabilityStorageClient,
        ProductAbstractAvailabilityRestResponseBuilderInterface $productAbstractAvailabilityRestResourceBuilder
    ) {
        $this->availabilityStorageClient = $availabilityStorageClient;
        $this->productStorageClient = $productStorageClient;
        $this->productAbstractAvailabilityRestResponseBuilder = $productAbstractAvailabilityRestResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAbstractProductAvailability(RestRequestInterface $restRequest): RestResponseInterface
    {
        $abstractProductResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);
        if (!$abstractProductResource) {
            return $this->productAbstractAvailabilityRestResponseBuilder
                ->createProductAbstractSkuIsNotSpecifiedErrorResponse();
        }

        $productAbstractSku = $abstractProductResource->getId();

        $productAbstractAvailabilityRestResource = $this->findAbstractProductAvailabilityBySku($productAbstractSku, $restRequest);
        if (!$productAbstractAvailabilityRestResource) {
            return $this->productAbstractAvailabilityRestResponseBuilder
                ->createProductAbstractAvailabilityNotFoundErrorResponse();
        }

        return $this->productAbstractAvailabilityRestResponseBuilder
            ->createProductAbstractAvailabilityResponse($productAbstractAvailabilityRestResource);
    }

    /**
     * @param string $productAbstractSku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findAbstractProductAvailabilityBySku(string $productAbstractSku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $productAbstractData = $this->productStorageClient
            ->findProductAbstractStorageDataByMapping(
                static::PRODUCT_ABSTRACT_MAPPING_TYPE,
                $productAbstractSku,
                $restRequest->getMetadata()->getLocale()
            );

        if (!$productAbstractData) {
            return null;
        }

        $productAbstractAvailabilityTransfer = $this->availabilityStorageClient
            ->findProductAbstractAvailability($productAbstractData[static::KEY_ID_PRODUCT_ABSTRACT]);

        if (!$productAbstractAvailabilityTransfer) {
            return null;
        }

        return $this->productAbstractAvailabilityRestResponseBuilder
            ->createProductAbstractAvailabilityResource($productAbstractSku, $productAbstractAvailabilityTransfer);
    }
}
