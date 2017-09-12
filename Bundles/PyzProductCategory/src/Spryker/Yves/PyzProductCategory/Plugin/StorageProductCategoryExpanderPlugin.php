<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PyzProductCategory\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\StorageProductCategoryTransfer;
use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\PyzProduct\Dependency\Plugin\StorageProductExpanderPluginInterface;
use Symfony\Component\HttpFoundation\Request;

class StorageProductCategoryExpanderPlugin extends AbstractPlugin implements StorageProductExpanderPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     * @param array $productData
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function expandStorageProduct(StorageProductTransfer $storageProductTransfer, array $productData, Request $request)
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
