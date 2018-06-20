<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Model;

use Generated\Shared\Transfer\ShoppingListTransfer;

interface ShoppingListResolverInterface
{
    /**
     * @param string $customerReference
     * @param string|null $shoppingListName
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function createShoppingListIfNotExists(string $customerReference, ?string $shoppingListName = null): ShoppingListTransfer;

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function createDefaultShoppingListIfNotExists(string $customerReference): ShoppingListTransfer;
}
