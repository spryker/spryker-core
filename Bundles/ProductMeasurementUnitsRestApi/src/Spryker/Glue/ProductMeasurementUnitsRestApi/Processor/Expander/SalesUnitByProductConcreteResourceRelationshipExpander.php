<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\SalesUnitRestResponseBuilderInterface;

class SalesUnitByProductConcreteResourceRelationshipExpander implements SalesUnitByProductConcreteResourceRelationshipExpanderInterface
{
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';

    /**
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\SalesUnitRestResponseBuilderInterface
     */
    protected $salesUnitRestResponseBuilder;

    /**
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface
     */
    protected $productMeasurementUnitStorageClient;

    /**
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\SalesUnitRestResponseBuilderInterface $salesUnitRestResponseBuilder
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient
     */
    public function __construct(
        SalesUnitRestResponseBuilderInterface $salesUnitRestResponseBuilder,
        ProductMeasurementUnitsRestApiToProductStorageClientInterface $productStorageClient,
        ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient
    ) {
        $this->salesUnitRestResponseBuilder = $salesUnitRestResponseBuilder;
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
        $productConcreteSkus = $this->getProductConcreteSkus($resources);
        $productConcreteIds = $this->productStorageClient->getProductConcreteIdsByMapping(
            static::PRODUCT_CONCRETE_MAPPING_TYPE,
            $productConcreteSkus,
            $restRequest->getMetadata()->getLocale()
        );

        $productConcreteProductMeasurementSalesUnitTransfers = $this->productMeasurementUnitStorageClient
            ->getProductMeasurementSalesUnitsByProductConcreteIds($productConcreteIds);
        $productConcreteSkus = array_flip($productConcreteIds);
        foreach ($resources as $resource) {
            foreach ($productConcreteProductMeasurementSalesUnitTransfers as $productConcreteProductMeasurementSalesUnitTransfer) {
                $concreteProductsRestAttributesTransfer = $resource->getAttributes();
                if (!$concreteProductsRestAttributesTransfer instanceof ConcreteProductsRestAttributesTransfer) {
                    continue;
                }

                $productConcreteSku = $productConcreteSkus[$productConcreteProductMeasurementSalesUnitTransfer->getIdProductConcrete()];
                if ($productConcreteSku !== $concreteProductsRestAttributesTransfer->getSku()) {
                    continue;
                }

                $this->addResourceRelationship($resource, $productConcreteProductMeasurementSalesUnitTransfer->getProductMeasurementSalesUnits());
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[] $productMeasurementSalesUnitTransfers
     *
     * @return void
     */
    protected function addResourceRelationship(
        RestResourceInterface $resource,
        ArrayObject $productMeasurementSalesUnitTransfers
    ): void {
        foreach ($productMeasurementSalesUnitTransfers as $productMeasurementSalesUnitTransfer) {
            $resource->addRelationship(
                $this->salesUnitRestResponseBuilder->createSalesUnitRestResource(
                    $productMeasurementSalesUnitTransfer,
                    $resource->getId()
                )
            );
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return string[]
     */
    protected function getProductConcreteSkus(array $resources): array
    {
        $skus = [];
        foreach ($resources as $resource) {
            $skus[] = $resource->getId();
        }

        return $skus;
    }
}
