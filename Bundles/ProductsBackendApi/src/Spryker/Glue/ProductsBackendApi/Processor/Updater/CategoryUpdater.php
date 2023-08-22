<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Updater;

use Generated\Shared\Transfer\CategoryConditionsTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\ProductCategoryConditionsTransfer;
use Generated\Shared\Transfer\ProductCategoryCriteriaTransfer;
use Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToCategoryFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductCategoryFacadeInterface;

class CategoryUpdater implements CategoryUpdaterInterface
{
    /**
     * @var \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToCategoryFacadeInterface
     */
    protected ProductsBackendApiToCategoryFacadeInterface $categoryFacade;

    /**
     * @var \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductCategoryFacadeInterface
     */
    protected ProductsBackendApiToProductCategoryFacadeInterface $productCategoryFacade;

    /**
     * @param \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductCategoryFacadeInterface $productCategoryFacade
     */
    public function __construct(
        ProductsBackendApiToCategoryFacadeInterface $categoryFacade,
        ProductsBackendApiToProductCategoryFacadeInterface $productCategoryFacade
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->productCategoryFacade = $productCategoryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function createCategoryAssignment(ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer, int $idProductAbstract): void
    {
        $categoryKeys = [];
        foreach ($productsBackendApiAttributesTransfer->getCategories() as $productCategoriesBackendApiAttributesTransfer) {
            $categoryKeys[] = $productCategoriesBackendApiAttributesTransfer->getCategoryKeyOrFail();
        }
        $categoryCollectionTransfer = $this->categoryFacade->getCategoryCollection(
            (new CategoryCriteriaTransfer())
                ->setCategoryConditions(
                    (new CategoryConditionsTransfer())
                        ->setCategoryKeys($categoryKeys),
                ),
        );

        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $this->productCategoryFacade->createProductCategoryMappings($categoryTransfer->getIdCategoryOrFail(), [$idProductAbstract]);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function updateCategories(ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer, int $idProductAbstract): void
    {
        $productCategoryCollectionTransfer = $this->productCategoryFacade->getProductCategoryCollection(
            (new ProductCategoryCriteriaTransfer())
                ->setProductCategoryConditions(
                    (new ProductCategoryConditionsTransfer())
                        ->addIdProductAbstract($idProductAbstract),
                ),
        );
        $currentCategoryIds = [];
        foreach ($productCategoryCollectionTransfer->getProductCategories() as $productCategoryTransfer) {
            $currentCategoryIds[] = $productCategoryTransfer->getFkCategoryOrFail();
        }

        $categoryKeys = [];
        foreach ($productsBackendApiAttributesTransfer->getCategories() as $productCategoriesBackendApiAttributesTransfer) {
            $categoryKeys[] = $productCategoriesBackendApiAttributesTransfer->getCategoryKeyOrFail();
        }
        $categoryCollectionTransfer = $this->categoryFacade->getCategoryCollection(
            (new CategoryCriteriaTransfer())
                ->setCategoryConditions(
                    (new CategoryConditionsTransfer())
                        ->setCategoryKeys($categoryKeys),
                ),
        );

        $categoryIds = [];
        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $categoryIds[] = $categoryTransfer->getIdCategoryOrFail();
            $this->productCategoryFacade->createProductCategoryMappings($categoryTransfer->getIdCategoryOrFail(), [$idProductAbstract]);
        }

        $categoriesToDelete = array_diff($currentCategoryIds, $categoryIds);
        if ($categoriesToDelete) {
            foreach ($categoriesToDelete as $categoryId) {
                $this->productCategoryFacade->removeProductCategoryMappings($categoryId, [$idProductAbstract]);
            }
        }
    }
}
