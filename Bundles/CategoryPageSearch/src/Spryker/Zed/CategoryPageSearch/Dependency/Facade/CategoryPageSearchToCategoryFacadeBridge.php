<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Dependency\Facade;

use Generated\Shared\Transfer\NodeCollectionTransfer;

class CategoryPageSearchToCategoryFacadeBridge implements CategoryPageSearchToCategoryFacadeInterface
{
    /**
     * @var \Spryker\Zed\Category\Business\CategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @param \Spryker\Zed\Category\Business\CategoryFacadeInterface $categoryFacade
     */
    public function __construct($categoryFacade)
    {
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    public function getActiveCategoryNodesByCategoryNodeIds(array $categoryNodeIds): NodeCollectionTransfer
    {
        return $this->categoryFacade->getActiveCategoryNodesByCategoryNodeIds($categoryNodeIds);
    }

    /**
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function getCategoryNodeIdsByCategoryIds(array $categoryIds): array
    {
        return $this->categoryFacade->getCategoryNodeIdsByCategoryIds($categoryIds);
    }
}
