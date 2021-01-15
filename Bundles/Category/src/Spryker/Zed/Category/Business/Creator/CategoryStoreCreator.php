<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Creator;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;

class CategoryStoreCreator implements CategoryStoreCreatorInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface
     */
    protected $categoryEntityManager;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface $categoryEntityManager
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CategoryEntityManagerInterface $categoryEntityManager
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryEntityManager = $categoryEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function createCategoryStoreRelations(CategoryTransfer $categoryTransfer): void
    {
        if (!$categoryTransfer->getStoreRelation()) {
            return;
        }

        $this->categoryEntityManager->createCategoryStoreRelationForStores(
            $categoryTransfer->getIdCategoryOrFail(),
            $this->filterOutStoreIdsMissingInParentCategoryStoreRelation(
                $categoryTransfer->getIdCategory(),
                $categoryTransfer->getStoreRelation()->getIdStores()
            )
        );
    }

    /**
     * @param int $idCategory
     * @param int[] $storeIds
     *
     * @return int[]
     */
    protected function filterOutStoreIdsMissingInParentCategoryStoreRelation(int $idCategory, array $storeIds): array
    {
        return array_filter($storeIds, function (int $idStore) use ($idCategory) {
            return $this->categoryRepository->isParentCategoryHasRelationToStore($idCategory, $idStore);
        });
    }
}
