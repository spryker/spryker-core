<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Plugin\ProductCategoryStorage;

use Generated\Shared\Transfer\ProductAbstractCategoryStorageCollectionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductCategoryStorageExtension\Dependency\Plugin\ProductAbstractCategoryStorageCollectionExpanderPluginInterface;

/**
 * @method \Spryker\Client\CategoryStorage\CategoryStorageFactory getFactory()
 * @method \Spryker\Client\CategoryStorage\CategoryStorageClientInterface getClient()
 */
class ParentCategoryIdsProductAbstractCategoryStorageCollectionExpanderPlugin extends AbstractPlugin implements ProductAbstractCategoryStorageCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ProductAbstractCategoryStorageCollectionTransfer.productAbstractCategory.category.categoryNodeId` to be set.
     * - Expands product categories with their parent category ids.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageCollectionTransfer $productAbstractCategoryStorageCollectionTransfer
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageCollectionTransfer
     */
    public function expand(
        ProductAbstractCategoryStorageCollectionTransfer $productAbstractCategoryStorageCollectionTransfer,
        string $localeName,
        string $storeName
    ): ProductAbstractCategoryStorageCollectionTransfer {
        return $this->getClient()->expandProductCategoriesWithParentIds($productAbstractCategoryStorageCollectionTransfer, $localeName, $storeName);
    }
}
