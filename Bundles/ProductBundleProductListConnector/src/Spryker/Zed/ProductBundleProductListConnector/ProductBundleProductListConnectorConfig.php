<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductBundleProductListConnectorConfig extends AbstractBundleConfig
{
    /**
     * @uses \Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap::COL_TYPE_BLACKLIST
     */
    protected const PRODUCT_LIST_TYPE_BLACKLIST = 'blacklist';

    /**
     * @return string
     */
    public function getProductListTypeBlacklist(): string
    {
        return static::PRODUCT_LIST_TYPE_BLACKLIST;
    }
}
