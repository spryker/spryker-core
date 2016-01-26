<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Catalog\KeyBuilder;

use Spryker\Shared\Catalog\CatalogConstants;
use Spryker\Shared\Collector\Code\KeyBuilder\SharedResourceKeyBuilder;

class ProductResourceKeyBuilder extends SharedResourceKeyBuilder
{

    /**
     * @return string
     */
    protected function getResourceType()
    {
        return CatalogConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT;
    }

}
