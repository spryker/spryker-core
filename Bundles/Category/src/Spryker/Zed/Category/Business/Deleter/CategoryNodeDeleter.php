<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Deleter;

use Generated\Shared\Transfer\CategoryNodeFilterTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Category\Business\Model\CategoryToucherInterface;
use Spryker\Zed\Category\Business\Model\CategoryTree\CategoryTreeInterface;
use Spryker\Zed\Category\Business\Publisher\CategoryNodePublisherInterface;
use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryNodeDeleter implements CategoryNodeDeleterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface
     */
    protected $categoryEntityManager;

    /**
     * @var \Spryker\Zed\Category\Business\Model\CategoryTree\CategoryTreeInterface
     */
    protected $categoryTree;

    /**
     * @var \Spryker\Zed\Category\Business\Deleter\CategoryClosureTableDeleterInterface
     */
    protected $categoryClosureTableDeleter;

    /**
     * @var \Spryker\Zed\Category\Business\Deleter\CategoryUrlDeleterInterface
     */
    protected $categoryUrlDeleter;

    /**
     * @var \Spryker\Zed\Category\Business\Model\CategoryToucherInterface
     */
    protected $categoryToucher;

    /**
     * @var \Spryker\Zed\Category\Business\Publisher\CategoryNodePublisherInterface
     */
    protected $categoryNodePublisher;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface $categoryEntityManager
     * @param \Spryker\Zed\Category\Business\Model\CategoryTree\CategoryTreeInterface $categoryTree
     * @param \Spryker\Zed\Category\Business\Deleter\CategoryClosureTableDeleterInterface $categoryClosureTableDeleter
     * @param \Spryker\Zed\Category\Business\Deleter\CategoryUrlDeleterInterface $categoryUrlDeleter
     * @param \Spryker\Zed\Category\Business\Model\CategoryToucherInterface $categoryToucher
     * @param \Spryker\Zed\Category\Business\Publisher\CategoryNodePublisherInterface $categoryNodePublisher
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CategoryEntityManagerInterface $categoryEntityManager,
        CategoryTreeInterface $categoryTree,
        CategoryClosureTableDeleterInterface $categoryClosureTableDeleter,
        CategoryUrlDeleterInterface $categoryUrlDeleter,
        CategoryToucherInterface $categoryToucher,
        CategoryNodePublisherInterface $categoryNodePublisher
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryEntityManager = $categoryEntityManager;
        $this->categoryTree = $categoryTree;
        $this->categoryClosureTableDeleter = $categoryClosureTableDeleter;
        $this->categoryUrlDeleter = $categoryUrlDeleter;
        $this->categoryToucher = $categoryToucher;
        $this->categoryNodePublisher = $categoryNodePublisher;
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategoryNodes(int $idCategory): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($idCategory) {
            $this->executeDeleteCategoryNodesTransaction($idCategory);
        });
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategoryExtraParentNodes(int $idCategory): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($idCategory) {
            $this->executeDeleteCategoryExtraParentNodesTransaction($idCategory);
        });
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    protected function executeDeleteCategoryNodesTransaction(int $idCategory): void
    {
        $categoryNodeFilterTransfer = (new CategoryNodeFilterTransfer())
            ->addIdCategory($idCategory);

        $nodeCollectionTransfer = $this->categoryRepository->getCategoryNodesByIdCategory($categoryNodeFilterTransfer);

        foreach ($nodeCollectionTransfer->getNodes() as $nodeTransfer) {
            $this->deleteNode($nodeTransfer);
        }
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    protected function executeDeleteCategoryExtraParentNodesTransaction(int $idCategory): void
    {
        $categoryNodeFilterTransfer = (new CategoryNodeFilterTransfer())
            ->addIdCategory($idCategory)
            ->setIsMain(false);

        $nodeCollectionTransfer = $this->categoryRepository->getCategoryNodesByIdCategory($categoryNodeFilterTransfer);

        foreach ($nodeCollectionTransfer->getNodes() as $nodeTransfer) {
            $this->deleteExtraParentNode($nodeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return void
     */
    protected function deleteNode(NodeTransfer $nodeTransfer): void
    {
        do {
            $childrenMoved = $this->categoryTree->moveSubTree(
                $nodeTransfer->getIdCategoryNode(),
                $nodeTransfer->getFkParentCategoryNode()
            );
        } while ($childrenMoved > 0);

        $this->categoryNodePublisher->triggerBulkCategoryNodePublishEventForUpdate($nodeTransfer->getIdCategoryNode());

        $this->categoryClosureTableDeleter->deleteCategoryClosureTable($nodeTransfer->getIdCategoryNode());
        $this->categoryEntityManager->deleteCategoryNode($nodeTransfer->getIdCategoryNode());

        $this->categoryToucher->touchCategoryNodeDeleted($nodeTransfer->getIdCategoryNode());
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return void
     */
    protected function deleteExtraParentNode(NodeTransfer $nodeTransfer): void
    {
        $this->categoryTree->moveSubTree($nodeTransfer->getIdCategoryNode(), $nodeTransfer->getFkParentCategoryNode());

        $this->categoryUrlDeleter->deleteCategoryUrlsForCategoryNode($nodeTransfer->getIdCategoryNode());
        $this->categoryClosureTableDeleter->deleteCategoryClosureTable($nodeTransfer->getIdCategoryNode());
        $this->categoryEntityManager->deleteCategoryNode($nodeTransfer->getIdCategoryNode());

        $this->categoryToucher->touchCategoryNodeDeleted($nodeTransfer->getIdCategoryNode());
    }
}
