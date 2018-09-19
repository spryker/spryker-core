<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList\ShoppingList;

use Generated\Shared\Transfer\ShoppingListItemTransfer;

class ShoppingListAddItemExpander implements ShoppingListAddItemExpanderInterface
{
    /**
     * @var \Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemExpanderPluginInterface[]
     */
    protected $shoppingListItemExpanderPlugins;

    /**
     * @param \Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemExpanderPluginInterface[] $shoppingListItemExpanderPlugins
     */
    public function __construct(array $shoppingListItemExpanderPlugins)
    {
        $this->shoppingListItemExpanderPlugins = $shoppingListItemExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function expandShoppingListAddItem(ShoppingListItemTransfer $shoppingListItemTransfer, array $params): ShoppingListItemTransfer
    {
        foreach ($this->shoppingListItemExpanderPlugins as $shoppingListItemExpanderPlugin) {
            $shoppingListItemTransfer = $shoppingListItemExpanderPlugin->expand($shoppingListItemTransfer, $params);
        }

        return $shoppingListItemTransfer;
    }
}
