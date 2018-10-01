<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListStorage\Dependency\Plugin\ShoppingListSession;

use Generated\Shared\Transfer\ShoppingListSessionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ShoppingListSessionExtension\Dependency\Plugin\ShoppingListCollectionOutdatedPluginInterface;

/**
 * @method \Spryker\Client\ShoppingListStorage\ShoppingListStorageClientInterface getClient()
 * @method \Spryker\Client\ShoppingListStorage\ShoppingListStorageFactory getFactory()
 */
class ShoppingListCollectionOutdatedPlugin extends AbstractPlugin implements ShoppingListCollectionOutdatedPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListSessionTransfer $shoppingListSession
     *
     * @return bool
     */
    public function isCollectionOutdated(ShoppingListSessionTransfer $shoppingListSession): bool
    {
        return $this->getClient()->isShoppingListCollectionOutdated($shoppingListSession);
    }
}
