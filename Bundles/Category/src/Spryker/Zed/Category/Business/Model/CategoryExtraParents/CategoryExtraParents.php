<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryExtraParents;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Spryker\Zed\Category\Business\Model\CategoryToucherInterface;
use Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class CategoryExtraParents implements CategoryExtraParentsInterface
{

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface
     */
    protected $closureTableWriter;

    /**
     * @var \Spryker\Zed\Category\Business\Model\CategoryToucherInterface
     */
    protected $categoryToucher;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface $closureTableWriter
     * @param \Spryker\Zed\Category\Business\Model\CategoryToucherInterface $categoryToucher
     */
    public function __construct(
        CategoryQueryContainerInterface $queryContainer,
        ClosureTableWriterInterface $closureTableWriter,
        CategoryToucherInterface $categoryToucher
    ) {
        $this->queryContainer = $queryContainer;
        $this->closureTableWriter = $closureTableWriter;
        $this->categoryToucher = $categoryToucher;
    }


    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer)
    {
        $this->assignExtraParents($categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        $this->assignExtraParents($categoryTransfer);
        $this->removeDeassignedExtraParents($categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function assignExtraParents(CategoryTransfer $categoryTransfer)
    {
        $extraParentNodesTransferCollection = $categoryTransfer->getExtraParents();

        foreach ($extraParentNodesTransferCollection as $extraParentNodeTransfer) {
            $this->assignParent($categoryTransfer->getIdCategory(), $extraParentNodeTransfer);
        }
    }

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\NodeTransfer $extraParentNodeTransfer
     *
     * @return void
     */
    protected function assignParent($idCategory, NodeTransfer $extraParentNodeTransfer)
    {
        $assignmentNodeEntity = $this->getAssignmentNodeEntity($idCategory, $extraParentNodeTransfer);

        $isNewNode = $assignmentNodeEntity->isNew();

        $assignmentNodeEntity->setFkCategory($idCategory);
        $assignmentNodeEntity->setIsMain(false);
        $assignmentNodeEntity->save();

        $this->updateClosureTable($assignmentNodeEntity, $isNewNode);

        $this->categoryToucher->touchCategoryNodeActiveRecursively($assignmentNodeEntity->getIdCategoryNode());
    }


    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\NodeTransfer $parentNodeTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode
     */
    protected function getAssignmentNodeEntity($idCategory, NodeTransfer $parentNodeTransfer)
    {
        return $this
            ->queryContainer
            ->queryNotMainNodesByCategoryId($idCategory)
            ->filterByFkParentCategoryNode($parentNodeTransfer->getIdCategoryNode())
            ->findOneOrCreate();
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNodeEntity
     * @param bool $isNewNode
     *
     * @return void
     */
    protected function updateClosureTable(SpyCategoryNode $categoryNodeEntity, $isNewNode)
    {
        $categoryNodeTransfer = (new NodeTransfer())->fromArray($categoryNodeEntity->toArray());

        if ($isNewNode) {
            $this->closureTableWriter->create($categoryNodeTransfer);
        } else {
            $this->closureTableWriter->moveNode($categoryNodeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function removeDeassignedExtraParents(CategoryTransfer $categoryTransfer)
    {
        $existingAssignmentNodeList = $this->getExistingAssignmentNodes($categoryTransfer);
        $assignedParentNodeIds = $this->getAssignedExtraParentNodeIds($categoryTransfer);

        foreach ($existingAssignmentNodeList as $assignmentNodeEntity) {
            if (in_array((int)$assignmentNodeEntity->getFkParentCategoryNode(), $assignedParentNodeIds)) {
                continue;
            }

            $this->removeAssignmentNode($assignmentNodeEntity);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getExistingAssignmentNodes(CategoryTransfer $categoryTransfer)
    {
        return $this
            ->queryContainer
            ->queryNotMainNodesByCategoryId($categoryTransfer->getIdCategory())
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return int[]
     */
    protected function getAssignedExtraParentNodeIds(CategoryTransfer $categoryTransfer)
    {
        $nodeIds = [];
        foreach ($categoryTransfer->getExtraParents() as $categoryNodeTransfer) {
            $nodeIds[] = (int)$categoryNodeTransfer->getIdCategoryNode();
        }

        return $nodeIds;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNodeEntity
     *
     * @return void
     */
    protected function removeAssignmentNode(SpyCategoryNode $categoryNodeEntity)
    {
        $this->closureTableWriter->delete($categoryNodeEntity->getIdCategoryNode());
        $categoryNodeEntity->delete();

        $this->categoryToucher->touchCategoryNodeDeleted($categoryNodeEntity->getIdCategoryNode());
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory)
    {
        $assignmentNodeCollection = $this
            ->queryContainer
            ->queryNotMainNodesByCategoryId($idCategory)
            ->find();

        foreach ($assignmentNodeCollection as $assignmentNodeEntity) {
            $this->moveSubTreeToParent($assignmentNodeEntity);
            $this->removeAssignmentNode($assignmentNodeEntity);
        }
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $sourceNodeEntity
     *
     * @return void
     */
    protected function moveSubTreeToParent(SpyCategoryNode $sourceNodeEntity)
    {
        $idDestinationParentNode = $sourceNodeEntity->getFkParentCategoryNode();
        $firstLevelChildNodeCollection = $this
            ->queryContainer
            ->queryFirstLevelChildren($sourceNodeEntity->getIdCategoryNode())
            ->find();

        foreach ($firstLevelChildNodeCollection as $childNodeEntity) {
            $childNodeEntity->setFkParentCategoryNode($idDestinationParentNode);
            $childNodeEntity->save();

            $categoryNodeTransfer = (new NodeTransfer())->fromArray($childNodeEntity->toArray());
            $this->closureTableWriter->moveNode($categoryNodeTransfer);

            $this->categoryToucher->touchCategoryNodeActiveRecursively($childNodeEntity->getIdCategoryNode());
        }
    }

}
