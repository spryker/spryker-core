<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductSet\KeyBuilder;

use Spryker\Shared\KeyBuilder\SharedResourceKeyBuilder;
use Spryker\Shared\ProductSet\ProductSetConfig;

class ProductSetKeyBuilder extends SharedResourceKeyBuilder
{
    /**
     * @return string
     */
    protected function getResourceType()
    {
        return ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET;
    }
}
