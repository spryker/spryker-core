<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryNode;

use Generated\Shared\Transfer\CategoryTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Spryker\Zed\Category\Business\Model\CategoryToucherInterface;
use Spryker\Zed\Category\Business\TransferGeneratorInterface;
use Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class CategoryNode implements CategoryNodeInterface
{

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
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
     * @param \Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface $closureTableWriter
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Business\TransferGeneratorInterface $transferGenerator
     * @param \Spryker\Zed\Category\Business\Model\CategoryToucherInterface $categoryToucher
     */
    public function __construct(
        ClosureTableWriterInterface $closureTableWriter,
        CategoryQueryContainerInterface $queryContainer,
        TransferGeneratorInterface $transferGenerator,
        CategoryToucherInterface $categoryToucher
    ) {
        $this->closureTableWriter = $closureTableWriter;
        $this->queryContainer = $queryContainer;
        $this->transferGenerator = $transferGenerator;
        $this->categoryToucher = $categoryToucher;
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
        $this->categoryToucher->touchCategoryNodeActiveRecursively($categoryNodeEntity->getIdCategoryNode());
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        $categoryNodeEntity = $this
            ->queryContainer
            ->queryCategoryNodeByNodeId($categoryTransfer->getCategoryNode()->getIdCategoryNode())
            ->findOne();
        $categoryNodeEntity = $this->setUpCategoryNodeEntity($categoryTransfer, $categoryNodeEntity);
        $categoryNodeEntity->save();

        $categoryNodeTransfer = $this->transferGenerator->convertCategoryNode($categoryNodeEntity);
        $categoryTransfer->setCategoryNode($categoryNodeTransfer);

        $this->closureTableWriter->moveNode($categoryNodeTransfer);

        $this->categoryToucher->touchCategoryNodeActiveRecursively($categoryNodeEntity->getIdCategoryNode());
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNodeEntity
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode
     */
    private function setUpCategoryNodeEntity(CategoryTransfer $categoryTransfer, SpyCategoryNode $categoryNodeEntity)
    {
        $categoryNodeTransfer = $categoryTransfer->getCategoryNode();
        $parentCategoryNodeTransfer = $categoryTransfer->getParentCategoryNode();

        $categoryNodeEntity->fromArray($categoryNodeTransfer->toArray());
        $categoryNodeEntity->setIsMain(true);
        $categoryNodeEntity->setFkCategory($categoryTransfer->getIdCategory());
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
            $this->moveSubTreeToParent($categoryNodeEntity);
            $this->closureTableWriter->delete($categoryNodeEntity->getIdCategoryNode());
            $categoryNodeEntity->delete();

            $this->categoryToucher->touchCategoryNodeDeleted($categoryNodeEntity->getIdCategoryNode());
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

            $categoryNodeTransfer = $this->transferGenerator->convertCategoryNode($childNodeEntity);
            $this->closureTableWriter->moveNode($categoryNodeTransfer);

            $this->categoryToucher->touchCategoryNodeActiveRecursively($childNodeEntity->getIdCategoryNode());
        }
    }

}
