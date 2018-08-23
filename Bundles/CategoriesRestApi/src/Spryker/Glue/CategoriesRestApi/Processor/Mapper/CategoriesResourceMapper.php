<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Generated\Shared\Transfer\RestCategoriesTreeTransfer;
use Generated\Shared\Transfer\RestCategoryTreesAttributesTransfer;
use Generated\Shared\Transfer\RestProductCategoriesAttributesTransfer;
use Generated\Shared\Transfer\RestProductCategoriesTreeTransfer;

class CategoriesResourceMapper implements CategoriesResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categoryNodeStorageTransfers
     *
     * @return \Generated\Shared\Transfer\RestCategoriesTreeTransfer
     */
    public function mapCategoriesResourceToRestCategoriesTransfer(array $categoryNodeStorageTransfers): RestCategoriesTreeTransfer
    {
        $rootCategories = new ArrayObject();
        foreach ($categoryNodeStorageTransfers as $categoryNodeStorageTransfer) {
            $categoriesResourceTransfer = (new RestCategoryTreesAttributesTransfer())
                ->fromArray(
                    $categoryNodeStorageTransfer->toArray(),
                    true
                );
            $rootCategories->append($categoriesResourceTransfer);
        }
        return (new RestCategoriesTreeTransfer())->setCategoryNodesStorage($rootCategories);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer $productAbstractCategoryStorageTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductCategoriesTreeTransfer
     */
    public function mapProductCategoriesToRestProductCategoriesTransfer(ProductAbstractCategoryStorageTransfer $productAbstractCategoryStorageTransfer): RestProductCategoriesTreeTransfer
    {
        $productCategories = new RestProductCategoriesTreeTransfer();

        foreach ($productAbstractCategoryStorageTransfer->getCategories() as $productCategoriesResourceItem) {
            $productCategoriesResourceTransfer = (new RestProductCategoriesAttributesTransfer())
                ->fromArray($productCategoriesResourceItem->toArray(), true);
            $productCategories->addProductCategoriesStorage($productCategoriesResourceTransfer);
        }

        return $productCategories;
    }
}
