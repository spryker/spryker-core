<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryNode;

interface TransferGeneratorInterface
{

    /**
     * @param SpyCategory $categoryEntity
     *
     * @return CategoryTransfer
     */
    public function convertCategory(SpyCategory $categoryEntity);

    /**
     * @param SpyCategory[]|ObjectCollection $categoryEntityList
     *
     * @return CategoryTransfer[]
     */
    public function convertCategoryCollection(ObjectCollection $categoryEntityList);

    /**
     * @param SpyCategoryNode $nodeEntity
     *
     * @return NodeTransfer
     */
    public function convertCategoryNode(SpyCategoryNode $nodeEntity);

    /**
     * @param SpyCategoryNode[]|ObjectCollection $categoryNodeEntityList
     *
     * @return NodeTransfer[]
     */
    public function convertCategoryNodeCollection(ObjectCollection $categoryNodeEntityList);

}
