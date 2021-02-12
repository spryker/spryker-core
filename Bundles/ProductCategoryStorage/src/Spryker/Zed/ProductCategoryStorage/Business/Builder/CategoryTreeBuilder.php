<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business\Builder;

use Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface;

class CategoryTreeBuilder implements CategoryTreeBuilderInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface
     */
    protected $productCategoryStorageRepository;

    /**
     * @param \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface $productCategoryStorageRepository
     */
    public function __construct(ProductCategoryStorageRepositoryInterface $productCategoryStorageRepository)
    {
        $this->productCategoryStorageRepository = $productCategoryStorageRepository;
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryNodeAggregationTransfer[][]
     */
    public function buildCategoryTree(): array
    {
        $categoryTree = [];
        $categoryNodeIds = $this->productCategoryStorageRepository->getAllCategoryNodeIds();

        $categoryNodeAggregationTransfers = $this->productCategoryStorageRepository->getAllCategoryNodeAggregationsOrderedByDescendant();
        $formattedCategoryNodeAggregationTransfers = $this->formatCategoryNodeAggregations($categoryNodeAggregationTransfers);

        foreach ($categoryNodeIds as $idCategoryNode) {
            $categoryTree = $this->buildCategoryTreeByIdCategoryNode(
                $categoryTree,
                $idCategoryNode,
                $formattedCategoryNodeAggregationTransfers[$idCategoryNode] ?? []
            );
        }

        return $categoryTree;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeAggregationTransfer[][] $categoryTree
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\CategoryNodeAggregationTransfer[] $categoryNodeAggregationTransfers
     *
     * @return \Generated\Shared\Transfer\CategoryNodeAggregationTransfer[][]
     */
    protected function buildCategoryTreeByIdCategoryNode(
        array $categoryTree,
        int $idCategoryNode,
        array $categoryNodeAggregationTransfers
    ): array {
        $categoryTree[$idCategoryNode] = [];

        foreach ($categoryNodeAggregationTransfers as $categoryNodeAggregationTransfer) {
            $categoryTree[$idCategoryNode][] = $categoryNodeAggregationTransfer;
        }

        return $categoryTree;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeAggregationTransfer[] $categoryNodeAggregationTransfers
     *
     * @return \Generated\Shared\Transfer\CategoryNodeAggregationTransfer[][]
     */
    protected function formatCategoryNodeAggregations(array $categoryNodeAggregationTransfers): array
    {
        $formattedCategoryNodeAggregationTransfers = [];

        foreach ($categoryNodeAggregationTransfers as $categoryNodeAggregationTransfer) {
            $idCategoryNodeDescendant = $categoryNodeAggregationTransfer->getIdCategoryNodeDescendant();

            $formattedCategoryNodeAggregationTransfers[$idCategoryNodeDescendant][] = $categoryNodeAggregationTransfer;
        }

        return $formattedCategoryNodeAggregationTransfers;
    }
}
