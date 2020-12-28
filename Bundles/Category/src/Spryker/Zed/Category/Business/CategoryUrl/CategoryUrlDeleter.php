<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\CategoryUrl;

use Generated\Shared\Transfer\CategoryNodeUrlFilterTransfer;
use Generated\Shared\Transfer\NodeTransfer;
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
        $this->getTransactionHandler()->handleTransaction(function () use ($idCategory) {
            $this->executeDeleteCategoryUrlsForCategoryTransaction($idCategory);
        });
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function deleteCategoryUrlsForCategoryNode(int $idCategoryNode): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($idCategoryNode) {
            $this->executeDeleteCategoryUrlsForCategoryNodeTransaction($idCategoryNode);
        });
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function executeDeleteCategoryUrlsForCategoryTransaction(int $idCategory): void
    {
        $categoryNodeUrlFilterTransfer = (new CategoryNodeUrlFilterTransfer())
            ->setCategoryNodeIds($this->getCategoryNodeIdsForCategory($idCategory));

        $this->deleteUrlsByCategoryNodeUrlFilter($categoryNodeUrlFilterTransfer);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    protected function executeDeleteCategoryUrlsForCategoryNodeTransaction(int $idCategoryNode): void
    {
        $categoryNodeUrlFilterTransfer = (new CategoryNodeUrlFilterTransfer())
            ->addIdCategoryNode($idCategoryNode);

        $this->deleteUrlsByCategoryNodeUrlFilter($categoryNodeUrlFilterTransfer);
    }

    /**
     * @param int $idCategory
     *
     * @return int[]
     */
    protected function getCategoryNodeIdsForCategory(int $idCategory): array
    {
        $nodeCollectionTransfer = $this->categoryRepository->getCategoryNodesByIdCategory($idCategory);

        return array_map(function (NodeTransfer $nodeTransfer): int {
            return $nodeTransfer->getIdCategoryNode();
        }, $nodeCollectionTransfer->getNodes()->getArrayCopy());
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeUrlFilterTransfer $categoryNodeUrlFilterTransfer
     *
     * @return void
     */
    protected function deleteUrlsByCategoryNodeUrlFilter(CategoryNodeUrlFilterTransfer $categoryNodeUrlFilterTransfer): void
    {
        $urlTransfers = $this->categoryRepository->getCategoryNodeUrls($categoryNodeUrlFilterTransfer);
        foreach ($urlTransfers as $urlTransfer) {
            $this->urlFacade->deleteUrl($urlTransfer);
        }
    }
}
