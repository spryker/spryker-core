<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ShoppingList\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ShoppingListBuilder;
use Generated\Shared\DataBuilder\ShoppingListItemBuilder;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Orm\Zed\Permission\Persistence\SpyPermissionQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroup;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListPermissionGroupToPermission;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ShoppingListHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function haveShoppingList(array $seed = []): ShoppingListTransfer
    {
        $shoppingListTransfer = (new ShoppingListBuilder($seed))->build();

        return $this->getLocator()->shoppingList()->facade()->createShoppingList($shoppingListTransfer)->getShoppingList();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function buildShoppingListItem(array $seed = []): ShoppingListItemTransfer
    {
        return (new ShoppingListItemBuilder($seed))->build();
    }

    /**
     * @param string $name
     * @param array $permissionKeys
     *
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    public function haveShoppingListPermissionGroup($name, array $permissionKeys): ShoppingListPermissionGroupTransfer
    {
        $shoppingListPermissionGroupEntity = new SpyShoppingListPermissionGroup();
        $shoppingListPermissionGroupEntity->setName($name);

        foreach ($permissionKeys as $permissionKey) {
            $permissionEntity = SpyPermissionQuery::create()
                ->filterByKey($permissionKey)
                ->findOneOrCreate();

            $quotePermissionGroupToPermissionEntity = new SpyShoppingListPermissionGroupToPermission();
            $quotePermissionGroupToPermissionEntity
                ->setSpyPermission($permissionEntity);

            $shoppingListPermissionGroupEntity->addSpyShoppingListPermissionGroupToPermission($quotePermissionGroupToPermissionEntity);
        }

        $shoppingListPermissionGroupEntity->save();

        $shoppingListPermissionGroupTransfer = new ShoppingListPermissionGroupTransfer();
        $shoppingListPermissionGroupTransfer->fromArray($shoppingListPermissionGroupEntity->toArray(), true);

        return $shoppingListPermissionGroupTransfer;
    }
}
