<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\RestCategoriesTreeTransfer;
use Generated\Shared\Transfer\RestCategoryTreesAttributesTransfer;
use Generated\Shared\Transfer\RestProductCategoriesAttributesTransfer;
use Generated\Shared\Transfer\RestProductCategoriesTreeTransfer;

class CategoriesResourceMapper implements CategoriesResourceMapperInterface
{
    /**
     * @param array $categoriesResource
     *
     * @return \Generated\Shared\Transfer\RestCategoriesTreeTransfer
     */
    public function mapCategoriesResourceToRestCategoriesTransfer(array $categoriesResource): RestCategoriesTreeTransfer
    {
        $rootCategories = new ArrayObject();
        foreach ($categoriesResource as $categoriesResourceItem) {
            $categoriesResourceTransfer = (new RestCategoryTreesAttributesTransfer())
                ->fromArray(
                    $categoriesResourceItem->toArray(),
                    true
                );
            $rootCategories->append($categoriesResourceTransfer);
        }
        return (new RestCategoriesTreeTransfer())->setCategoryNodesStorage($rootCategories);
    }

    /**
     * @param array $productCategoriesResource
     *
     * @return \Generated\Shared\Transfer\RestProductCategoriesTreeTransfer
     */
    public function mapProductCategoriesToRestProductCategoriesTransfer(array $productCategoriesResource): RestProductCategoriesTreeTransfer
    {
        $productCategories = new RestProductCategoriesTreeTransfer();

        foreach ($productCategoriesResource as $productCategoriesResourceItem) {
            $productCategoriesResourceTransfer = (new RestProductCategoriesAttributesTransfer())
                ->fromArray($productCategoriesResourceItem->toArray(), true);
            $productCategories->addProductCategoriesStorage($productCategoriesResourceTransfer);
        }

        return $productCategories;
    }
}
