<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingListNote\Business\ShoppingListNoteFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ShoppingListItemNoteBuilder;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Spryker\Shared\ShoppingList\ShoppingListConfig;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use Spryker\Zed\ShoppingList\Communication\Plugin\ReadShoppingListPermissionPlugin;
use Spryker\Zed\ShoppingList\Communication\Plugin\ShoppingListPermissionStoragePlugin;
use Spryker\Zed\ShoppingList\Communication\Plugin\WriteShoppingListPermissionPlugin;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ShoppingListNote
 * @group Business
 * @group ShoppingListNoteFacade
 * @group Facade
 * @group ShoppingListNoteFacadeTest
 * Add your own group annotations below this line
 */
class ShoppingListNoteFacadeTest extends Unit
{
    use LocatorHelperTrait;

    /**
     * @var \SprykerTest\Zed\ShoppingListNote\ShoppingListNoteBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $ownerCompanyUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $otherCompanyUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected $shoppingList;

    /**
     * @var \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    protected $readOnlyPermissionGroup;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $product;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION_STORAGE, [
            new ShoppingListPermissionStoragePlugin(),
        ]);

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION, [
            new ReadShoppingListPermissionPlugin(),
            new WriteShoppingListPermissionPlugin(),
        ]);

        $this->tester->getLocator()->permission()->facade()->syncPermissionPlugins();

        $this->readOnlyPermissionGroup = $this->tester
            ->haveShoppingListPermissionGroup(ShoppingListConfig::PERMISSION_GROUP_READ_ONLY, [
                WriteShoppingListPermissionPlugin::KEY,
            ]);

        $this->tester->haveShoppingListPermissionGroup(ShoppingListConfig::PERMISSION_GROUP_FULL_ACCESS, [
            ReadShoppingListPermissionPlugin::KEY,
            WriteShoppingListPermissionPlugin::KEY,
        ]);

        $companyTransfer = $this->tester->createCompany();
        $companyTransferBusinessUnit = $this->tester->createCompanyBusinessUnit($companyTransfer);

        $ownerCustomerTransfer = $this->tester->haveCustomer();
        $this->ownerCompanyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $ownerCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyTransferBusinessUnit->getIdCompanyBusinessUnit(),
        ]);
        $this->ownerCompanyUserTransfer->setCustomer($ownerCustomerTransfer);

        $otherCustomerTransfer = $this->tester->haveCustomer();
        $this->otherCompanyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $otherCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyTransferBusinessUnit->getIdCompanyBusinessUnit(),
        ]);
        $this->otherCompanyUserTransfer->setCustomer($otherCustomerTransfer);

        $this->product = $this->tester->haveProduct();
        $this->tester->haveProductInStock([StockProductTransfer::SKU => $this->product->getSku()]);
    }

    /**
     * @return void
     */
    public function testOwnerCanGetShoppingListItemNoteByIdShoppingListItem(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->createShoppingListItem($shoppingListTransfer, $this->product);

        $shoppingListItemNoteTransfer = (new ShoppingListItemNoteBuilder(([
            ShoppingListItemNoteTransfer::FK_SHOPPING_LIST_ITEM => $shoppingListItemTransfer->getIdShoppingListItem(),
            ShoppingListItemNoteTransfer::MESSAGE => 'Note for shopping item goes here',
        ])))->build();

        $this->tester->getFacade()->saveShoppingListItemNote($shoppingListItemNoteTransfer);

        // Act
        $shoppingListItemNoteResponseTransfer = $this->tester->getFacade()->getShoppingListItemNoteByIdShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem());

        // Assert
        $this->assertEquals($shoppingListItemNoteTransfer, $shoppingListItemNoteResponseTransfer);
    }

    /**
     * @return void
     */
    public function testOwnerCanDeleteShoppingListItemNote(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->createShoppingListItem($shoppingListTransfer, $this->product);

        $shoppingListItemNoteTransfer = (new ShoppingListItemNoteBuilder(([
            ShoppingListItemNoteTransfer::FK_SHOPPING_LIST_ITEM => $shoppingListItemTransfer->getIdShoppingListItem(),
            ShoppingListItemNoteTransfer::MESSAGE => 'Note for shopping item goes here',
        ])))->build();

        $this->tester->getFacade()->saveShoppingListItemNote($shoppingListItemNoteTransfer);

        // Act
        $shoppingListItemNoteResponseTransfer = $this->tester->getFacade()->getShoppingListItemNoteByIdShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem());

        // Assert
        $this->assertEquals($shoppingListItemNoteTransfer, $shoppingListItemNoteResponseTransfer);

        // Arrange
        $this->tester->getFacade()->deleteShoppingListItemNote($shoppingListItemNoteResponseTransfer);

        // Act
        $shoppingListItemNoteResponseTransfer = $this->tester->getFacade()->getShoppingListItemNoteByIdShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem());

        // Assert
        $this->assertNull($shoppingListItemNoteResponseTransfer);
    }
}
