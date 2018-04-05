<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList;

use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer;
use Spryker\Shared\ShoppingList\ShoppingListConfig as SharedConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\ShoppingList\Communication\Plugin\ReadShoppingListPermissionPlugin;

class ShoppingListConfig extends AbstractBundleConfig
{
    protected const DEFAULT_SHOPPING_LIST_NAME = 'Shopping List';

    /**
     * @return string
     */
    public function getDefaultShoppingListName(): string
    {
        return static::DEFAULT_SHOPPING_LIST_NAME;
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer[]
     */
    public function getShoppingListPermissionGroups(): array
    {
        return [
            $this->getReadOnlyPermissionGroup(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    protected function getReadOnlyPermissionGroup(): ShoppingListPermissionGroupTransfer
    {
        $readOnlyQuotePermissionGroupTransfer = new ShoppingListPermissionGroupTransfer();
        $readOnlyQuotePermissionGroupTransfer
            ->setName(SharedConfig::PERMISSION_GROUP_READ_ONLY)
            ->addPermission((new PermissionTransfer())->setKey(ReadShoppingListPermissionPlugin::KEY));

        return $readOnlyQuotePermissionGroupTransfer;
    }
}
