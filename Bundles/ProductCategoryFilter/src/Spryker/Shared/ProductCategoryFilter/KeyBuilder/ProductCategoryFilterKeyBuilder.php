<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductCategoryFilter\KeyBuilder;

use Spryker\Shared\KeyBuilder\SharedResourceKeyBuilder;
use Spryker\Shared\ProductCategoryFilter\ProductCategoryFilterConfig;

class ProductCategoryFilterKeyBuilder extends SharedResourceKeyBuilder
{
    /**
     * @return string
     */
    public function getResourceType()
    {
        return ProductCategoryFilterConfig::RESOURCE_TYPE_PRODUCT_CATEGORY_FILTER;
    }
}
