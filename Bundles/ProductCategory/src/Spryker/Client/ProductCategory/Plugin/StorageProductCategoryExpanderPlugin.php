<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategory\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\StorageProductCategoryTransfer;
use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Product\Dependency\Plugin\StorageProductExpanderPluginInterface;

class StorageProductCategoryExpanderPlugin extends AbstractPlugin implements StorageProductExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     * @param array $productData
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function expandStorageProduct(StorageProductTransfer $storageProductTransfer, array $productData, $locale)
    {
        if (array_key_exists(StorageProductTransfer::CATEGORIES, $productData) === false) {
            return $storageProductTransfer;
        }

        $persistedCategories = $productData[StorageProductTransfer::CATEGORIES];
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
