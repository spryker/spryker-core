<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingListNote\Business\ShoppingListNoteFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ShoppingListItemNoteBuilder;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingListItemNoteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
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

    protected const CART_TEST_NOTE = 'CART_TEST_NOTE';

    /**
     * @var \SprykerTest\Zed\ShoppingListNote\ShoppingListNoteBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $ownerCompanyUserTransfer;

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

        $this->tester->haveShoppingListPermissionGroup(ShoppingListConfig::PERMISSION_GROUP_READ_ONLY, [
            ReadShoppingListPermissionPlugin::KEY,
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

        $this->productTransfer = $this->tester->haveProduct();
        $this->tester->haveProductInStock([StockProductTransfer::SKU => $this->productTransfer->getSku()]);
    }

    /**
     * @return void
     */
    public function testSaveShoppingListItemNoteForShoppingListItemAddsNoteForShoppingListItem(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->createShoppingListItem($shoppingListTransfer, $this->productTransfer);
        $shoppingListItemNoteTransfer = $this->createShoppingListItemNote($shoppingListItemTransfer);
        $shoppingListItemTransfer->setShoppingListItemNote($shoppingListItemNoteTransfer);

        // Act
        $this->tester->getFacade()->saveShoppingListItemNoteForShoppingListItem($shoppingListItemTransfer);

        $storedShoppingListItemNoteTransfer = $this->tester
            ->getFacade()
            ->getShoppingListItemNoteByIdShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem());

        // Assert
        $this->assertEquals($shoppingListItemNoteTransfer, $storedShoppingListItemNoteTransfer);
    }

    /**
     * @return void
     */
    public function testGetShoppingListItemNoteByIdShoppingListItemGetsNoteByIdShoppingList(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->createShoppingListItem($shoppingListTransfer, $this->productTransfer);
        $shoppingListItemNoteTransfer = $this->createShoppingListItemNote($shoppingListItemTransfer);
        $shoppingListItemTransfer->setShoppingListItemNote($shoppingListItemNoteTransfer);

        $this->tester->getFacade()->saveShoppingListItemNoteForShoppingListItem($shoppingListItemTransfer);

        // Act
        $storedShoppingListItemNoteTransfer = $this->tester
            ->getFacade()
            ->getShoppingListItemNoteByIdShoppingListItem($shoppingListItemNoteTransfer->getFkShoppingListItem());

        // Assert
        $this->assertEquals($shoppingListItemNoteTransfer, $storedShoppingListItemNoteTransfer);
    }

    /**
     * @return void
     */
    public function testSaveShoppingListItemNoteForShoppingListItemDeletesShoppingListNoteWithEmptyNote(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->createShoppingListItem($shoppingListTransfer, $this->productTransfer);
        $shoppingListItemNoteTransfer = $this->createShoppingListItemNote($shoppingListItemTransfer)->setNote('');
        $shoppingListItemTransfer->setShoppingListItemNote($shoppingListItemNoteTransfer);

        // Act
        $this->tester->getFacade()->saveShoppingListItemNoteForShoppingListItem($shoppingListItemTransfer);

        $storedShoppingListItemNoteTransfer = $this->tester
            ->getFacade()
            ->getShoppingListItemNoteByIdShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem());

        // Assert
        $this->assertNull($storedShoppingListItemNoteTransfer->getIdShoppingListItemNote());
    }

    /**
     * @return void
     */
    public function testDeleteShoppingListItemNoteDeletesNoteFromShoppingListItem(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->createShoppingListItem($shoppingListTransfer, $this->productTransfer);
        $shoppingListItemNoteTransfer = $this->createShoppingListItemNote($shoppingListItemTransfer);

        // Act
        $this->tester->getFacade()->deleteShoppingListItemNote($shoppingListItemNoteTransfer);

        $storedShoppingListItemNoteTransfer = $this->tester
            ->getFacade()
            ->getShoppingListItemNoteByIdShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem());

        // Assert
        $this->assertNull($storedShoppingListItemNoteTransfer->getIdShoppingListItemNote());
    }

    /**
     * @return void
     */
    public function testExpandShoppingListExpandsShoppingListItemWithNote(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->createShoppingListItem($shoppingListTransfer, $this->productTransfer);

        // Act
        $expandedShoppingListItemTransfer = $this->tester->getFacade()->expandShoppingListItem($shoppingListItemTransfer);

        // Assert
        $this->assertNotNull($expandedShoppingListItemTransfer->getShoppingListItemNote());
    }

    /**
     * @return void
     */
    public function testMapItemCartNoteToShoppingListItemNote(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())->setCartNote(static::CART_TEST_NOTE);

        // Act
        $mappedShoppingListItemTransfer = $this->tester
            ->getFacade()
            ->mapItemCartNoteToShoppingListItemNote($itemTransfer, new ShoppingListItemTransfer());

        // Assert
        $this->assertEquals(static::CART_TEST_NOTE, $mappedShoppingListItemTransfer->getShoppingListItemNote()->getNote());
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemNoteTransfer
     */
    protected function createShoppingListItemNote(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemNoteTransfer
    {
        return (new ShoppingListItemNoteBuilder(([
            ShoppingListItemNoteTransfer::FK_SHOPPING_LIST_ITEM => $shoppingListItemTransfer->getIdShoppingListItem(),
            ShoppingListItemNoteTransfer::NOTE => 'Note for shopping item goes here',
        ])))->build();
    }
}
