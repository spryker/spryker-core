<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSessionExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ShoppingListSessionTransfer;

interface ShoppingListCollectionOutdatedPluginInterface
{
    /**
     * Specification:
     * - This check is applied to the ShoppingListSessionTransfer that is stored in Shopping List Session Storage.
     * - The plugins are triggered after Customer Shopping List Collection has been got from Shopping List Session
     *   Storage;
     * - If at least one plugin returns true, than shopping list collection in shopping List Session Storage
     *   will be updated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListSessionTransfer $shoppingListSession
     *
     * @return bool
     */
    public function isCollectionOutdated(ShoppingListSessionTransfer $shoppingListSession): bool;
}
