<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Processor\Mapper;

use ArrayObject;
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
        $rootCategories = new ArrayObject();
        foreach ($categoriesResource as $categoriesResourceItem) {
            $categoriesResourceTransfer = (new RestCategoriesAttributesTransfer())
                ->fromArray(
                    $categoriesResourceItem->toArray(),
                    true
                );
            $rootCategories->append($categoriesResourceTransfer);
        }
        return (new RestCategoriesTreeTransfer())->setCategoryNodesStorage($rootCategories);
    }
}
