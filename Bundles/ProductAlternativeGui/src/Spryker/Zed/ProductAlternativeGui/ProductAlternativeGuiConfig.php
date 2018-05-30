<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductAlternativeGuiConfig extends AbstractBundleConfig
{
    protected const FILTERED_PRODUCTS_LIMIT_DEFAULT = 10;

    /**
     * @return int
     */
    public function getFilteredProductsLimitDefault(): int
    {
        return static::FILTERED_PRODUCTS_LIMIT_DEFAULT;
    }
}
