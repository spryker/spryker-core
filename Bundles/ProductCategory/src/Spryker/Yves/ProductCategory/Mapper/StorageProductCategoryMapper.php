<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductCategory\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\StorageProductCategoryTransfer;
use Generated\Shared\Transfer\StorageProductTransfer;

class StorageProductCategoryMapper implements StorageProductCategoryMapperInterface
{

    /**
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     * @param array $persistedProduct
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function mapProductCategories(StorageProductTransfer $storageProductTransfer, array $persistedProduct)
    {
        if (array_key_exists(StorageProductTransfer::CATEGORIES, $persistedProduct) === false) {
            return $storageProductTransfer;
        }

        $persistedCategories = $persistedProduct[StorageProductTransfer::CATEGORIES];
        $categories = new ArrayObject();
        foreach ($persistedCategories as $category) {
            $storageProductCategoryTransfer = new StorageProductCategoryTransfer();
            $storageProductCategoryTransfer->fromArray($category, true);

            $categories->append($storageProductCategoryTransfer);
        }

        $storageProductTransfer->setCategories($categories);

        return $storageProductTransfer;
    }

}
