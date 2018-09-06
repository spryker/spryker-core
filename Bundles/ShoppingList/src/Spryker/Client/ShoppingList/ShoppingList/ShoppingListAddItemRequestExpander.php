<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList\ShoppingList;

use Generated\Shared\Transfer\ShoppingListItemTransfer;

class ShoppingListAddItemRequestExpander implements ShoppingListAddItemRequestExpanderInterface
{
    /**
     * @var \Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemRequestExpanderPluginInterface[]
     */
    protected $shoppingListItemRequestExpanderPlugins;

    /**
     * @param \Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemRequestExpanderPluginInterface[] $shoppingListItemRequestExpanderPlugins
     */
    public function __construct(array $shoppingListItemRequestExpanderPlugins)
    {
        $this->shoppingListItemRequestExpanderPlugins = $shoppingListItemRequestExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function expand(ShoppingListItemTransfer $shoppingListItemTransfer, array $params = []): ShoppingListItemTransfer
    {
        foreach ($this->shoppingListItemRequestExpanderPlugins as $shoppingListItemRequestExpanderPlugin) {
            $shoppingListItemRequestExpanderPlugin->expand($shoppingListItemTransfer, $params);
        }

        return $shoppingListItemTransfer;
    }
}
