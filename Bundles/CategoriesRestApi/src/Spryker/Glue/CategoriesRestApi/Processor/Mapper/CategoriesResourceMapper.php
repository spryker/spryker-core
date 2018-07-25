<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\RestCategoriesAttributesTransfer;
use Generated\Shared\Transfer\RestCategoriesTreeTransfer;

class CategoriesResourceMapper implements CategoriesResourceMapperInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categoriesResource
     *
     * @return \Generated\Shared\Transfer\RestCategoriesTreeTransfer
     */
    public function mapCategoriesResourceToRestCategoriesTransfer(ArrayObject $categoriesResource): RestCategoriesTreeTransfer
    {
        $categoriesTreeTransfer = new RestCategoriesTreeTransfer();
        $rootCategories = new ArrayObject();
        foreach ($categoriesResource as $categoriesResourceItem) {
            $categoriesResourceItem = $this->traverseCategoriesTree($categoriesResourceItem);
            $rootCategories->append($categoriesResourceItem);
        }
        $categoriesTreeTransfer->setCategoryNodesStorage($rootCategories);

        return $categoriesTreeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoriesResourceItem
     *
     * @return \Generated\Shared\Transfer\RestCategoriesAttributesTransfer
     */
    protected function traverseCategoriesTree(CategoryNodeStorageTransfer $categoriesResourceItem)
    {
        $categoriesTransfer = new RestCategoriesAttributesTransfer();
        $categoriesTransfer->fromArray($categoriesResourceItem->toArray(), true);
        $categoriesTransfer->setIsRoot(false);

        if ($categoriesResourceItem->getChildren()) {
            $childrenCategories = new ArrayObject();
            foreach ($categoriesResourceItem->getChildren() as $categoriesChildItem) {
                $childrenCategories->append($this->traverseCategoriesTree($categoriesChildItem));
            }
            $categoriesTransfer->setChildren($childrenCategories);
        }

        return $categoriesTransfer;
    }
}
