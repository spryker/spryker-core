<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Generator;

use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Propel\Runtime\Collection\ObjectCollection;

class TransferGenerator implements TransferGeneratorInterface
{
    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $nodeEntity
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function convertCategoryNode(SpyCategoryNode $nodeEntity)
    {
        $categoryNodeTransfer = new NodeTransfer();
        $categoryNodeTransfer->fromArray($nodeEntity->toArray(), true);

        return $categoryNodeTransfer;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode[]|\Propel\Runtime\Collection\ObjectCollection $categoryNodeEntityList
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function convertCategoryNodeCollection(ObjectCollection $categoryNodeEntityList)
    {
        $transferList = [];
        foreach ($categoryNodeEntityList as $categoryNodeEntity) {
            $transferList[] = $this->convertCategoryNode($categoryNodeEntity);
        }

        return $transferList;
    }
}
