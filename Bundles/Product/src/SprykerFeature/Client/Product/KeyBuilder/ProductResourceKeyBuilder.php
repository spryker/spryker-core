<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Product\KeyBuilder;

use SprykerFeature\Shared\Collector\Code\KeyBuilder\SharedResourceKeyBuilder;
use SprykerFeature\Shared\Product\ProductConstants;

class ProductResourceKeyBuilder extends SharedResourceKeyBuilder
{

    /**
     * @return string
     */
    protected function getResourceType()
    {
        return ProductConstants::RESOURCE_TYPE_ABSTRACT_PRODUCT;
    }

}
