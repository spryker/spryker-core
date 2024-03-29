<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Dependency\QueryContainer;

use Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery;

class ProductCategoryFilterGuiToCategoryQueryContainerBridge implements ProductCategoryFilterGuiToCategoryQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $categoryQueryContainer
     */
    public function __construct($categoryQueryContainer)
    {
        $this->categoryQueryContainer = $categoryQueryContainer;
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryRootNodes(): SpyCategoryAttributeQuery
    {
        return $this->categoryQueryContainer->queryRootNodes();
    }

    /**
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryAttributeByCategoryId($idNode): SpyCategoryAttributeQuery
    {
        return $this->categoryQueryContainer->queryAttributeByCategoryId($idNode);
    }
}
