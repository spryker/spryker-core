<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade;

use Generated\Shared\Transfer\ShoppingListItemTransfer;

class ShoppingListProductOptionConnectorToShoppingListFacadeBridge implements ShoppingListProductOptionConnectorToShoppingListFacadeInterface
{
    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface
     */
    protected $shoppingListFacade;

    /**
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade
     */
    public function __construct($shoppingListFacade)
    {
        $this->shoppingListFacade = $shoppingListFacade;
    }

    /**
     * @param int $idShoppingListItem
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function getShoppingListItemById(int $idShoppingListItem): ShoppingListItemTransfer
    {
        return $this->shoppingListFacade->getShoppingListItemById($idShoppingListItem);
    }
}
