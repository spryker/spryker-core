<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupCollector\Business\Collector\Storage;

use Generated\Shared\Transfer\ProductAbstractGroupsTransfer;
use Spryker\Shared\ProductGroup\ProductGroupConfig;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;
use Spryker\Zed\ProductGroupCollector\Persistence\Collector\Propel\ProductAbstractGroupsCollectorQuery;

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
        $productAbstractGroupsTransfer
            ->setIdProductAbstract($collectItemData[ProductAbstractGroupsCollectorQuery::FIELD_ID_PRODUCT_ABSTRACT])
            ->setIdProductGroups(explode(',', $collectItemData[ProductAbstractGroupsCollectorQuery::FIELD_ID_PRODUCT_GROUPS]));

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
