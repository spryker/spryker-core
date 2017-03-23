<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelation\KeyBuilder;

use Spryker\Shared\KeyBuilder\SharedResourceKeyBuilder;
use Spryker\Zed\ProductRelation\ProductRelationConfig;

class ProductRelationKeyBuilder extends SharedResourceKeyBuilder
{

    /**
     * @return string
     */
    protected function getResourceType()
    {
        return ProductRelationConfig::RESOURCE_TYPE_PRODUCT_RELATION;
    }

}
