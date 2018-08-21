<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession\ShoppingListSessionPluginsExecutor;

use Generated\Shared\Transfer\ShoppingListSessionTransfer;

interface ShoppingListSessionPluginsExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListSessionTransfer $shoppingListSessionTransfer
     *
     * @return bool
     */
    public function executeCollectionOutdatedPlugins(ShoppingListSessionTransfer $shoppingListSessionTransfer): bool;
}
