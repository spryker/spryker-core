<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListStorage\KeyBuilder;

use Spryker\Shared\KeyBuilder\SharedResourceKeyBuilder;
use Spryker\Shared\ShoppingListStorage\ShoppingListStorageConfig;

class ShoppingListStorageKeyBuilder extends SharedResourceKeyBuilder
{
    /**
     * @return string
     */
    protected function getResourceType(): string
    {
        return ShoppingListStorageConfig::RESOURCE_TYPE_SHOPPING_LIST;
    }
}
