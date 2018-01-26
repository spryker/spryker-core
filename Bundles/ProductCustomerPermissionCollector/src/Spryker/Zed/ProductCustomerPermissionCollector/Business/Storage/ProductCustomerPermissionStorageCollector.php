<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermissionCollector\Business\Storage;

use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;
use Spryker\Zed\ProductCustomerPermission\ProductCustomerPermissionConfig;
use Spryker\Zed\ProductCustomerPermissionCollector\Persistence\Search\Propel\ProductCustomerPermissionSearchCollectorQuery;

class ProductCustomerPermissionStorageCollector extends AbstractStoragePropelCollector
{
    protected function collectResourceType()
    {
        return ProductCustomerPermissionConfig::RESOURCE_TYPE_PRODUCT_CUSTOMER_PERMISSION;
    }

    protected function collectItem($touchKey, array $collectItemData)
    {
        return [];
    }

    /**
     * @param mixed $data
     * @param string $localeName
     * @param array $collectedItemData
     *
     * @return string
     */
    protected function collectKey($data, $localeName, array $collectedItemData)
    {
        $data  = $collectedItemData[ProductCustomerPermissionSearchCollectorQuery::FIELD_FK_PRODUCT_ABSTRACT]
            . '.' . $collectedItemData[ProductCustomerPermissionSearchCollectorQuery::FIELD_FK_CUSTOMER];

        return $this->generateKey($data, $localeName);
    }

    /**
     * @return bool
     */
    protected function isStorageTableJoinWithLocaleEnabled()
    {
        return true;
    }
}
