<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Dependency\QueryContainer;

use Orm\Zed\Category\Persistence\SpyCategoryStoreQuery;

class CategoryGuiToCategoryQueryContainerBridge implements CategoryGuiToCategoryQueryContainerInterface
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
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryStoreQuery
     */
    public function queryCategoryStoreWithStoresByFkCategory(int $idCategory): SpyCategoryStoreQuery
    {
        return $this->categoryQueryContainer->queryCategoryStoreWithStoresByFkCategory($idCategory);
    }
}
