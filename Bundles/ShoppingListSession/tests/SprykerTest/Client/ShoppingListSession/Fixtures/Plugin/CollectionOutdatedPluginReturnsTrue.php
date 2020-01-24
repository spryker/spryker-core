<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ShoppingListSession\Fixtures\Plugin;

use Generated\Shared\Transfer\ShoppingListSessionTransfer;
use Spryker\Client\ShoppingListSessionExtension\Dependency\Plugin\ShoppingListCollectionOutdatedPluginInterface;

class CollectionOutdatedPluginReturnsTrue implements ShoppingListCollectionOutdatedPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\ShoppingListSessionTransfer $shoppingListSession
     *
     * @return bool
     */
    public function isCollectionOutdated(ShoppingListSessionTransfer $shoppingListSession): bool
    {
        return true;
    }
}
