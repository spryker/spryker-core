<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Dependency\Facade;

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
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getCategoryNodesByCategoryNodeIds(array $categoryNodeIds): array
    {
        return $this->categoryFacade->getCategoryNodesByCategoryNodeIds($categoryNodeIds);
    }

    /**
     * @param int[] $categoryStoreIds
     *
     * @return int[]
     */
    public function getCategoryNodeIdsByCategoryIds(array $categoryStoreIds): array
    {
        return $this->categoryFacade->getCategoryNodeIdsByCategoryIds($categoryStoreIds);
    }
}
