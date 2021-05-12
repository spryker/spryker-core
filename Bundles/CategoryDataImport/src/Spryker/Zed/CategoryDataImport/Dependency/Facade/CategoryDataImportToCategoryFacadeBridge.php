<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CategoryDataImport\Dependency\Facade;

use Generated\Shared\Transfer\UpdateCategoryStoreRelationRequestTransfer;

class CategoryDataImportToCategoryFacadeBridge implements CategoryDataImportToCategoryFacadeInterface
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
     * @param \Generated\Shared\Transfer\UpdateCategoryStoreRelationRequestTransfer $updateCategoryStoreRelationRequestTransfer
     *
     * @return void
     */
    public function updateCategoryStoreRelation(
        UpdateCategoryStoreRelationRequestTransfer $updateCategoryStoreRelationRequestTransfer
    ): void {
        $this->categoryFacade->updateCategoryStoreRelation($updateCategoryStoreRelationRequestTransfer);
    }
}
