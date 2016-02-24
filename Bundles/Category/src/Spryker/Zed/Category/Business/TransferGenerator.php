<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Propel\Runtime\Collection\ObjectCollection;

class TransferGenerator implements TransferGeneratorInterface
{

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function convertCategory(SpyCategory $categoryEntity)
    {
        return (new CategoryTransfer())
            ->fromArray($categoryEntity->toArray());
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory[]|\Propel\Runtime\Collection\ObjectCollection $categoryEntityList
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer[]
     */
    public function convertCategoryCollection(ObjectCollection $categoryEntityList)
    {
        $transferList = [];
        foreach ($categoryEntityList as $categoryEntity) {
            $transferList[] = $this->convertCategory($categoryEntity);
        }

        return $transferList;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $nodeEntity
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function convertCategoryNode(SpyCategoryNode $nodeEntity)
    {
        return (new NodeTransfer())
            ->fromArray($nodeEntity->toArray());
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
