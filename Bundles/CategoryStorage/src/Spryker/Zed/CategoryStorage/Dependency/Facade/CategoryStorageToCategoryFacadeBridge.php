<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Dependency\Facade;

use Generated\Shared\Transfer\CategoryNodeTreeElementCriteriaTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;

class CategoryStorageToCategoryFacadeBridge implements CategoryStorageToCategoryFacadeInterface
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
     * @param \Generated\Shared\Transfer\CategoryNodeTreeElementCriteriaTransfer $categoryNodeTreeElementCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getAllActiveCategoryNodeTreeMenuElementsByCategoryNodeIds(
        CategoryNodeTreeElementCriteriaTransfer $categoryNodeTreeElementCriteriaTransfer
    ): array {
        return $this->categoryFacade->getAllActiveCategoryNodeTreeMenuElementsByCategoryNodeIds($categoryNodeTreeElementCriteriaTransfer);
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

    /**
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    public function getRootCategoryNodes(): NodeCollectionTransfer
    {
        return $this->categoryFacade->getRootCategoryNodes();
    }
}
