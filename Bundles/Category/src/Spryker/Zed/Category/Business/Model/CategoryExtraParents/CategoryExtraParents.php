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
use Spryker\Zed\Category\Business\Model\CategoryTree\CategoryTreeInterface;
use Spryker\Zed\Category\Business\Model\CategoryUrl\CategoryUrlInterface;
use Spryker\Zed\Category\Business\TransferGeneratorInterface;
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
     * @var \Spryker\Zed\Category\Business\Model\CategoryTree\CategoryTreeInterface
     */
    protected $categoryTree;

    /**
     * @var \Spryker\Zed\Category\Business\Model\CategoryUrl\CategoryUrlInterface
     */
    protected $categoryUrl;

    /**
     * @var \Spryker\Zed\Category\Business\TransferGeneratorInterface
     */
    protected $transferGenerator;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface $closureTableWriter
     * @param \Spryker\Zed\Category\Business\Model\CategoryToucherInterface $categoryToucher
     * @param \Spryker\Zed\Category\Business\Model\CategoryTree\CategoryTreeInterface $categoryTree
     * @param \Spryker\Zed\Category\Business\Model\CategoryUrl\CategoryUrlInterface $categoryUrl
     * @param \Spryker\Zed\Category\Business\TransferGeneratorInterface $transferGenerator
     */
    public function __construct(
        CategoryQueryContainerInterface $queryContainer,
        ClosureTableWriterInterface $closureTableWriter,
        CategoryToucherInterface $categoryToucher,
        CategoryTreeInterface $categoryTree,
        CategoryUrlInterface $categoryUrl,
        TransferGeneratorInterface $transferGenerator
    ) {
        $this->queryContainer = $queryContainer;
        $this->closureTableWriter = $closureTableWriter;
        $this->categoryToucher = $categoryToucher;
        $this->categoryTree = $categoryTree;
        $this->categoryUrl = $categoryUrl;
        $this->transferGenerator = $transferGenerator;
    }

    /**
     * @deprecated Use \Spryker\Zed\Category\Business\Model\CategoryReaderInterface::findCategoryById() instead.
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function read($idCategory, CategoryTransfer $categoryTransfer)
    {
        $categoryNodeCollection = $this
            ->queryContainer
            ->queryNotMainNodesByCategoryId($idCategory)
            ->find();

        foreach ($categoryNodeCollection as $categoryNodeEntity) {
            $parentNodeEntity = $categoryNodeEntity->getParentCategoryNode();

            $categoryNodeTransfer = new NodeTransfer();
            $categoryNodeTransfer->fromArray($parentNodeEntity->toArray(), true);

            $categoryTransfer->addExtraParent($categoryNodeTransfer);
        }

        return $categoryTransfer;
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
        $idParentCategoryNode = $categoryTransfer->getParentCategoryNode()->getIdCategoryNode();

        foreach ($extraParentNodesTransferCollection as $extraParentNodeTransfer) {
            if ($idParentCategoryNode === $extraParentNodeTransfer->getIdCategoryNode()) {
                continue;
            }

            $this->assignParent($categoryTransfer, $extraParentNodeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\NodeTransfer $extraParentNodeTransfer
     *
     * @return void
     */
    protected function assignParent(CategoryTransfer $categoryTransfer, NodeTransfer $extraParentNodeTransfer)
    {
        $idCategory = $categoryTransfer->requireIdCategory()->getIdCategory();

        $assignmentNodeEntity = $this->getAssignmentNodeEntity($idCategory, $extraParentNodeTransfer);

        $isNewNode = $assignmentNodeEntity->isNew();

        $assignmentNodeEntity->setFkCategory($idCategory);
        $assignmentNodeEntity->setIsMain(false);
        $assignmentNodeEntity->save();

        $this->updateClosureTable($assignmentNodeEntity, $isNewNode);
        $this->saveAssignmentNodeUrls($categoryTransfer, $assignmentNodeEntity);

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
            ->filterByFkParentCategoryNode($parentNodeTransfer->requireIdCategoryNode()->getIdCategoryNode())
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
        $categoryNodeTransfer = $this->transferGenerator->convertCategoryNode($categoryNodeEntity);

        if ($isNewNode) {
            $this->closureTableWriter->create($categoryNodeTransfer);
        } else {
            $this->closureTableWriter->moveNode($categoryNodeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNodeEntity
     *
     * @return void
     */
    protected function saveAssignmentNodeUrls(CategoryTransfer $categoryTransfer, SpyCategoryNode $categoryNodeEntity)
    {
        $localizedAttributesCollection = $categoryTransfer->getLocalizedAttributes();
        $categoryNodeTransfer = $this->transferGenerator->convertCategoryNode($categoryNodeEntity);

        foreach ($localizedAttributesCollection as $localizedAttributesTransfer) {
            $localeTransfer = $localizedAttributesTransfer->requireLocale()->getLocale();

            $this->categoryUrl->saveLocalizedUrlForNode($categoryNodeTransfer, $localeTransfer, $categoryTransfer);
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

            $this->moveSubTreeToParent($assignmentNodeEntity);
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
            ->queryNotMainNodesByCategoryId($categoryTransfer->requireIdCategory()->getIdCategory())
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
            $nodeIds[] = (int)$categoryNodeTransfer->requireIdCategoryNode()->getIdCategoryNode();
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
        $this->categoryUrl->deleteUrlsForCategoryNode($categoryNodeEntity->getIdCategoryNode());
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
        $this->categoryTree->moveSubTree(
            $sourceNodeEntity->getIdCategoryNode(),
            $sourceNodeEntity->getFkParentCategoryNode()
        );
    }
}
