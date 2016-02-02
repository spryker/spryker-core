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

interface TransferGeneratorInterface
{

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function convertCategory(SpyCategory $categoryEntity);

    /**
     * @param SpyCategory[]|\Propel\Runtime\Collection\ObjectCollection $categoryEntityList
     *
     * @return CategoryTransfer[]
     */
    public function convertCategoryCollection(ObjectCollection $categoryEntityList);

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $nodeEntity
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function convertCategoryNode(SpyCategoryNode $nodeEntity);

    /**
     * @param SpyCategoryNode[]|\Propel\Runtime\Collection\ObjectCollection $categoryNodeEntityList
     *
     * @return NodeTransfer[]
     */
    public function convertCategoryNodeCollection(ObjectCollection $categoryNodeEntityList);

}
