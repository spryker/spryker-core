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
use Orm\Zed\ShoppingListNote\Persistence\SpyShoppingListItemNoteQuery;
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
    protected $notOwnerCompanyUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    protected $readOnlyPermissionGroup;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productTransfer;

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
        $this->notOwnerCompanyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $otherCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyTransferBusinessUnit->getIdCompanyBusinessUnit(),
        ]);
        $this->notOwnerCompanyUserTransfer->setCustomer($otherCustomerTransfer);

        $this->productTransfer = $this->tester->haveProduct();
        $this->tester->haveProductInStock([StockProductTransfer::SKU => $this->productTransfer->getSku()]);
    }

    /**
     * @return void
     */
    public function testOwnerCanSaveShoppingListItemNote(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->createShoppingListItem($shoppingListTransfer, $this->productTransfer);

        $shoppingListItemNoteTransfer = (new ShoppingListItemNoteBuilder(([
            ShoppingListItemNoteTransfer::FK_SHOPPING_LIST_ITEM => $shoppingListItemTransfer->getIdShoppingListItem(),
            ShoppingListItemNoteTransfer::MESSAGE => 'Note for shopping item goes here',
        ])))->build();

        $this->tester->getFacade()->saveShoppingListItemNote($shoppingListItemNoteTransfer);

        // Act
        $shoppingListItemNote = SpyShoppingListItemNoteQuery::create()
            ->filterByIdShoppingListItemNote($shoppingListItemNoteTransfer->getIdShoppingListItemNote())
            ->find();

        // Assert
        $this->assertEquals(1, count($shoppingListItemNote->getData()));
    }

    /**
     * @return void
     */
    public function testOwnerCanDeleteShoppingListItemNote(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->createShoppingListItem($shoppingListTransfer, $this->productTransfer);

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
        $this->assertNull($shoppingListItemNoteResponseTransfer->getIdShoppingListItemNote());
    }

    /**
     * @return void
     */
    public function testCanGetShoppingListItemNoteByIdShoppingListItem(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->createShoppingListItem($shoppingListTransfer, $this->productTransfer);
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
}
