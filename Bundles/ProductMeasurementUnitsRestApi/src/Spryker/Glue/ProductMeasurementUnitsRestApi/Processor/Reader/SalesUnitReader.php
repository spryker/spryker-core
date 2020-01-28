<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\SalesUnitRestResponseBuilderInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\ProductMeasurementUnitsRestApiConfig;

class SalesUnitReader implements SalesUnitReaderInterface
{
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';

    /**
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\SalesUnitRestResponseBuilderInterface
     */
    protected $salesUnitRestResponseBuilder;

    /**
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface
     */
    protected $productMeasurementUnitStorageClient;

    /**
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\ProductMeasurementUnitsRestApiConfig
     */
    protected $productMeasurementUnitsRestApiConfig;

    /**
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\SalesUnitRestResponseBuilderInterface $salesUnitRestResponseBuilder
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\ProductMeasurementUnitsRestApiConfig $productMeasurementUnitsRestApiConfig
     */
    public function __construct(
        SalesUnitRestResponseBuilderInterface $salesUnitRestResponseBuilder,
        ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient,
        ProductMeasurementUnitsRestApiToProductStorageClientInterface $productStorageClient,
        ProductMeasurementUnitsRestApiConfig $productMeasurementUnitsRestApiConfig
    ) {
        $this->salesUnitRestResponseBuilder = $salesUnitRestResponseBuilder;
        $this->productMeasurementUnitStorageClient = $productMeasurementUnitStorageClient;
        $this->productStorageClient = $productStorageClient;
        $this->productMeasurementUnitsRestApiConfig = $productMeasurementUnitsRestApiConfig;
    }
    
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getSalesUnit(RestRequestInterface $restRequest): RestResponseInterface
    {
        $parentResource = $restRequest->findParentResourceByType(
            ProductMeasurementUnitsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS
        );

        if (!$parentResource) {
            return $this->salesUnitRestResponseBuilder->createProductConcreteSkuMissingErrorResponse();
        }

        $productConcreteIds = $this->productStorageClient->getProductConcreteIdsByMapping(
            static::PRODUCT_CONCRETE_MAPPING_TYPE,
            [$parentResource->getId()],
            $restRequest->getMetadata()->getLocale()
        );

        if (!$productConcreteIds) {
            return $this->salesUnitRestResponseBuilder->createProductConcreteNotFoundErrorResponse();
        }

        $productMeasurementSalesUnitTransfers = $this->productMeasurementUnitStorageClient
            ->getProductMeasurementSalesUnitsByProductConcreteIds($productConcreteIds);

        $restResponse = $this->salesUnitRestResponseBuilder->createRestResponse();
        if (!$productMeasurementSalesUnitTransfers) {
            return $restResponse;
        }

        foreach (reset($productMeasurementSalesUnitTransfers) as $productMeasurementSalesUnitTransfer) {
            $restResponse->addResource(
                $this->salesUnitRestResponseBuilder->createSalesUnitRestResource($productMeasurementSalesUnitTransfer)
            );
        }

        return $restResponse;
    }
}
