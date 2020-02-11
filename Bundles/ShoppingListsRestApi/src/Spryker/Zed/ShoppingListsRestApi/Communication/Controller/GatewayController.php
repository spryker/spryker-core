<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer
     */
    public function getCustomerShoppingListCollectionAction(
        CustomerTransfer $customerTransfer
    ): RestShoppingListCollectionResponseTransfer {
        return $this->getFacade()->getCustomerShoppingListCollection($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function createShoppingListAction(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->getFacade()->createShoppingList($shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function updateShoppingListAction(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->getFacade()->updateShoppingList($shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function deleteShoppingListAction(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->getFacade()->deleteShoppingList($shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function addShoppingListItemAction(
        ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        return $this->getFacade()->addShoppingListItem($shoppingListItemRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function deleteShoppingListItemAction(
        ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        return $this->getFacade()->deleteShoppingListItem($shoppingListItemRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function updateShoppingListItemAction(
        ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        return $this->getFacade()->updateShoppingListItem($shoppingListItemRequestTransfer);
    }
}
