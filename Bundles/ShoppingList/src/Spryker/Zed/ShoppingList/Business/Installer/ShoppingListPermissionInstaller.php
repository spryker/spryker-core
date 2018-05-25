<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Installer;

use Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer;
use Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToPermissionFacadeInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface;
use Spryker\Zed\ShoppingList\ShoppingListConfig;

class ShoppingListPermissionInstaller implements ShoppingListPermissionInstallerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ShoppingList\ShoppingListConfig
     */
    protected $shoppingListConfig;

    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface
     */
    protected $shoppingListEntityManager;

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToPermissionFacadeInterface
     */
    protected $permissionFacade;

    /**
     * @param \Spryker\Zed\ShoppingList\ShoppingListConfig $shoppingListConfig
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface $shoppingListEntityManager
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToPermissionFacadeInterface $permissionFacade
     */
    public function __construct(ShoppingListConfig $shoppingListConfig, ShoppingListEntityManagerInterface $shoppingListEntityManager, ShoppingListToPermissionFacadeInterface $permissionFacade)
    {
        $this->shoppingListConfig = $shoppingListConfig;
        $this->shoppingListEntityManager = $shoppingListEntityManager;
        $this->permissionFacade = $permissionFacade;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $this->getTransactionHandler()->handleTransaction(function () {
            $this->executeInstallTransaction();
        });
    }

    /**
     * @return void
     */
    protected function executeInstallTransaction(): void
    {
        $this->permissionFacade->syncPermissionPlugins();
        $shoppingListPermissionGroupTransfers = $this->shoppingListConfig->getShoppingListPermissionGroups();

        foreach ($shoppingListPermissionGroupTransfers as $shoppingListPermissionGroupTransfer) {
            $shoppingListPermissionGroupEntityTransfer = $this->createShoppingListPermissionGroupEntityTransfer($shoppingListPermissionGroupTransfer);

            $shoppingListPermissionGroupEntityTransfer = $this->shoppingListEntityManager->saveShoppingListPermissionGroup($shoppingListPermissionGroupEntityTransfer);

            foreach ($shoppingListPermissionGroupTransfer->getPermissions() as $permissionTransfer) {
                $this->shoppingListEntityManager->saveShoppingListPermissionGroupToPermission(
                    $shoppingListPermissionGroupEntityTransfer,
                    $this->permissionFacade->findPermissionByKey($permissionTransfer->getKey())
                );
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer $shoppingListPermissionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListPermissionGroupEntityTransfer
     */
    protected function createShoppingListPermissionGroupEntityTransfer(ShoppingListPermissionGroupTransfer $shoppingListPermissionGroupTransfer): SpyShoppingListPermissionGroupEntityTransfer
    {
        return (new SpyShoppingListPermissionGroupEntityTransfer())->fromArray($shoppingListPermissionGroupTransfer->modifiedToArray(), true);
    }
}
