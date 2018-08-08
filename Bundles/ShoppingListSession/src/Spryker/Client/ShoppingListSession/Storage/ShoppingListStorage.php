<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession\Storage;

use Generated\Shared\Transfer\ShoppingListSessionTransfer;
use Spryker\Client\Session\SessionClientInterface;

class ShoppingListStorage implements ShoppingListStorageInterface
{
    const SESSION_KEY_SHOPPING_LIST_COLLECTION = 'SESSION_KEY_SHOPPING_LIST_COLLECTION';

    /**
     * @var \Spryker\Client\ShoppingListSession\Storage\ShoppingListStorageInterface
     */
    protected $shoppingListStorage;

    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    protected $sessionClient;

    /**
     * ShoppingListStorage constructor.
     *
     * @param \Spryker\Client\ShoppingListSession\Storage\ShoppingListStorageInterface $shoppingListStorage
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     */
    public function __construct(
        ShoppingListStorageInterface $shoppingListStorage,
        SessionClientInterface $sessionClient
    ) {
        $this->shoppingListStorage = $shoppingListStorage;
        $this->sessionClient = $sessionClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListSessionTransfer $shoppingListSessionTransfer
     *
     * @return void
     */
    public function setShoppingListCollection(ShoppingListSessionTransfer $shoppingListSessionTransfer): void
    {
        $this->sessionClient->set(static::SESSION_KEY_SHOPPING_LIST_COLLECTION, $shoppingListSessionTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListSessionTransfer|null
     */
    public function getShoppingListCollection(): ?ShoppingListSessionTransfer
    {
        return $this->sessionClient->get(static::SESSION_KEY_SHOPPING_LIST_COLLECTION/*, new ShoppingListCollectionTransfer()*/);
    }
}
