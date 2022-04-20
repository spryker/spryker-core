<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList\Remover;

use Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToSessionClientInterface;

class ShoppingListSessionRemover implements ShoppingListSessionRemoverInterface
{
    /**
     * @uses \Spryker\Client\ShoppingListSession\Storage\ShoppingListSessionSessionStorage::SESSION_KEY_SHOPPING_LIST_COLLECTION
     *
     * @var string
     */
    protected const SESSION_KEY_SHOPPING_LIST_COLLECTION = 'SESSION_KEY_SHOPPING_LIST_COLLECTION';

    /**
     * @var \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToSessionClientInterface
     */
    protected $sessionClient;

    /**
     * @param \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToSessionClientInterface $sessionClient
     */
    public function __construct(ShoppingListToSessionClientInterface $sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }

    /**
     * @return void
     */
    public function removeShoppingListCollection(): void
    {
        $this->sessionClient->remove(static::SESSION_KEY_SHOPPING_LIST_COLLECTION);
    }
}
