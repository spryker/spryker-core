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
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $indexedProductConcreteIdsToProductConcreteSkusMap = $this->getProductConcreteIdsToProductConcreteSkusMapIndexedByIdProductList($resources);
        $productConcreteIds = $this->extractProductConcreteIds($indexedProductConcreteIdsToProductConcreteSkusMap);

        $productConcreteRestResources = $this->productsRestApiResource
            ->getProductConcreteCollectionByIds($productConcreteIds, $restRequest);

        $groupedProductConcreteRestResources = $this->getProductConcreteRestResourcesGroupedByIdProductList(
            $productConcreteRestResources,
            $indexedProductConcreteIdsToProductConcreteSkusMap,
        );

        $this->addProductConcreteRestResources($groupedProductConcreteRestResources, $resources);
    }

    /**
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     *
     * @return array<int, array<int, string>>
     */
    protected function getProductConcreteIdsToProductConcreteSkusMapIndexedByIdProductList(array $resources): array
    {
        $indexedProductConcreteIdsToProductConcreteSkusMap = [];

        foreach ($resources as $resource) {
            $configurableBundleTemplateSlotStorageTransfer = $resource->getPayload();
            if (!$configurableBundleTemplateSlotStorageTransfer instanceof ConfigurableBundleTemplateSlotStorageTransfer) {
                continue;
            }

            $idProductList = $configurableBundleTemplateSlotStorageTransfer->getIdProductList();

            if (!$idProductList) {
                continue;
            }

            $indexedProductConcreteIdsToProductConcreteSkusMap[$idProductList] = $this->productConcreteReader->getProductConcreteSkusIndexedByIdProductConcrete($idProductList);
        }

        return $indexedProductConcreteIdsToProductConcreteSkusMap;
    }

    /**
     * @param array<int, array<int, string>> $indexedProductConcreteIdsToProductConcreteSkusMap
     *
     * @return array<int>
     */
    protected function extractProductConcreteIds(array $indexedProductConcreteIdsToProductConcreteSkusMap): array
    {
        $productConcreteIds = [];
        foreach ($indexedProductConcreteIdsToProductConcreteSkusMap as $productConcreteIdsToProductConcreteSkusMap) {
            $productConcreteIds[] = array_keys($productConcreteIdsToProductConcreteSkusMap);
        }

        return array_merge(...$productConcreteIds);
    }

    /**
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $productConcreteRestResources
     * @param array<int, array<int, string>> $indexedProductConcreteIdsToProductConcreteSkusMap
     *
     * @return array<int, array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>>
     */
    protected function getProductConcreteRestResourcesGroupedByIdProductList(
        array $productConcreteRestResources,
        array $indexedProductConcreteIdsToProductConcreteSkusMap
    ): array {
        $mappedProductConcreteRestResources = [];

        foreach ($indexedProductConcreteIdsToProductConcreteSkusMap as $idProductList => $productConcreteSkus) {
            foreach ($productConcreteRestResources as $productConcreteRestResource) {
                if (in_array($productConcreteRestResource->getId(), $productConcreteSkus, true)) {
                    $mappedProductConcreteRestResources[$idProductList][] = $productConcreteRestResource;
                }
            }
        }

        return $mappedProductConcreteRestResources;
    }

    /**
     * @param array<int, array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>> $groupedProductConcreteRestResources
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     *
     * @return void
     */
    protected function addProductConcreteRestResources(array $groupedProductConcreteRestResources, array $resources): void
    {
        foreach ($resources as $resource) {
            $configurableBundleTemplateSlotStorageTransfer = $resource->getPayload();
            if (!$configurableBundleTemplateSlotStorageTransfer instanceof ConfigurableBundleTemplateSlotStorageTransfer) {
                continue;
            }

            $productConcreteRestResources = $groupedProductConcreteRestResources[$configurableBundleTemplateSlotStorageTransfer->getIdProductList()] ?? [];

            foreach ($productConcreteRestResources as $productConcreteRestResource) {
                $resource->addRelationship($productConcreteRestResource);
            }
        }
    }
}
