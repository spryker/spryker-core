<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;

class CategoryNodeMapper
{
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
}
