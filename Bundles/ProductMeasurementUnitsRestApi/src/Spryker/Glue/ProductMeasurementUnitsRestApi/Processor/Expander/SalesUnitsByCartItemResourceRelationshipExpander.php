<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Expander;

use Generated\Shared\Transfer\ProductConcreteProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\RestItemsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductMeasurementUnitStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\RestResponseBuilder\SalesUnitRestResponseBuilderInterface;

class SalesUnitsByCartItemResourceRelationshipExpander implements SalesUnitsByCartItemResourceRelationshipExpanderInterface
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
            $restItemsAttributesTransfer = $resource->getAttributes();
            if (!$restItemsAttributesTransfer instanceof RestItemsAttributesTransfer) {
                continue;
            }

            foreach ($productConcreteProductMeasurementSalesUnitTransfers as $productConcreteProductMeasurementSalesUnitTransfer) {
                $this->addResourceRelationship(
                    $resource,
                    $productConcreteProductMeasurementSalesUnitTransfer,
                    $restItemsAttributesTransfer,
                    $productConcreteSkus
                );
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Generated\Shared\Transfer\ProductConcreteProductMeasurementSalesUnitTransfer $productConcreteProductMeasurementSalesUnitTransfer
     * @param \Generated\Shared\Transfer\RestItemsAttributesTransfer $restItemsAttributesTransfer
     * @param array $productConcreteSkus
     *
     * @return void
     */
    protected function addResourceRelationship(
        RestResourceInterface $resource,
        ProductConcreteProductMeasurementSalesUnitTransfer $productConcreteProductMeasurementSalesUnitTransfer,
        RestItemsAttributesTransfer $restItemsAttributesTransfer,
        array $productConcreteSkus
    ): void {
        $productConcreteSku = $productConcreteSkus[$productConcreteProductMeasurementSalesUnitTransfer->getIdProductConcrete()];
        if ($productConcreteSku !== $restItemsAttributesTransfer->getSku()) {
            return;
        }

        $restCartItemsSalesUnitAttributesTransfer = $restItemsAttributesTransfer->getSalesUnit();
        if (!$restCartItemsSalesUnitAttributesTransfer) {
            return;
        }

        foreach ($productConcreteProductMeasurementSalesUnitTransfer->getProductMeasurementSalesUnits() as $productMeasurementSalesUnitTransfer) {
            if ($restCartItemsSalesUnitAttributesTransfer->getId() !== $productMeasurementSalesUnitTransfer->getIdProductMeasurementSalesUnit()) {
                continue;
            }

            $resource->addRelationship(
                $this->salesUnitRestResponseBuilder->createSalesUnitRestResource(
                    $productMeasurementSalesUnitTransfer,
                    $productConcreteSku
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
            $restItemsAttributesTransfer = $resource->getAttributes();
            if (!$restItemsAttributesTransfer instanceof RestItemsAttributesTransfer) {
                continue;
            }

            $skus[] = $restItemsAttributesTransfer->getSku();
        }

        return $skus;
    }
}
