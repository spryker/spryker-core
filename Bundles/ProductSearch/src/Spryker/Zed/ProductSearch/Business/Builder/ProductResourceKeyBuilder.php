<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Builder;

use Spryker\Shared\Collector\Code\KeyBuilder\SharedResourceKeyBuilder;
use Spryker\Shared\ProductSearch\ProductSearchConstants;

class ProductResourceKeyBuilder extends SharedResourceKeyBuilder
{

    /**
     * @return string
     */
    protected function getResourceType()
    {
        return ProductSearchConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT;
    }

}
