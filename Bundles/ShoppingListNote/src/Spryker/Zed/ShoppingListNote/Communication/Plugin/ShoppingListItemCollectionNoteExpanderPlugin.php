<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListNote\Communication\Plugin;

use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemCollectionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ShoppingListNote\Business\ShoppingListNoteFacadeInterface getFacade()
 * @method \Spryker\Zed\ShoppingListNote\ShoppingListNoteConfig getConfig()
 */
class ShoppingListItemCollectionNoteExpanderPlugin extends AbstractPlugin implements ShoppingListItemCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Populates shopping list items notes
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function expandItemCollection(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        return $this->getFacade()->expandShoppingListItemCollection($shoppingListItemCollectionTransfer);
    }
}
