<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;

interface ShoppingListItemCollectionExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands a {@link \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer} with additional data fields.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function expandItemCollection(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): ShoppingListItemCollectionTransfer;
}
