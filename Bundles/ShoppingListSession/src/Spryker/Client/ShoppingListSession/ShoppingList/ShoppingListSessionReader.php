<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession\ShoppingList;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListSessionTransfer;
use Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToShoppingListBridgeInterface;
use Spryker\Client\ShoppingListSession\Storage\ShoppingListSessionStorageInterface;

class ShoppingListSessionReader implements ShoppingListSessionReaderInterface
{
    /**
     * @var \Spryker\Client\ShoppingListSession\Storage\ShoppingListSessionStorageInterface
     */
    protected $shoppingListStorage;

    /**
     * @var \Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToShoppingListBridgeInterface
     */
    protected $shoppingListClient;

    /**
     * @var array|\Spryker\Client\ShoppingListSession\Dependency\Plugin\ShoppingListCollectionOutdatedPluginInterface[]
     */
    protected $shoppingListCollectionOutdatedPlugins;

    /**
     * @param \Spryker\Client\ShoppingListSession\Storage\ShoppingListSessionStorageInterface $shoppingListStorage
     * @param \Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToShoppingListBridgeInterface $shoppingListClient
     * @param \Spryker\Client\ShoppingListSession\Dependency\Plugin\ShoppingListCollectionOutdatedPluginInterface[] $shoppingListCollectionOutdatedPlugins
     */
    public function __construct(
        ShoppingListSessionStorageInterface $shoppingListStorage,
        ShoppingListSessionToShoppingListBridgeInterface $shoppingListClient,
        array $shoppingListCollectionOutdatedPlugins
    ) {
        $this->shoppingListStorage = $shoppingListStorage;
        $this->shoppingListClient = $shoppingListClient;
        $this->shoppingListCollectionOutdatedPlugins = $shoppingListCollectionOutdatedPlugins;
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingListCollection()
    {
        $shoppingListSessionTransfer = $this->shoppingListStorage->getShoppingListCollection();

        if ($shoppingListSessionTransfer === null || $this->isCollectionOutdated($shoppingListSessionTransfer) === true) {
            $customerShoppingListCollectionTransfer = $this->shoppingListClient->getCustomerShoppingListCollection();
            if (!$customerShoppingListCollectionTransfer) {
                return new ShoppingListCollectionTransfer();
            }
            $shoppingListSessionTransfer = (new ShoppingListSessionTransfer())
                ->setUpdatedAt(time())
                ->setShoppingLists($customerShoppingListCollectionTransfer);
            $this->shoppingListStorage->setShoppingListCollection($shoppingListSessionTransfer);
        }

        return $shoppingListSessionTransfer->getShoppingLists();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListSessionTransfer|null $shoppingListSessionTransfer
     *
     * @return bool
     */
    protected function isCollectionOutdated(?ShoppingListSessionTransfer $shoppingListSessionTransfer = null): bool
    {
        if (!$shoppingListSessionTransfer) {
            return true;
        }
        foreach ($this->shoppingListCollectionOutdatedPlugins as $shoppingListCollectionOutdatedPlugin) {
            if ($shoppingListCollectionOutdatedPlugin->isCollectionOutdated($shoppingListSessionTransfer)) {
                return true;
            }
        }

        return false;
    }
}
