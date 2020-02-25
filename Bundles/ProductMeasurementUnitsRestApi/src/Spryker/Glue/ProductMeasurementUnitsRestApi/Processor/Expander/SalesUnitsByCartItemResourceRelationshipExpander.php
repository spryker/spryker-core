<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestItemsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\SalesUnitRestResponseBuilderInterface;

class SalesUnitsByCartItemResourceRelationshipExpander implements SalesUnitsByCartItemResourceRelationshipExpanderInterface
{
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';
    protected const ATTRIBUTE_SKU = 'sku';
    protected const ATTRIBUTE_SALES_UNIT = 'salesUnit';

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

        $productMeasurementSalesUnitTransfers = $this->productMeasurementUnitStorageClient
            ->getProductMeasurementSalesUnitsByProductConcreteIds($productConcreteIds);

        $restSalesUnitsResources = [];
        $productConcreteSkus = array_flip($productConcreteIds);
        foreach ($productMeasurementSalesUnitTransfers as $idProductConcrete => $productConcreteProductMeasurementSalesUnitTransfers) {
            $productConcreteSku = $productConcreteSkus[$idProductConcrete];
            foreach ($productConcreteProductMeasurementSalesUnitTransfers as $productConcreteProductMeasurementSalesUnitTransfer) {
                $restSalesUnitsResources[$productConcreteSku][] =
                    $this->salesUnitRestResponseBuilder->createSalesUnitRestResource(
                        $productConcreteProductMeasurementSalesUnitTransfer,
                        $productConcreteSku
                    );
            }
        }

        foreach ($resources as $resource) {
            $this->addSalesUnitResourceRelationships($resource, $restSalesUnitsResources);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][] $restSalesUnitsResources
     *
     * @return void
     */
    protected function addSalesUnitResourceRelationships(
        RestResourceInterface $resource,
        array $restSalesUnitsResources
    ): void {
        foreach ($restSalesUnitsResources as $productConcreteSku => $productConcreteRestSalesUnitsResources) {
            $restCartItemsAttributesTransfer = $resource->getAttributes();
            if (!$restCartItemsAttributesTransfer instanceof RestItemsAttributesTransfer) {
                continue;
            }

            if ($productConcreteSku !== $restCartItemsAttributesTransfer->getSku()) {
                continue;
            }

            $restCartItemsSalesUnitAttributesTransfer = $restCartItemsAttributesTransfer->getSalesUnit();
            if (!$restCartItemsSalesUnitAttributesTransfer) {
                continue;
            }

            $this->addResourceRelationship(
                $resource,
                $productConcreteRestSalesUnitsResources,
                $restCartItemsSalesUnitAttributesTransfer->getId()
            );
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param array $productConcreteRestSalesUnitsResources
     * @param int $salesUnitId
     *
     * @return void
     */
    protected function addResourceRelationship(
        RestResourceInterface $resource,
        array $productConcreteRestSalesUnitsResources,
        int $salesUnitId
    ): void {
        foreach ($productConcreteRestSalesUnitsResources as $productConcreteRestSalesUnitsResource) {
            if ($salesUnitId !== (int)$productConcreteRestSalesUnitsResource->getId()) {
                continue;
            }

            $resource->addRelationship($productConcreteRestSalesUnitsResource);
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
            if (!$resource->getAttributes()->offsetExists(static::ATTRIBUTE_SKU)) {
                continue;
            }

            $skus[] = $resource->getAttributes()->offsetGet(static::ATTRIBUTE_SKU);
        }

        return $skus;
    }
}
