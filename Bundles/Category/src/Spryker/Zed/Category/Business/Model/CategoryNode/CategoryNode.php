<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryNode;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Spryker\Zed\Category\Business\Exception\MissingCategoryNodeException;
use Spryker\Zed\Category\Business\Generator\TransferGeneratorInterface;
use Spryker\Zed\Category\Business\Model\CategoryToucherInterface;
use Spryker\Zed\Category\Business\Model\CategoryTree\CategoryTreeInterface;
use Spryker\Zed\Category\Business\Publisher\CategoryNodePublisherInterface;
use Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class CategoryNode implements CategoryNodeInterface, CategoryNodeDeleterInterface
{
    /**
     * @var \Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface
     */
    protected $closureTableWriter;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Category\Business\Generator\TransferGeneratorInterface
     */
    protected $transferGenerator;

    /**
     * @var \Spryker\Zed\Category\Business\Model\CategoryToucherInterface
     */
    protected $categoryToucher;

    /**
     * @var \Spryker\Zed\Category\Business\Model\CategoryTree\CategoryTreeInterface
     */
    protected $categoryTree;

    /**
     * @var \Spryker\Zed\Category\Business\Publisher\CategoryNodePublisherInterface
     */
    protected $categoryNodePublisher;

    /**
     * @param \Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface $closureTableWriter
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Business\Generator\TransferGeneratorInterface $transferGenerator
     * @param \Spryker\Zed\Category\Business\Model\CategoryToucherInterface $categoryToucher
     * @param \Spryker\Zed\Category\Business\Model\CategoryTree\CategoryTreeInterface $categoryTree
     * @param \Spryker\Zed\Category\Business\Publisher\CategoryNodePublisherInterface $categoryNodePublisher
     */
    public function __construct(
        ClosureTableWriterInterface $closureTableWriter,
        CategoryQueryContainerInterface $queryContainer,
        TransferGeneratorInterface $transferGenerator,
        CategoryToucherInterface $categoryToucher,
        CategoryTreeInterface $categoryTree,
        CategoryNodePublisherInterface $categoryNodePublisher
    ) {
        $this->closureTableWriter = $closureTableWriter;
        $this->queryContainer = $queryContainer;
        $this->transferGenerator = $transferGenerator;
        $this->categoryToucher = $categoryToucher;
        $this->categoryTree = $categoryTree;
        $this->categoryNodePublisher = $categoryNodePublisher;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer)
    {
        $categoryNodeEntity = new SpyCategoryNode();
        $categoryNodeEntity = $this->setUpCategoryNodeEntity($categoryTransfer, $categoryNodeEntity);
        $categoryNodeEntity->save();

        $categoryNodeTransfer = $this->transferGenerator->convertCategoryNode($categoryNodeEntity);
        $categoryTransfer->setCategoryNode($categoryNodeTransfer);

        $this->closureTableWriter->create($categoryNodeTransfer);
        $this->touchCategoryNode($categoryTransfer, $categoryNodeTransfer);
        $this->categoryNodePublisher->triggerBulkCategoryNodePublishEventForCreate($categoryNodeTransfer->getIdCategoryNode());
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     *
     * @return void
     */
    protected function touchCategoryNode(CategoryTransfer $categoryTransfer, NodeTransfer $categoryNodeTransfer)
    {
        $idCategoryNode = $categoryNodeTransfer->requireIdCategoryNode()->getIdCategoryNode();

        if ($categoryTransfer->getIsActive()) {
            $this->categoryToucher->touchCategoryNodeActiveRecursively($idCategoryNode);

            return;
        }

        $this->categoryToucher->touchCategoryNodeDeletedRecursively($idCategoryNode);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        $categoryNodeTransfer = $categoryTransfer->requireCategoryNode()->getCategoryNode();
        $idCategoryNode = $categoryNodeTransfer->requireIdCategoryNode()->getIdCategoryNode();
        $categoryNodeEntity = $this->getCategoryNodeEntity($idCategoryNode);

        $idFormerParentCategoryNode = $this->findPossibleFormerParentCategoryNodeId(
            $categoryNodeEntity,
            $categoryTransfer
        );

        $categoryNodeEntity = $this->setUpCategoryNodeEntity($categoryTransfer, $categoryNodeEntity);
        $categoryNodeEntity->save();

        $categoryNodeTransfer = $this->transferGenerator->convertCategoryNode($categoryNodeEntity);
        $categoryTransfer->setCategoryNode($categoryNodeTransfer);

        $this->closureTableWriter->moveNode($categoryNodeTransfer);

        $this->touchCategoryNode($categoryTransfer, $categoryNodeTransfer);
        $this->touchPossibleFormerParentCategoryNode($idFormerParentCategoryNode);
        $this->categoryNodePublisher->triggerBulkCategoryNodePublishEventForUpdate($categoryNodeTransfer->getIdCategoryNode());
    }

    /**
     * @param int $idCategoryNode
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryNodeException
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode
     */
    protected function getCategoryNodeEntity($idCategoryNode)
    {
        $categoryNodeEntity = $this
            ->queryContainer
            ->queryCategoryNodeByNodeId($idCategoryNode)
            ->findOne();

        if (!$categoryNodeEntity) {
            throw new MissingCategoryNodeException(sprintf(
                'Could not find category node for ID "%s"',
                $idCategoryNode
            ));
        }

        return $categoryNodeEntity;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNodeEntity
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return int|null
     */
    protected function findPossibleFormerParentCategoryNodeId(
        SpyCategoryNode $categoryNodeEntity,
        CategoryTransfer $categoryTransfer
    ) {
        $parentCategoryNodeTransfer = $categoryTransfer->requireParentCategoryNode()->getParentCategoryNode();
        $idFormerParentCategoryNode = $categoryNodeEntity->getFkParentCategoryNode();

        if ($parentCategoryNodeTransfer->getIdCategoryNode() !== $idFormerParentCategoryNode) {
            return $idFormerParentCategoryNode;
        }

        return null;
    }

    /**
     * @param int|null $idCategoryNode
     *
     * @return void
     */
    protected function touchPossibleFormerParentCategoryNode($idCategoryNode)
    {
        if (!$idCategoryNode) {
            return;
        }

        $this->categoryToucher->touchFormerParentCategoryNodeActiveRecursively($idCategoryNode);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNodeEntity
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode
     */
    private function setUpCategoryNodeEntity(CategoryTransfer $categoryTransfer, SpyCategoryNode $categoryNodeEntity)
    {
        $categoryNodeTransfer = $categoryTransfer->requireCategoryNode()->getCategoryNode();
        $parentCategoryNodeTransfer = $categoryTransfer->requireParentCategoryNode()->getParentCategoryNode();

        $categoryNodeEntity->fromArray($categoryNodeTransfer->toArray());
        $categoryNodeEntity->setIsMain(true);
        $categoryNodeEntity->setFkCategory($categoryTransfer->requireIdCategory()->getIdCategory());
        $categoryNodeEntity->setFkParentCategoryNode($parentCategoryNodeTransfer->getIdCategoryNode());

        return $categoryNodeEntity;
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory)
    {
        $categoryNodeCollection = $this
            ->queryContainer
            ->queryMainNodesByCategoryId($idCategory)
            ->find();

        foreach ($categoryNodeCollection as $categoryNodeEntity) {
            $this->deleteNode($categoryNodeEntity);
        }
    }

    /**
     * @param int $idCategoryNode
     * @param int $idChildrenDestinationNode
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryNodeException
     *
     * @return void
     */
    public function deleteNodeById($idCategoryNode, $idChildrenDestinationNode)
    {
        $categoryNodeEntity = $this->queryContainer
            ->queryNodeById($idCategoryNode)
            ->findOne();

        if (!$categoryNodeEntity) {
            throw new MissingCategoryNodeException();
        }

        $this->deleteNode($categoryNodeEntity, $idChildrenDestinationNode);
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNodeEntity
     * @param int|null $idChildrenDestinationNode
     *
     * @return void
     */
    protected function deleteNode(SpyCategoryNode $categoryNodeEntity, $idChildrenDestinationNode = null)
    {
        $idChildrenDestinationNode = $idChildrenDestinationNode ?: $categoryNodeEntity->getFkParentCategoryNode();

        do {
            $childrenMoved = $this->categoryTree
                ->moveSubTree(
                    $categoryNodeEntity->getIdCategoryNode(),
                    $idChildrenDestinationNode
                );
        } while ($childrenMoved > 0);

        $this->categoryToucher->touchCategoryNodeDeleted($categoryNodeEntity->getIdCategoryNode());
        $this->categoryNodePublisher->triggerBulkCategoryNodePublishEventForUpdate($categoryNodeEntity->getIdCategoryNode());

        $this->closureTableWriter->delete($categoryNodeEntity->getIdCategoryNode());

        $categoryNodeEntity->delete();
    }
}
