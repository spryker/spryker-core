<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Dependency\Facade;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\CategoryNodeFilterTransfer;
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
     * @param \Generated\Shared\Transfer\CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getCategoryNodesWithRelativeNodesByCriteria(
        CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
    ): array {
        return $this->categoryFacade->getCategoryNodesWithRelativeNodesByCriteria($categoryNodeCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeFilterTransfer $categoryNodeFilterTransfer
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    public function getCategoryNodesByCriteria(CategoryNodeFilterTransfer $categoryNodeFilterTransfer): NodeCollectionTransfer
    {
        return $this->categoryFacade->getCategoryNodesByCriteria($categoryNodeFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    public function getCategoryNodeCollectionByCriteria(CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer): NodeCollectionTransfer
    {
        return $this->categoryFacade->getCategoryNodeCollectionByCriteria($categoryNodeCriteriaTransfer);
    }
}
