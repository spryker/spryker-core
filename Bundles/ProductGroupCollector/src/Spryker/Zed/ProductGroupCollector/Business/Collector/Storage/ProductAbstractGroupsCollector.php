<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupCollector\Business\Collector\Storage;

use Generated\Shared\Transfer\ProductAbstractGroupsTransfer;
use Spryker\Shared\ProductGroup\ProductGroupConfig;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;

class ProductAbstractGroupsCollector extends AbstractStoragePropelCollector
{

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS;
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $productAbstractGroupsTransfer = new ProductAbstractGroupsTransfer();
        $productAbstractGroupsTransfer->fromArray($collectItemData, true);

        return $productAbstractGroupsTransfer->modifiedToArray();
    }

    /**
     * @return bool
     */
    protected function isStorageTableJoinWithLocaleEnabled()
    {
        return true;
    }

}
