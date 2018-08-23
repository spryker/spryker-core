<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\RestCategoriesTreeTransfer;
use Generated\Shared\Transfer\RestCategoryTreesAttributesTransfer;

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
}
