<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Mapper;

use Generated\Shared\Transfer\CategoryNodeCollectionRequestTransfer;
use Generated\Shared\Transfer\NodeTransfer;

class CategoryNodeMapper implements CategoryNodeMapperInterface
{
    /**
     * @var string
     */
    protected const KEY_ID = 'id';

    /**
     * @param list<array<string, mixed>> $categoryNodesData
     * @param \Generated\Shared\Transfer\CategoryNodeCollectionRequestTransfer $categoryNodeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryNodeCollectionRequestTransfer
     */
    public function mapCategoryNodesDataToCategoryNodeCollectionRequestTransfer(
        array $categoryNodesData,
        CategoryNodeCollectionRequestTransfer $categoryNodeCollectionRequestTransfer
    ): CategoryNodeCollectionRequestTransfer {
        foreach ($categoryNodesData as $categoryNodeData) {
            $categoryNodeCollectionRequestTransfer->addCategoryNode(
                $this->mapCategoryNodeDataToNodeTransfer($categoryNodeData, new NodeTransfer()),
            );
        }

        return $categoryNodeCollectionRequestTransfer;
    }

    /**
     * @param array<string, mixed> $categoryNodeData
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    protected function mapCategoryNodeDataToNodeTransfer(array $categoryNodeData, NodeTransfer $nodeTransfer): NodeTransfer
    {
        return $nodeTransfer->setIdCategoryNode($categoryNodeData[static::KEY_ID]);
    }
}
