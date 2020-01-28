<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander;

use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\ProductMeasurementUnitRestResponseBuilderInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\ProductMeasurementUnitsRestApiConfig;

class ProductMeasurementUnitBySalesUnitResourceRelationshipExpander implements ProductMeasurementUnitBySalesUnitResourceRelationshipExpanderInterface
{
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';
    protected const PRODUCT_MEASUREMENT_UNIT_MAPPING_TYPE = 'code';

    /**
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\ProductMeasurementUnitRestResponseBuilderInterface
     */
    protected $productMeasurementUnitRestResponseBuilder;

    /**
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface
     */
    protected $productMeasurementUnitStorageClient;

    /**
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\ProductMeasurementUnitRestResponseBuilderInterface $productMeasurementUnitRestResponseBuilder
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient
     */
    public function __construct(
        ProductMeasurementUnitRestResponseBuilderInterface $productMeasurementUnitRestResponseBuilder,
        ProductMeasurementUnitsRestApiToProductStorageClientInterface $productStorageClient,
        ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient
    ) {
        $this->productMeasurementUnitRestResponseBuilder = $productMeasurementUnitRestResponseBuilder;
        $this->productStorageClient = $productStorageClient;
        $this->productMeasurementUnitStorageClient = $productMeasurementUnitStorageClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $codes = $this->getAllCodes($resources);

        $productMeasurementUnitData = $this->productMeasurementUnitStorageClient
            ->getProductMeasurementUnitsByMapping(
                static::PRODUCT_MEASUREMENT_UNIT_MAPPING_TYPE,
                $codes
            );

        foreach ($resources as $resource) {
            $productMeasurementUnitTransfer = $this->getProductMeasurementSalesUnitTransfer($resource)
                ->getProductMeasurementUnit();

            if (!isset($productMeasurementUnitData[$productMeasurementUnitTransfer->getIdProductMeasurementUnit()])) {
                continue;
            }

            $this->addRelationships(
                $productMeasurementUnitData[$productMeasurementUnitTransfer->getIdProductMeasurementUnit()],
                $resource
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer $productMeasurementUnitStorageTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return void
     */
    protected function addRelationships(
        ProductMeasurementUnitStorageTransfer $productMeasurementUnitStorageTransfer,
        RestResourceInterface $resource
    ): void {
        $productMeasurementUnitResource = $this->restResourceBuilder->createRestResource(
            ProductMeasurementUnitsRestApiConfig::RESOURCE_PRODUCT_MEASUREMENT_UNITS,
            (string)$productMeasurementUnitStorageTransfer->getCode(),
            $productMeasurementUnitStorageTransfer
        );

        $resource->addRelationship($productMeasurementUnitResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return string[]
     */
    protected function getAllCodes(array $resources): array
    {
        $codes = [];
        foreach ($resources as $resource) {
            $productMeasurementSalesUnitTransfer = $this->getProductMeasurementSalesUnitTransfer($resource);
            $codes[$resource->getId()] = $productMeasurementSalesUnitTransfer->getProductMeasurementUnit()->getCode();
        }

        return $codes;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    protected function getProductMeasurementSalesUnitTransfer(RestResourceInterface $resource): ProductMeasurementSalesUnitTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer */
        $productMeasurementSalesUnitTransfer = $resource->getAttributes();

        return $productMeasurementSalesUnitTransfer;
    }
}
