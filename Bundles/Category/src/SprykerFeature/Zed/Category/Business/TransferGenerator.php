<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategory;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNode;

class TransferGenerator implements TransferGeneratorInterface
{

    /**
     * @param SpyCategory $categoryEntity
     *
     * @return CategoryTransfer
     */
    public function convertCategory(SpyCategory $categoryEntity)
    {
        return (new CategoryTransfer())
            ->fromArray($categoryEntity->toArray());
    }

    /**
     * @param SpyCategory[]|ObjectCollection $categoryEntityList
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
     * @param SpyCategoryNode $nodeEntity
     *
     * @return NodeTransfer
     */
    public function convertCategoryNode(SpyCategoryNode $nodeEntity)
    {
        return (new NodeTransfer())
            ->fromArray($nodeEntity->toArray());
    }

    /**
     * @param SpyCategoryNode[]|ObjectCollection $categoryNodeEntityList
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
