<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Dependency\Facade;

use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

class ShoppingListsRestApiToShoppingListFacadeBridge implements ShoppingListsRestApiToShoppingListFacadeInterface
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
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function findShoppingListByUuid(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->shoppingListFacade->findShoppingListByUuid($shoppingListTransfer);
    }
}
