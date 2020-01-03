<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProduct;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ContentProductConfig extends AbstractBundleConfig
{
    protected const MAX_NUMBER_PRODUCTS_IN_PRODUCT_ABSTRACT_LIST = 20;

    /**
     * @return int
     */
    public function getMaxProductsInProductAbstractList(): int
    {
        return static::MAX_NUMBER_PRODUCTS_IN_PRODUCT_ABSTRACT_LIST;
    }
}
