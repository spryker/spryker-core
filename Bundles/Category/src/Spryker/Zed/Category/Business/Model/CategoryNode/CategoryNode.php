<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryNode;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
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
     * @param \Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface $closureTableWriter
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     */
    public function __construct(
        ClosureTableWriterInterface $closureTableWriter,
        CategoryQueryContainerInterface $queryContainer
    ) {
        $this->closureTableWriter = $closureTableWriter;
        $this->queryContainer = $queryContainer;
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

        $categoryNodeTransfer = $this->getCategoryNodeTransferFromEntity($categoryNodeEntity);

        $categoryTransfer->setCategoryNode($categoryNodeTransfer);

        $this->closureTableWriter->create($categoryNodeTransfer);
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

        $categoryNodeTransfer = $this->getCategoryNodeTransferFromEntity($categoryNodeEntity);

        $categoryTransfer->setCategoryNode($categoryNodeTransfer);

        $this->closureTableWriter->moveNode($categoryNodeTransfer);
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
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNodeEntity
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    private function getCategoryNodeTransferFromEntity(SpyCategoryNode $categoryNodeEntity)
    {
        $categoryNodeTransfer = new NodeTransfer();
        $categoryNodeTransfer->fromArray($categoryNodeEntity->toArray(), true);

        return $categoryNodeTransfer;
    }

}
