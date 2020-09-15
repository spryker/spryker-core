<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductMerchantPortalGuiConfig extends AbstractBundleConfig
{
    protected const MAIN_CATEGORY_ID = 1;

    /**
     * Specification:
     * - Defines main category ID which is used as a starting point for category tree building.
     *
     * @api
     *
     * @return int
     */
    public function getMainCategoryIdForCategoryFilter(): int
    {
        return static::MAIN_CATEGORY_ID;
    }
}
