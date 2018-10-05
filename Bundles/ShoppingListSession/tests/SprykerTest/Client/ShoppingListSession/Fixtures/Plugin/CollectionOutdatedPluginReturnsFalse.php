<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ShoppingListSession\Fixtures\Plugin;

use Generated\Shared\Transfer\ShoppingListSessionTransfer;
use Spryker\Client\ShoppingListSessionExtension\Dependency\Plugin\ShoppingListCollectionOutdatedPluginInterface;

class CollectionOutdatedPluginReturnsFalse implements ShoppingListCollectionOutdatedPluginInterface
{
    /**
     * {@inheritDoc}
     */
    public function isCollectionOutdated(ShoppingListSessionTransfer $shoppingListSession): bool
    {
        return false;
    }
}
