<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Communication\Controller;

use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\RestShoppingListItemResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemResponseTransfer
     */
    public function addItemAction(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): RestShoppingListItemResponseTransfer {
        return $this->getFacade()->addItem($restShoppingListItemRequestTransfer);
    }
}
