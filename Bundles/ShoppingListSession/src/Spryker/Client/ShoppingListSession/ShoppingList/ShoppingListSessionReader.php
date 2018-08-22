<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession\ShoppingList;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListSessionTransfer;
use Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToShoppingListClientBridgeInterface;
use Spryker\Client\ShoppingListSession\ShoppingListSessionPluginsExecutor\ShoppingListSessionPluginsExecutorInterface;
use Spryker\Client\ShoppingListSession\Storage\ShoppingListSessionStorageInterface;

class ShoppingListSessionReader implements ShoppingListSessionReaderInterface
{
    /**
     * @var \Spryker\Client\ShoppingListSession\Storage\ShoppingListSessionStorageInterface
     */
    protected $shoppingListSessionStorage;

    /**
     * @var \Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToShoppingListClientBridgeInterface
     */
    protected $shoppingListClient;

    /**
     * @var \Spryker\Client\ShoppingListSession\ShoppingListSessionPluginsExecutor\ShoppingListSessionPluginsExecutorInterface
     */
    protected $shoppingListSessionPluginsExecutor;

    /**
     * @param \Spryker\Client\ShoppingListSession\Storage\ShoppingListSessionStorageInterface $shoppingListSessionStorage
     * @param \Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToShoppingListClientBridgeInterface $shoppingListClient
     * @param \Spryker\Client\ShoppingListSession\ShoppingListSessionPluginsExecutor\ShoppingListSessionPluginsExecutorInterface $shoppingListSessionPluginsExecutor
     */
    public function __construct(
        ShoppingListSessionStorageInterface $shoppingListSessionStorage,
        ShoppingListSessionToShoppingListClientBridgeInterface $shoppingListClient,
        ShoppingListSessionPluginsExecutorInterface $shoppingListSessionPluginsExecutor
    ) {
        $this->shoppingListSessionStorage = $shoppingListSessionStorage;
        $this->shoppingListClient = $shoppingListClient;
        $this->shoppingListSessionPluginsExecutor = $shoppingListSessionPluginsExecutor;
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingListCollection(): ShoppingListCollectionTransfer
    {
        $shoppingListSessionTransfer = $this->shoppingListSessionStorage->findShoppingListCollection();
        if ($this->needUpdateShoppingListSession($shoppingListSessionTransfer)) {
            $shoppingListSessionTransfer = $this->doUpdateShoppingListSession();
        }

        return $shoppingListSessionTransfer->getShoppingLists();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListSessionTransfer|null $shoppingListSessionTransfer
     *
     * @return bool
     */
    protected function needUpdateShoppingListSession(?ShoppingListSessionTransfer $shoppingListSessionTransfer): bool
    {
        return (
            !$shoppingListSessionTransfer
            || $this->shoppingListSessionPluginsExecutor->executeCollectionOutdatedPlugins($shoppingListSessionTransfer)
        );
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListSessionTransfer
     */
    protected function doUpdateShoppingListSession(): ShoppingListSessionTransfer
    {
        $customerShoppingListCollectionTransfer = $this->shoppingListClient->getCustomerShoppingListCollection();
        $shoppingListSessionTransfer = (new ShoppingListSessionTransfer())
            ->setUpdatedAt(time())
            ->setShoppingLists($customerShoppingListCollectionTransfer);
        $this->shoppingListSessionStorage->setShoppingListCollection($shoppingListSessionTransfer);

        return $shoppingListSessionTransfer;
    }
}
