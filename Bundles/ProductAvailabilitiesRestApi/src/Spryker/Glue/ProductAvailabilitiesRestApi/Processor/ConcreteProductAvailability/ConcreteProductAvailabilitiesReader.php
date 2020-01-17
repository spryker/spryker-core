<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Processor\ConcreteProductAvailability;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToAvailabilityStorageClientInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\RestResponseBuilder\ProductConcreteAvailabilityRestResponseBuilderInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

class ConcreteProductAvailabilitiesReader implements ConcreteProductAvailabilitiesReaderInterface
{
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var \Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToAvailabilityStorageClientInterface
     */
    protected $availabilityStorageClient;

    /**
     * @var \Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\RestResponseBuilder\ProductConcreteAvailabilityRestResponseBuilderInterface
     */
    protected $productAbstractAvailabilityRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToAvailabilityStorageClientInterface $availabilityStorageClient
     * @param \Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\RestResponseBuilder\ProductConcreteAvailabilityRestResponseBuilderInterface $productAbstractAvailabilityRestResourceBuilder
     */
    public function __construct(
        ProductAvailabilitiesRestApiToAvailabilityStorageClientInterface $availabilityStorageClient,
        ProductAvailabilitiesRestApiToProductStorageClientInterface $productStorageClient,
        ProductConcreteAvailabilityRestResponseBuilderInterface $productAbstractAvailabilityRestResourceBuilder
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
    public function getConcreteProductAvailability(RestRequestInterface $restRequest): RestResponseInterface
    {
        $concreteProductResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS);
        if (!$concreteProductResource) {
            return $this->productAbstractAvailabilityRestResponseBuilder
                ->createProductConcreteSkuIsNotSpecifiedErrorResponse();
        }

        $productConcreteSku = $concreteProductResource->getId();
        $concreteProductAvailabilityRestResource = $this->findConcreteProductAvailabilityBySku(
            $productConcreteSku,
            $restRequest
        );

        if (!$concreteProductAvailabilityRestResource) {
            return $this->productAbstractAvailabilityRestResponseBuilder
                ->createProductConcreteAvailabilityNotFoundErrorResponse();
        }

        return $this->productAbstractAvailabilityRestResponseBuilder
            ->createProductConcreteAvailabilityResponse($concreteProductAvailabilityRestResource);
    }

    /**
     * @param string $productConcreteSku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findConcreteProductAvailabilityBySku(
        string $productConcreteSku,
        RestRequestInterface $restRequest
    ): ?RestResourceInterface {
        $localeName = $restRequest->getMetadata()->getLocale();
        $productConcreteStorageData = $this->productStorageClient
            ->findProductConcreteStorageDataByMapping(
                static::PRODUCT_CONCRETE_MAPPING_TYPE,
                $productConcreteSku,
                $localeName
            );

        if (!$productConcreteStorageData) {
            return null;
        }

        $idProductAbstract = $productConcreteStorageData[static::KEY_ID_PRODUCT_ABSTRACT];

        $productAbstractAvailabilityTransfer = $this->availabilityStorageClient
            ->findProductAbstractAvailability((int)$idProductAbstract);

        if (!$productAbstractAvailabilityTransfer) {
            return null;
        }

        $productConcreteAvailabilityTransfers = $productAbstractAvailabilityTransfer->getProductConcreteAvailabilities();
        foreach ($productConcreteAvailabilityTransfers as $productConcreteAvailabilityTransfer) {
            if ($productConcreteAvailabilityTransfer->getSku() === $productConcreteSku) {
                return $this->productAbstractAvailabilityRestResponseBuilder
                    ->createProductConcreteAvailabilityResource($productConcreteAvailabilityTransfer);
            }
        }

        return null;
    }
}
