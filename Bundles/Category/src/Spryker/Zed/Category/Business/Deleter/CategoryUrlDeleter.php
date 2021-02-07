<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Deleter;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\CategoryNodeUrlCriteriaTransfer;
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
        $categoryNodeUrlCriteriaTransfer = (new CategoryNodeUrlCriteriaTransfer())
            ->setCategoryNodeIds($this->getCategoryNodeIdsForCategory($idCategory));

        $this->getTransactionHandler()->handleTransaction(function () use ($categoryNodeUrlCriteriaTransfer) {
            $this->executeDeleteUrlsByCategoryNodeUrlTransaction($categoryNodeUrlCriteriaTransfer);
        });
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function deleteCategoryUrlsForCategoryNode(int $idCategoryNode): void
    {
        $categoryNodeUrlCriteriaTransfer = (new CategoryNodeUrlCriteriaTransfer())
            ->addIdCategoryNode($idCategoryNode);

        $this->getTransactionHandler()->handleTransaction(function () use ($categoryNodeUrlCriteriaTransfer) {
            $this->executeDeleteUrlsByCategoryNodeUrlTransaction($categoryNodeUrlCriteriaTransfer);
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
        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->addIdCategory($idCategory);

        $nodeCollectionTransfer = $this->categoryRepository->getCategoryNodes($categoryNodeCriteriaTransfer);

        foreach ($nodeCollectionTransfer->getNodes() as $nodeTransfer) {
            $categoryNodeIds[] = $nodeTransfer->getIdCategoryNodeOrFail();
        }

        return $categoryNodeIds;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeUrlCriteriaTransfer $categoryNodeUrlCriteriaTransfer
     *
     * @return void
     */
    protected function executeDeleteUrlsByCategoryNodeUrlTransaction(
        CategoryNodeUrlCriteriaTransfer $categoryNodeUrlCriteriaTransfer
    ): void {
        $urlTransfers = $this->categoryRepository->getCategoryNodeUrls($categoryNodeUrlCriteriaTransfer);

        foreach ($urlTransfers as $urlTransfer) {
            $this->urlFacade->deleteUrl($urlTransfer);
        }
    }
}
