<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ShoppingListConfig extends AbstractBundleConfig
{
    public const DEFAULT_SHOPPING_LIST_NAME = 'Default Name';

    /**
     * @return string
     */
    public function getDefaultShoppingListName(): string
    {
        return static::DEFAULT_SHOPPING_LIST_NAME;
    }
}
