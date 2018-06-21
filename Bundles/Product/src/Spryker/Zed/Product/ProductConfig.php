<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductConfig extends AbstractBundleConfig
{
    public const FILTERED_PRODUCTS_LIMIT_DEFAULT = 10;

    /**
     * @return int
     */
    public function getFilteredProductsLimitDefault(): int
    {
        return $this->get('FILTERED_PRODUCTS_LIMIT_DEFAULT', static::FILTERED_PRODUCTS_LIMIT_DEFAULT);
    }
}
