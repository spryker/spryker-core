<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList;

use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer;
use Spryker\Client\ShoppingList\Plugin\WriteShoppingListPermissionPlugin;
use Spryker\Shared\ShoppingList\ShoppingListConfig as SharedConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\ShoppingList\Communication\Plugin\ReadShoppingListPermissionPlugin;

class ShoppingListConfig extends AbstractBundleConfig
{
    protected const DEFAULT_SHOPPING_LIST_NAME = 'Shopping List';

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultShoppingListName(): string
    {
        return static::DEFAULT_SHOPPING_LIST_NAME;
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer[]
     */
    public function getShoppingListPermissionGroups(): array
    {
        return [
            $this->getReadOnlyPermissionGroup(),
            $this->getFullAccessPermissionGroup(),
        ];
    }

    /**
     * Specification:
     * - Controls whether shopping lists overview will load all shopping lists.
     *
     * @api
     *
     * @return bool
     */
    public function isShoppingListOverviewWithShoppingLists(): bool
    {
        return false;
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    protected function getReadOnlyPermissionGroup(): ShoppingListPermissionGroupTransfer
    {
        $readOnlyShoppingListPermissionGroupTransfer = new ShoppingListPermissionGroupTransfer();
        $readOnlyShoppingListPermissionGroupTransfer
            ->setName(SharedConfig::PERMISSION_GROUP_READ_ONLY)
            ->addPermission((new PermissionTransfer())->setKey(ReadShoppingListPermissionPlugin::KEY));

        return $readOnlyShoppingListPermissionGroupTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    protected function getFullAccessPermissionGroup(): ShoppingListPermissionGroupTransfer
    {
        $fullAccessQuotePermissionGroupTransfer = new ShoppingListPermissionGroupTransfer();
        $fullAccessQuotePermissionGroupTransfer
            ->setName(SharedConfig::PERMISSION_GROUP_FULL_ACCESS)
            ->addPermission((new PermissionTransfer())->setKey(ReadShoppingListPermissionPlugin::KEY))
            ->addPermission((new PermissionTransfer())->setKey(WriteShoppingListPermissionPlugin::KEY));

        return $fullAccessQuotePermissionGroupTransfer;
    }
}
