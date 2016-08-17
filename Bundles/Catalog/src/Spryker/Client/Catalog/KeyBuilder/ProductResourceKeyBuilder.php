<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\KeyBuilder;

use Spryker\Shared\Catalog\CatalogConstants;
use Spryker\Shared\Collector\Code\KeyBuilder\SharedResourceKeyBuilder;

/**
 * @deprecated See \Spryker\Client\Catalog\Model\Catalog for more info.
 */
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
