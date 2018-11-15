<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession\Storage;

use Generated\Shared\Transfer\ShoppingListSessionTransfer;

interface ShoppingListSessionStorageInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListSessionTransfer $shoppingListSessionTransfer
     *
     * @return void
     */
    public function setShoppingListCollection(ShoppingListSessionTransfer $shoppingListSessionTransfer): void;

    /**
     * @return \Generated\Shared\Transfer\ShoppingListSessionTransfer|null
     */
    public function findShoppingListCollection(): ?ShoppingListSessionTransfer;
}
