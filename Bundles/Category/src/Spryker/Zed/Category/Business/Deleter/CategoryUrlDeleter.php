<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Deleter;

use Generated\Shared\Transfer\CategoryNodeFilterTransfer;
use Generated\Shared\Transfer\CategoryNodeUrlFilterTransfer;
use Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryUrlDeleter implements CategoryUrlDeleterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface
     */
    protected $urlFacade;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface $urlFacade
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CategoryToUrlInterface $urlFacade
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategoryUrlsForCategory(int $idCategory): void
    {
        $categoryNodeUrlFilterTransfer = (new CategoryNodeUrlFilterTransfer())
            ->setCategoryNodeIds($this->getCategoryNodeIdsForCategory($idCategory));

        $this->getTransactionHandler()->handleTransaction(function () use ($categoryNodeUrlFilterTransfer) {
            $this->executeDeleteUrlsByCategoryNodeUrlFilterTransaction($categoryNodeUrlFilterTransfer);
        });
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function deleteCategoryUrlsForCategoryNode(int $idCategoryNode): void
    {
        $categoryNodeUrlFilterTransfer = (new CategoryNodeUrlFilterTransfer())
            ->addIdCategoryNode($idCategoryNode);

        $this->getTransactionHandler()->handleTransaction(function () use ($categoryNodeUrlFilterTransfer) {
            $this->executeDeleteUrlsByCategoryNodeUrlFilterTransaction($categoryNodeUrlFilterTransfer);
        });
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function deleteCategoryUrlsForCategoryNodes(array $categoryNodeIds): void
    {
        $categoryNodeUrlFilterTransfer = (new CategoryNodeUrlFilterTransfer())
            ->setCategoryNodeIds($categoryNodeIds);

        $this->getTransactionHandler()->handleTransaction(function () use ($categoryNodeUrlFilterTransfer) {
            $this->executeDeleteUrlsByCategoryNodeUrlFilterTransaction($categoryNodeUrlFilterTransfer);
        });
    }

    /**
     * @param int $idCategory
     *
     * @return int[]
     */
    protected function getCategoryNodeIdsForCategory(int $idCategory): array
    {
        $categoryNodeIds = [];
        $categoryNodeFilterTransfer = (new CategoryNodeFilterTransfer())
            ->addIdCategory($idCategory);

        $nodeCollectionTransfer = $this->categoryRepository->getCategoryNodesByCriteria($categoryNodeFilterTransfer);

        foreach ($nodeCollectionTransfer->getNodes() as $nodeTransfer) {
            $categoryNodeIds[] = $nodeTransfer->getIdCategoryNodeOrFail();
        }

        return $categoryNodeIds;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeUrlFilterTransfer $categoryNodeUrlFilterTransfer
     *
     * @return void
     */
    protected function executeDeleteUrlsByCategoryNodeUrlFilterTransaction(
        CategoryNodeUrlFilterTransfer $categoryNodeUrlFilterTransfer
    ): void {
        $urlTransfers = $this->categoryRepository->getCategoryNodeUrls($categoryNodeUrlFilterTransfer);

        foreach ($urlTransfers as $urlTransfer) {
            $this->urlFacade->deleteUrl($urlTransfer);
        }
    }
}
