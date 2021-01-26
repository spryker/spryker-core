<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CategoryDataImport\Dependency\Facade;

use Generated\Shared\Transfer\StoreRelationTransfer;

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
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $newStoreAssignment
     * @param \Generated\Shared\Transfer\StoreRelationTransfer|null $currentStoreAssignment
     *
     * @return void
     */
    public function updateCategoryStoreRelationWithMainChildrenPropagation(
        int $idCategory,
        StoreRelationTransfer $newStoreAssignment,
        ?StoreRelationTransfer $currentStoreAssignment = null
    ): void {
        $this->categoryFacade->updateCategoryStoreRelationWithMainChildrenPropagation($idCategory, $newStoreAssignment, $currentStoreAssignment);
    }
}
