<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Communication\Plugin;

use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemBulkPostSavePluginInterface;

/**
 * @method \Spryker\Zed\ShoppingListNote\Business\ShoppingListNoteFacadeInterface getFacade()
 * @method \Spryker\Zed\ShoppingListNote\ShoppingListNoteConfig getConfig()
 */
class ShoppingListItemNoteBulkPostSavePlugin extends AbstractPlugin implements ShoppingListItemBulkPostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Creates, updates or deletes note for shopping list item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemTransfers
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function execute(ShoppingListItemCollectionTransfer $shoppingListItemTransfers): ShoppingListItemCollectionTransfer
    {
        return $this->getFacade()->saveShoppingListItemNotesForShoppingListItemCollection($shoppingListItemTransfers);
    }
}
