<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Propel\Runtime\Collection\ObjectCollection;

class CategoryNodeMapper
{
    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode[]|\Propel\Runtime\Collection\ObjectCollection $nodeEntities
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $nodeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    public function mapNodeCollection(ObjectCollection $nodeEntities, NodeCollectionTransfer $nodeCollectionTransfer): NodeCollectionTransfer
    {
        foreach ($nodeEntities as $nodeEntity) {
            $nodeCollectionTransfer->addNode($this->mapCategoryNode($nodeEntity, new NodeTransfer()));
        }

        return $nodeCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNodeEntity
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode
     */
    public function mapNodeTransferToCategoryNodeEntity(NodeTransfer $nodeTransfer, SpyCategoryNode $categoryNodeEntity): SpyCategoryNode
    {
        $categoryNodeEntity->fromArray($nodeTransfer->modifiedToArray());

        return $categoryNodeEntity;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $spyCategoryNode
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function mapCategoryNode(SpyCategoryNode $spyCategoryNode, NodeTransfer $nodeTransfer): NodeTransfer
    {
        return $nodeTransfer->fromArray($spyCategoryNode->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function mapCategoryNodes(SpyCategory $categoryEntity, CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        foreach ($categoryEntity->getNodes() as $categoryNodeEntity) {
            if (!$categoryNodeEntity->isMain()) {
                continue;
            }
            $nodeTransfer = $this->mapCategoryNode($categoryNodeEntity, new NodeTransfer());
            $nodeTransfer->setCategory(clone $categoryTransfer);
            $categoryTransfer->setCategoryNode($nodeTransfer);
        }

        return $categoryTransfer;
    }
}
