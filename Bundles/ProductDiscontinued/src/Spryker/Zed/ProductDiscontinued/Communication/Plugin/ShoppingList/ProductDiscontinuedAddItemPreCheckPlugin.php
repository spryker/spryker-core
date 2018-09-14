<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Communication\Plugin\ShoppingList;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\AddItemPreCheckPluginInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinued\Communication\ProductDiscontinuedCommunicationFactory getFactory()
 */
class ProductDiscontinuedAddItemPreCheckPlugin extends AbstractPlugin implements AddItemPreCheckPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function check(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListPreAddItemCheckResponseTransfer
    {
        return $this->getFacade()->checkShoppingListItemProductIsNotDiscontinued($shoppingListItemTransfer);
    }
}
