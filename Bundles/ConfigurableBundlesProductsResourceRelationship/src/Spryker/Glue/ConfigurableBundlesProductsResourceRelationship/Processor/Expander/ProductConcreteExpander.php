<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Processor\Expander;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer;
use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceInterface;
use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Processor\Reader\ProductConcreteReaderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ProductConcreteExpander implements ProductConcreteExpanderInterface
{
    /**
     * @var \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Processor\Reader\ProductConcreteReaderInterface
     */
    protected $productConcreteReader;

    /**
     * @var \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceInterface
     */
    protected $productsRestApiResource;

    /**
     * @param \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Processor\Reader\ProductConcreteReaderInterface $productConcreteReader
     * @param \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Dependency\RestApiResource\ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceInterface $productsRestApiResource
     */
    public function __construct(
        ProductConcreteReaderInterface $productConcreteReader,
        ConfigurableBundlesProductsResourceRelationshipToProductsRestApiResourceInterface $productsRestApiResource
    ) {
        $this->productConcreteReader = $productConcreteReader;
        $this->productsRestApiResource = $productsRestApiResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $mappedProductConcreteIds = $this->getMappedProductConcreteIdsByProductListId($resources);
        $productConcreteIds = $this->mergeProductConcreteIds($mappedProductConcreteIds);

        $productConcreteRestResources = $this->productsRestApiResource
            ->getProductConcreteCollectionByIds($productConcreteIds, $restRequest);

        $mappedProductConcreteRestResources = $this->mapProductConcreteRestResourcesByProductId(
            $productConcreteRestResources,
            $mappedProductConcreteIds
        );

        $this->addProductConcreteRestResources($mappedProductConcreteRestResources, $resources);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return int[][]
     */
    protected function getMappedProductConcreteIdsByProductListId(array $resources): array
    {
        $mappedProductConcreteIds = [];

        foreach ($resources as $resource) {
            $configurableBundleTemplateSlotStorageTransfer = $resource->getPayload();
            if (!$configurableBundleTemplateSlotStorageTransfer instanceof ConfigurableBundleTemplateSlotStorageTransfer) {
                continue;
            }

            $idProductList = $configurableBundleTemplateSlotStorageTransfer->getIdProductList();

            if (!$idProductList) {
                continue;
            }

            $mappedProductConcreteIds[$idProductList] = $this->productConcreteReader->getProductConcreteIdsByProductListId($idProductList);
        }

        return $mappedProductConcreteIds;
    }

    /**
     * @param int[][] $mappedProductConcreteIds
     *
     * @return int[]
     */
    protected function mergeProductConcreteIds(array $mappedProductConcreteIds): array
    {
        $mergedProductConcreteIds = [];

        foreach ($mappedProductConcreteIds as $productConcreteIds) {
            $mergedProductConcreteIds = array_merge($mergedProductConcreteIds, $productConcreteIds);
        }

        return $mergedProductConcreteIds;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $productConcreteRestResources
     * @param int[][] $mappedProductConcreteIds
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    protected function mapProductConcreteRestResourcesByProductId(
        array $productConcreteRestResources,
        array $mappedProductConcreteIds
    ): array {
        $mappedProductConcreteRestResources = [];

        foreach ($mappedProductConcreteIds as $idProductList => $productConcreteIds) {
            foreach ($productConcreteRestResources as $productConcreteRestResource) {
                if (in_array($productConcreteRestResource->getId(), $productConcreteIds)) {
                    $mappedProductConcreteRestResources[$idProductList][] = $productConcreteRestResource;
                }
            }
        }

        return $mappedProductConcreteRestResources;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][] $mappedProductConcreteRestResources
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return void
     */
    protected function addProductConcreteRestResources(array $mappedProductConcreteRestResources, array $resources): void
    {
        foreach ($resources as $resource) {
            $configurableBundleTemplateSlotStorageTransfer = $resource->getPayload();
            if (!$configurableBundleTemplateSlotStorageTransfer instanceof ConfigurableBundleTemplateSlotStorageTransfer) {
                continue;
            }

            $productConcreteRestResources = $mappedProductConcreteRestResources[$configurableBundleTemplateSlotStorageTransfer->getIdProductList()] ?? [];

            foreach ($productConcreteRestResources as $productConcreteRestResource) {
                $resource->addRelationship($productConcreteRestResource);
            }
        }
    }
}
