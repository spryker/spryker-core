<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingList\Business\ShoppingList;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShoppingListTransfer;
use SprykerTest\Zed\ShoppingList\ShoppingListBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShoppingList
 * @group Business
 * @group ShoppingList
 * @group FindCompanyUserPermissionsTest
 * Add your own group annotations below this line
 */
class FindCompanyUserPermissionsTest extends Unit
{
    /**
     * @uses \Spryker\Shared\ShoppingList\ShoppingListConfig::READ_SHOPPING_LIST_PERMISSION_PLUGIN_KEY
     *
     * @var string
     */
    protected const READ_SHOPPING_LIST_PERMISSION_PLUGIN_KEY = 'ReadShoppingListPermissionPlugin';

    /**
     * @uses \Spryker\Shared\ShoppingList\ShoppingListConfig::WRITE_SHOPPING_LIST_PERMISSION_PLUGIN_KEY
     *
     * @var string
     */
    protected const WRITE_SHOPPING_LIST_PERMISSION_PLUGIN_KEY = 'WriteShoppingListPermissionPlugin';

    /**
     * @uses \Spryker\Shared\ShoppingList\ShoppingListConfig::PERMISSION_CONFIG_ID_SHOPPING_LIST_COLLECTION
     *
     * @var string
     */
    protected const PERMISSION_CONFIG_ID_SHOPPING_LIST_COLLECTION = 'id_shopping_list_collection';

    /**
     * @var int
     */
    protected const COMPANY_USER_ID_INVALID = 0;

    /**
     * @var \SprykerTest\Zed\ShoppingList\ShoppingListBusinessTester
     */
    protected ShoppingListBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldReturnEmptyCompanyUserPermissionCollectionWhileCompanyUserIsUnknown(): void
    {
        // Act
        $permissionCollectionTransfer = $this->tester
            ->getFacade()
            ->findCompanyUserPermissions(static::COMPANY_USER_ID_INVALID);

        // Assert
        $this->assertCount(0, $permissionCollectionTransfer->getPermissions());
    }

    /**
     * @return void
     */
    public function testShouldReturnWriteAndReadPermissionForShoppingListOwner(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserForBusinessUnit();
        $shoppingListTransfer = $this->tester->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => $companyUserTransfer->getCustomerOrFail()->getCustomerReference(),
            ShoppingListTransfer::ID_COMPANY_USER => $companyUserTransfer->getIdCompanyUser(),
        ]);

        // Act
        $permissionCollectionTransfer = $this->tester
            ->getFacade()
            ->findCompanyUserPermissions($companyUserTransfer->getIdCompanyUser());

        // Assert
        $this->assertCount(2, $permissionCollectionTransfer->getPermissions());

        /** @var \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer */
        $permissionTransfer = $permissionCollectionTransfer->getPermissions()->offsetGet(0);
        $this->assertSame(static::READ_SHOPPING_LIST_PERMISSION_PLUGIN_KEY, $permissionTransfer->getKey());
        $this->assertContains(
            $shoppingListTransfer->getIdShoppingList(),
            $permissionTransfer->getConfiguration()[static::PERMISSION_CONFIG_ID_SHOPPING_LIST_COLLECTION],
        );

        /** @var \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer */
        $permissionTransfer = $permissionCollectionTransfer->getPermissions()->offsetGet(1);
        $this->assertSame(static::WRITE_SHOPPING_LIST_PERMISSION_PLUGIN_KEY, $permissionTransfer->getKey());
        $this->assertContains(
            $shoppingListTransfer->getIdShoppingList(),
            $permissionTransfer->getConfiguration()[static::PERMISSION_CONFIG_ID_SHOPPING_LIST_COLLECTION],
        );
    }
}
