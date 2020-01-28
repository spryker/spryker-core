<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\ProductMeasurementUnitRestResponseBuilderInterface;

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
        $productMeasurementUnitStorageTransfers = $this->productMeasurementUnitStorageClient
            ->getProductMeasurementUnitsByMapping(
                static::PRODUCT_MEASUREMENT_UNIT_MAPPING_TYPE,
                $codes
            );

        foreach ($resources as $resource) {
            $this->addProductMeasurementUnitResourceRelationships($productMeasurementUnitStorageTransfers, $resource);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer[] $productMeasurementUnitStorageTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return void
     */
    protected function addProductMeasurementUnitResourceRelationships(
        array $productMeasurementUnitStorageTransfers,
        RestResourceInterface $resource
    ): void {
        foreach ($productMeasurementUnitStorageTransfers as $productMeasurementUnitStorageTransfer) {
            $measurementUnitCode = $this->getMeasurementUnitCode($resource);
            if ($measurementUnitCode === $productMeasurementUnitStorageTransfer->getCode()) {
                $productMeasurementUnitRestResource = $this->productMeasurementUnitRestResponseBuilder
                    ->createProductMeasurementUnitRestResource(
                        (new ProductMeasurementUnitTransfer())
                            ->fromArray($productMeasurementUnitStorageTransfer->toArray(), true)
                    );

                $resource->addRelationship($productMeasurementUnitRestResource);
            }
        }
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
            $codes[$resource->getId()] = $this->getMeasurementUnitCode($resource);
        }

        return $codes;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return string
     */
    protected function getMeasurementUnitCode(RestResourceInterface $resource): string
    {
        /** @var \Generated\Shared\Transfer\RestSalesUnitsAttributesTransfer $restSalesUnitsAttributesTransfer */
        $restSalesUnitsAttributesTransfer = $resource->getAttributes();

        return $restSalesUnitsAttributesTransfer->getMeasurementUnitCode();
    }
}
