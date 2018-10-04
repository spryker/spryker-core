<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession\Dependency\Client;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;

class ShoppingListSessionToShoppingListClientBridge implements ShoppingListSessionToShoppingListClientBridgeInterface
{
    /**
     * @var \Spryker\Client\ShoppingList\ShoppingListClientInterface
     */
    protected $shoppingListClient;

    /**
     * @param \Spryker\Client\ShoppingList\ShoppingListClientInterface $shoppingListClient
     */
    public function __construct($shoppingListClient)
    {
        $this->shoppingListClient = $shoppingListClient;
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingListCollection(): ShoppingListCollectionTransfer
    {
        return $this->shoppingListClient->getCustomerShoppingListCollection();
    }
}
