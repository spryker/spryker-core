<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business\Reader;

use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\ProductCategoryStorageTransfer;
use Generated\Shared\Transfer\ProductCategoryTransfer;
use Spryker\Zed\ProductCategoryStorage\Business\Builder\CategoryTreeBuilderInterface;

class ProductCategoryStorageReader implements ProductCategoryStorageReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Business\Builder\CategoryTreeBuilderInterface
     */
    protected $categoryTreeBuilder;

    /**
     * @var \Generated\Shared\Transfer\CategoryNodeAggregationTransfer[][]
     */
    protected static $categoryTree;

    /**
     * @param \Spryker\Zed\ProductCategoryStorage\Business\Builder\CategoryTreeBuilderInterface $categoryTreeBuilder
     */
    public function __construct(CategoryTreeBuilderInterface $categoryTreeBuilder)
    {
        $this->categoryTreeBuilder = $categoryTreeBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryTransfer[] $productCategoryTransfers
     * @param string $storeName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductCategoryStorageTransfer[]
     */
    public function getProductCategoryStoragesFromCategoryTree(
        array $productCategoryTransfers,
        string $storeName,
        string $localeName
    ): array {
        $productCategoryStorageTransfers = [];

        foreach ($productCategoryTransfers as $productCategoryTransfer) {
            $productCategoryStorageTransfers[] = $this->generateProductCategoryStorageTransfers($productCategoryTransfer, $storeName, $localeName);
        }

        return $productCategoryStorageTransfers ? array_merge(...$productCategoryStorageTransfers) : [];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryTransfer $productCategoryTransfer
     * @param string $storeName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductCategoryStorageTransfer[]
     */
    protected function generateProductCategoryStorageTransfers(
        ProductCategoryTransfer $productCategoryTransfer,
        string $storeName,
        string $localeName
    ): array {
        $productCategoryTransfers = [];

        foreach ($productCategoryTransfer->getCategoryOrFail()->getNodeCollectionOrFail()->getNodes() as $nodeTransfer) {
            $categoryNodeAggregationTransfers = $this->extractCategoryNodeAggregationsFromCategoryTree(
                $nodeTransfer,
                $storeName,
                $localeName
            );

            $productCategoryTransfers[] = $this->buildProductCategoryStorageTransfers($categoryNodeAggregationTransfers);
        }

        return $productCategoryTransfers ? array_merge(...$productCategoryTransfers) : [];
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param string $storeName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeAggregationTransfer[]
     */
    protected function extractCategoryNodeAggregationsFromCategoryTree(
        NodeTransfer $nodeTransfer,
        string $storeName,
        string $localeName
    ): array {
        $categoryNodeAggregations = [];
        $categoryNodeAggregationTransfers = $this->getCategoryNodeAggregationsFromCategoryTree($nodeTransfer);

        foreach ($categoryNodeAggregationTransfers as $categoryNodeAggregationTransfer) {
            if (
                $categoryNodeAggregationTransfer->getStore() === $storeName
                && $categoryNodeAggregationTransfer->getLocale() === $localeName
            ) {
                $categoryNodeAggregations[] = $categoryNodeAggregationTransfer;
            }
        }

        return $categoryNodeAggregations;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryNodeAggregationTransfer[]
     */
    protected function getCategoryNodeAggregationsFromCategoryTree(NodeTransfer $nodeTransfer): array
    {
        if (static::$categoryTree === null) {
            static::$categoryTree = $this->categoryTreeBuilder->buildCategoryTree();
        }

        return static::$categoryTree[$nodeTransfer->getIdCategoryNodeOrFail()] ?? [];
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeAggregationTransfer[] $categoryNodeAggregationTransfers
     *
     * @return \Generated\Shared\Transfer\ProductCategoryStorageTransfer[]
     */
    protected function buildProductCategoryStorageTransfers(array $categoryNodeAggregationTransfers): array
    {
        $productCategoryTransfers = [];

        foreach ($categoryNodeAggregationTransfers as $categoryNodeAggregationTransfer) {
            $productCategoryTransfers[] = (new ProductCategoryStorageTransfer())
                ->fromArray($categoryNodeAggregationTransfer->toArray(), true)
                ->setCategoryNodeId((int)$categoryNodeAggregationTransfer->getIdCategoryNode())
                ->setCategoryId((int)$categoryNodeAggregationTransfer->getIdCategory());
        }

        return $productCategoryTransfers;
    }
}
