<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession\ShoppingListSessionPluginsExecutor;

use Generated\Shared\Transfer\ShoppingListSessionTransfer;

class ShoppingListSessionPluginsExecutor implements ShoppingListSessionPluginsExecutorInterface
{
    /**
     * @var \Spryker\Client\ShoppingListSessionExtension\Dependency\Plugin\ShoppingListCollectionOutdatedPluginInterface[]
     */
    protected $shoppingListCollectionOutdatedPlugins;

    /**
     * @param \Spryker\Client\ShoppingListSessionExtension\Dependency\Plugin\ShoppingListCollectionOutdatedPluginInterface[] $shoppingListCollectionOutdatedPlugins
     */
    public function __construct(array $shoppingListCollectionOutdatedPlugins)
    {
        $this->shoppingListCollectionOutdatedPlugins = $shoppingListCollectionOutdatedPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListSessionTransfer $shoppingListSessionTransfer
     *
     * @return bool
     */
    public function executeCollectionOutdatedPlugins(ShoppingListSessionTransfer $shoppingListSessionTransfer): bool
    {
        foreach ($this->shoppingListCollectionOutdatedPlugins as $shoppingListCollectionOutdatedPlugin) {
            if ($shoppingListCollectionOutdatedPlugin->isCollectionOutdated($shoppingListSessionTransfer)) {
                return true;
            }
        }

        return false;
    }
}
