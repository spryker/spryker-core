<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
