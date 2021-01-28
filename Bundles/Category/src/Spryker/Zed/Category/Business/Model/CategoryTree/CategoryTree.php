<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryTree;

use ArrayObject;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Spryker\Zed\Category\Business\CategoryFacadeInterface;
use Spryker\Zed\Category\Business\Model\CategoryToucherInterface;
use Spryker\Zed\Category\Business\Publisher\CategoryNodePublisherInterface;
use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryTree implements CategoryTreeInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Category\Business\CategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\Category\Business\Deleter\CategoryNodeDeleterInterface
     */
    protected $categoryNodeDeleter;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface
     */
    protected $categoryEntityManager;

    /**
     * @var \Spryker\Zed\Category\Business\Publisher\CategoryNodePublisherInterface
     */
    protected $categoryNodePublisher;

    /**
     * @var \Spryker\Zed\Category\Business\Model\CategoryToucherInterface
     */
    protected $categoryToucher;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface $categoryEntityManager
     * @param \Spryker\Zed\Category\Business\CategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\Category\Business\Publisher\CategoryNodePublisherInterface $categoryNodePublisher
     * @param \Spryker\Zed\Category\Business\Model\CategoryToucherInterface $categoryToucher
     */
    public function __construct(
        CategoryQueryContainerInterface $queryContainer,
        CategoryEntityManagerInterface $categoryEntityManager,
        CategoryFacadeInterface $categoryFacade,
        CategoryNodePublisherInterface $categoryNodePublisher,
        CategoryToucherInterface $categoryToucher
    ) {
        $this->queryContainer = $queryContainer;
        $this->categoryEntityManager = $categoryEntityManager;
        $this->categoryFacade = $categoryFacade;
        $this->categoryNodePublisher = $categoryNodePublisher;
        $this->categoryToucher = $categoryToucher;
    }

    /**
     * @param int $idSourceCategoryNode
     * @param int $idDestinationCategoryNode
     *
     * @return int
     */
    public function moveSubTree($idSourceCategoryNode, $idDestinationCategoryNode)
    {
        $firstLevelChildNodeCollection = $this
            ->queryContainer
            ->queryFirstLevelChildren($idSourceCategoryNode)
            ->find();

        /** @var \Orm\Zed\Category\Persistence\SpyCategoryNode $destinationCategoryNodeEntity */
        $destinationCategoryNodeEntity = $this->queryContainer
            ->queryNodeById($idDestinationCategoryNode)
            ->findOne();

        $destinationChildrenIds = $this->queryContainer
            ->queryFirstLevelChildren($idDestinationCategoryNode)
            ->select([SpyCategoryNodeTableMap::COL_FK_CATEGORY])
            ->setFormatter(new SimpleArrayFormatter())
            ->find()
            ->toArray();

        foreach ($firstLevelChildNodeCollection as $childNodeEntity) {
            if ($childNodeEntity->getFkCategory() === $destinationCategoryNodeEntity->getFkCategory()) {
                $this->deleteNodeById($childNodeEntity->getIdCategoryNode(), $idDestinationCategoryNode);

                continue;
            }

            if (in_array($childNodeEntity->getFkCategory(), $destinationChildrenIds)) {
                $this->deleteNodeById($childNodeEntity->getIdCategoryNode(), $idDestinationCategoryNode);

                continue;
            }

            $categoryTransfer = $this->categoryFacade->findCategoryById($childNodeEntity->getFkCategory());

            if (!$categoryTransfer) {
                continue;
            }

            if ($childNodeEntity->getIsMain()) {
                $this->moveMainCategoryNodeSubTree($categoryTransfer, $idDestinationCategoryNode);

                continue;
            }

            $this->moveExtraParentCategoryNodeSubTree(
                $categoryTransfer,
                $idDestinationCategoryNode,
                $idSourceCategoryNode
            );
        }

        return count($firstLevelChildNodeCollection);
    }

    /**
     * @param int $idCategoryNode
     * @param int $idChildrenDestinationNode
     *
     * @return void
     */
    protected function deleteNodeById(int $idCategoryNode, int $idChildrenDestinationNode): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($idCategoryNode, $idChildrenDestinationNode) {
            $this->executeDeleteNodeByIdTransaction($idCategoryNode, $idChildrenDestinationNode);
        });
    }

    /**
     * @param int $idCategoryNode
     * @param int $idChildrenDestinationNode
     *
     * @return void
     */
    protected function executeDeleteNodeByIdTransaction(int $idCategoryNode, int $idChildrenDestinationNode): void
    {
        $nodeTransfer = (new NodeTransfer())->setIdCategoryNode($idCategoryNode);

        $this->deleteNode($nodeTransfer, $idChildrenDestinationNode);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param int|null $idDestinationCategoryNode
     *
     * @return void
     */
    protected function deleteNode(NodeTransfer $nodeTransfer, ?int $idDestinationCategoryNode = null): void
    {
        do {
            $childrenMoved = $this->moveSubTree(
                $nodeTransfer->getIdCategoryNodeOrFail(),
                $idDestinationCategoryNode ?? $nodeTransfer->getFkParentCategoryNodeOrFail()
            );
        } while ($childrenMoved > 0);

        $this->categoryNodePublisher->triggerBulkCategoryNodePublishEventForUpdate($nodeTransfer->getIdCategoryNodeOrFail());

        $this->categoryEntityManager->deleteCategoryClosureTable($nodeTransfer->getIdCategoryNodeOrFail());
        $this->categoryEntityManager->deleteCategoryNode($nodeTransfer->getIdCategoryNodeOrFail());

        $this->categoryToucher->touchCategoryNodeDeleted($nodeTransfer->getIdCategoryNodeOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param int $idDestinationCategoryNode
     *
     * @return void
     */
    protected function moveMainCategoryNodeSubTree(CategoryTransfer $categoryTransfer, $idDestinationCategoryNode)
    {
        $categoryNodeTransfer = $categoryTransfer->getCategoryNodeOrFail();
        $categoryNodeTransfer->setFkParentCategoryNode($idDestinationCategoryNode);
        $categoryTransfer->setCategoryNode($categoryNodeTransfer);

        $categoryParentNodeTransfer = $categoryTransfer->getParentCategoryNodeOrFail();
        $categoryParentNodeTransfer->setIdCategoryNode($idDestinationCategoryNode);
        $categoryTransfer->setParentCategoryNode($categoryParentNodeTransfer);

        $this->categoryFacade->update($categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param int $idDestinationCategoryNode
     * @param int $idSourceCategoryNode
     *
     * @return void
     */
    protected function moveExtraParentCategoryNodeSubTree(
        CategoryTransfer $categoryTransfer,
        $idDestinationCategoryNode,
        $idSourceCategoryNode
    ) {
        $extraParentNodeTransferCollection = $categoryTransfer->getExtraParents();
        $updatedParentNodeTransferCollection = new ArrayObject();

        foreach ($extraParentNodeTransferCollection as $extraParentNodeTransfer) {
            if ($extraParentNodeTransfer->requireIdCategoryNode()->getIdCategoryNode() === $idSourceCategoryNode) {
                $extraParentNodeTransfer = new NodeTransfer();
                $extraParentNodeTransfer->setIdCategoryNode($idDestinationCategoryNode);
            }

            $updatedParentNodeTransferCollection->append($extraParentNodeTransfer);
        }

        $categoryTransfer->setExtraParents($updatedParentNodeTransferCollection);

        $this->categoryFacade->update($categoryTransfer);
    }
}
