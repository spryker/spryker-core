<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Communication\Plugin\ShoppingList;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\AddItemPreCheckPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProduct\Business\MerchantProductFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProduct\MerchantProductConfig getConfig()
 */
class MerchantProductAddItemPreCheckPlugin extends AbstractPlugin implements AddItemPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ShoppingListItemTransfer.merchantReference` to be set.
     * - Checks that merchant of merchant product in shopping list is active and approved.
     * - Returns `ShoppingListPreAddItemCheckResponse` transfer object with error messages.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function check(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListPreAddItemCheckResponseTransfer {
        return $this->getFacade()->checkShoppingListItem($shoppingListItemTransfer);
    }
}
