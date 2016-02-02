<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Category\Business;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryNode;

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
     * @param SpyCategory[]|\Propel\Runtime\Collection\ObjectCollection $categoryEntityList
     *
     * @return CategoryTransfer[]
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
     * @param SpyCategoryNode[]|\Propel\Runtime\Collection\ObjectCollection $categoryNodeEntityList
     *
     * @return NodeTransfer[]
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
