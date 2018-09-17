<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOption\Communication\Plugin\ShoppingList;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemBeforeDeletePluginInterface;

/**
 * @method \Spryker\Zed\ShoppingListProductOption\Business\ShoppingListProductOptionFacadeInterface getFacade()
 */
class ShoppingListItemProductOptionBeforeDeletePlugin extends AbstractPlugin implements ShoppingListItemBeforeDeletePluginInterface
{
    /**
     * {@inheritdoc}
     * - Removes product options from list item before delete.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function execute(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $this->getFacade()
            ->removeShoppingListItemProductOptions($shoppingListItemTransfer);
    }
}
