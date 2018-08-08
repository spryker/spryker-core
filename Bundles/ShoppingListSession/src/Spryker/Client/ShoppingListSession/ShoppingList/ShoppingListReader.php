<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession\ShoppingList;

use Generated\Shared\Transfer\ShoppingListSessionTransfer;
use Spryker\Client\ShoppingList\ShoppingListClientInterface;
use Spryker\Client\ShoppingListSession\Storage\ShoppingListStorageInterface;

class ShoppingListReader
{
    /**
     * @var \Spryker\Client\ShoppingListSession\Storage\ShoppingListStorageInterface
     */
    protected $shoppingListStorage;

    /**
     * @var \Spryker\Client\ShoppingList\ShoppingListClientInterface
     */
    protected $shoppingListClient;

    /**
     * @var array|\Spryker\Client\ShoppingListSession\Dependency\Plugin\ShoppingListCollectionOutdatedPluginInterface[]
     */
    protected $shoppingListCollectionOutdatedPlugins;

    /**
     * @param \Spryker\Client\ShoppingListSession\Storage\ShoppingListStorageInterface $shoppingListStorage
     * @param \Spryker\Client\ShoppingList\ShoppingListClientInterface $shoppingListClient
     * @param \Spryker\Client\ShoppingListSession\Dependency\Plugin\ShoppingListCollectionOutdatedPluginInterface[] $shoppingListCollectionOutdatedPlugins
     */
    public function __construct(
        ShoppingListStorageInterface $shoppingListStorage,
        ShoppingListClientInterface $shoppingListClient,
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

        if ($shoppingListSessionTransfer === null || $this->isCollectionOutdated($shoppingListSessionTransfer)) {
            $customerShoppingListCollectionTransfer = $this->shoppingListClient->getCustomerShoppingListCollection();
            $shoppingListSessionTransfer = (new ShoppingListSessionTransfer())
                ->setUpdatedAt(time())
                ->setShoppingLists($customerShoppingListCollectionTransfer);
            $this->shoppingListStorage->setShoppingListCollection($shoppingListSessionTransfer);
        }

        return $shoppingListSessionTransfer->getShoppingLists();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListSessionTransfer $shoppingListSessionTransfer
     *
     * @return bool
     */
    protected function isCollectionOutdated(ShoppingListSessionTransfer $shoppingListSessionTransfer)
    {
        foreach ($this->shoppingListCollectionOutdatedPlugins as $shoppingListCollectionOutdatedPlugin) {
            if ($shoppingListCollectionOutdatedPlugin->isCollectionOutdated($shoppingListSessionTransfer)) {
                return true;
            }
        }

        return false;
    }
}
