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
use Spryker\Zed\Category\Business\Model\CategoryToucherInterface;
use Spryker\Zed\Category\Business\Model\CategoryTree\CategoryTreeInterface;
use Spryker\Zed\Category\Business\TransferGeneratorInterface;
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
     * @var \Spryker\Zed\Category\Business\TransferGeneratorInterface
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
     * @param \Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface $closureTableWriter
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Business\TransferGeneratorInterface $transferGenerator
     * @param \Spryker\Zed\Category\Business\Model\CategoryToucherInterface $categoryToucher
     * @param \Spryker\Zed\Category\Business\Model\CategoryTree\CategoryTreeInterface $categoryTree
     */
    public function __construct(
        ClosureTableWriterInterface $closureTableWriter,
        CategoryQueryContainerInterface $queryContainer,
        TransferGeneratorInterface $transferGenerator,
        CategoryToucherInterface $categoryToucher,
        CategoryTreeInterface $categoryTree
    ) {
        $this->closureTableWriter = $closureTableWriter;
        $this->queryContainer = $queryContainer;
        $this->transferGenerator = $transferGenerator;
        $this->categoryToucher = $categoryToucher;
        $this->categoryTree = $categoryTree;
    }

    /**
     * @deprecated Use \Spryker\Zed\Category\Business\Model\CategoryReaderInterface::findCategoryById() instead.
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryNodeException
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function read($idCategory, CategoryTransfer $categoryTransfer)
    {
        $categoryNodeEntity = $this
            ->queryContainer
            ->queryMainNodesByCategoryId($idCategory)
            ->findOne();

        if (!$categoryNodeEntity) {
            throw new MissingCategoryNodeException(sprintf(
                'Could not find category node for category with ID "%s"',
                $idCategory
            ));
        }

        $categoryNodeTransfer = new NodeTransfer();
        $categoryNodeTransfer->fromArray($categoryNodeEntity->toArray(), true);
        $categoryTransfer->setCategoryNode($categoryNodeTransfer);

        $parentCategoryNodeEntity = $categoryNodeEntity->getParentCategoryNode();
        $parentCategoryNodeTransfer = new NodeTransfer();
        if ($parentCategoryNodeEntity !== null) {
            $parentCategoryNodeTransfer->fromArray($parentCategoryNodeEntity->toArray(), true);
        }
        $categoryTransfer->setParentCategoryNode($parentCategoryNodeTransfer);

        return $categoryTransfer;
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
        $this->closureTableWriter->delete($categoryNodeEntity->getIdCategoryNode());

        $categoryNodeEntity->delete();
    }

    /**
     * @deprecated You can directly use ::categoryTree to manipulate with subTree
     *
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $sourceNodeEntity
     *
     * @return void
     */
    protected function moveSubTreeToParent(SpyCategoryNode $sourceNodeEntity)
    {
        $this->categoryTree->moveSubTree(
            $sourceNodeEntity->getIdCategoryNode(),
            $sourceNodeEntity->getFkParentCategoryNode()
        );
    }
}
