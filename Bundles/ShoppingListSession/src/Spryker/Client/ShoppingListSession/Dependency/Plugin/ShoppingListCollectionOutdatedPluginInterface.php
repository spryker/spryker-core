<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession\Dependency\Plugin;

use Generated\Shared\Transfer\ShoppingListSessionTransfer;

interface ShoppingListCollectionOutdatedPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListSessionTransfer $shoppingListSession
     *
     * @return bool
     */
    public function isCollectionOutdated(ShoppingListSessionTransfer $shoppingListSession): bool;
}
