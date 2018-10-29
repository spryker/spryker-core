<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Communication\Plugin\ShoppingList;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemBeforeDeletePluginInterface;

/**
 * @method \Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOptionConnectorFacadeInterface getFacade()
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
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function execute(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        $this->getFacade()
            ->removeShoppingListItemProductOptions($shoppingListItemTransfer->getIdShoppingListItem());

        return $shoppingListItemTransfer;
    }
}
