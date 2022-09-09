<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ShoppingListTransfer;

/**
 * Allows to expand shopping list with additional data before saving to Persistence.
 */
interface ShoppingListExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands shopping list with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function expand(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer;
}
