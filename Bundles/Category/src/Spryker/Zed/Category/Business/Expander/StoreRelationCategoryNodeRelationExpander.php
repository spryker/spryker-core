<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Expander;

use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;

class StoreRelationCategoryNodeRelationExpander implements CategoryNodeRelationExpanderInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected CategoryRepositoryInterface $categoryRepository;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $categoryNodeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    public function expandNodeCollectionWithRelations(
        NodeCollectionTransfer $categoryNodeCollectionTransfer
    ): NodeCollectionTransfer {
        $categoryIds = $this->extractCategoryIdsFromCategoryNodeCollection($categoryNodeCollectionTransfer);
        if ($categoryIds === []) {
            return $categoryNodeCollectionTransfer;
        }

        $categoryStoreRelationTransfers = $this->categoryRepository->getCategoryStoreRelationsByCategoryIds($categoryIds);
        $indexedCategoryStoreRelationTransfers = $this->getCategoryStoreRelationsIndexedByIdCategory($categoryStoreRelationTransfers);

        foreach ($categoryNodeCollectionTransfer->getNodes() as $categoryNodeTransfer) {
            $categoryTransfer = $categoryNodeTransfer->getCategoryOrFail();
            if (!isset($indexedCategoryStoreRelationTransfers[$categoryTransfer->getIdCategoryOrFail()])) {
                $categoryTransfer->setStoreRelation((new StoreRelationTransfer())->setIdEntity($categoryTransfer->getIdCategoryOrFail()));

                continue;
            }

            $categoryTransfer->setStoreRelation(
                $indexedCategoryStoreRelationTransfers[$categoryTransfer->getIdCategoryOrFail()],
            );
        }

        return $categoryNodeCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $categoryNodeCollectionTransfer
     *
     * @return array<int>
     */
    protected function extractCategoryIdsFromCategoryNodeCollection(NodeCollectionTransfer $categoryNodeCollectionTransfer): array
    {
        $categoryIds = [];
        foreach ($categoryNodeCollectionTransfer->getNodes() as $categoryNodeTransfer) {
            $categoryIds[] = $categoryNodeTransfer->getFkCategoryOrFail();
        }

        return $categoryIds;
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreRelationTransfer> $categoryStoreRelationTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\StoreRelationTransfer>
     */
    protected function getCategoryStoreRelationsIndexedByIdCategory(array $categoryStoreRelationTransfers): array
    {
        $indexedCategoryStoreRelationTransfers = [];
        foreach ($categoryStoreRelationTransfers as $categoryStoreRelationTransfer) {
            $indexedCategoryStoreRelationTransfers[$categoryStoreRelationTransfer->getIdEntityOrFail()] = $categoryStoreRelationTransfer;
        }

        return $indexedCategoryStoreRelationTransfers;
    }
}
