<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterCollector\Business\Collector\Storage;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Shared\ProductCategoryFilter\ProductCategoryFilterConfig;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;

class ProductCategoryFilterCollector extends AbstractStoragePropelCollector
{
    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return ProductCategoryFilterConfig::RESOURCE_TYPE_PRODUCT_CATEGORY_FILTER;
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        return $collectItemData[ProductCategoryFilterTransfer::FILTER_DATA];
    }

    /**
     * @return bool
     */
    protected function isStorageTableJoinWithLocaleEnabled()
    {
        return true;
    }
}
