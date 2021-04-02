<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingList\Business\ShoppingList;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyUserTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListShareRequestTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Spryker\Shared\ShoppingList\ShoppingListConfig;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use Spryker\Zed\ShoppingList\Communication\Plugin\ReadShoppingListPermissionPlugin;
use Spryker\Zed\ShoppingList\Communication\Plugin\ShoppingListPermissionStoragePlugin;
use Spryker\Zed\ShoppingList\Communication\Plugin\WriteShoppingListPermissionPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShoppingList
 * @group Business
 * @group ShoppingList
 * @group ShoppingListItemTest
 * Add your own group annotations below this line
 */
class ShoppingListItemTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ShoppingList\ShoppingListBusinessTester
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
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $product;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productTwo;

    /**
     * @var \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    protected $fullAccessPermissionGroup;

    /**
     * @var \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    protected $readOnlyPermissionGroup;

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

        $this->readOnlyPermissionGroup = $this->tester
            ->haveShoppingListPermissionGroup(ShoppingListConfig::PERMISSION_GROUP_READ_ONLY, [
                ReadShoppingListPermissionPlugin::KEY,
            ]);

        $this->fullAccessPermissionGroup = $this->tester
            ->haveShoppingListPermissionGroup(ShoppingListConfig::PERMISSION_GROUP_FULL_ACCESS, [
                ReadShoppingListPermissionPlugin::KEY,
                WriteShoppingListPermissionPlugin::KEY,
            ]);

        $this->tester->getLocator()->permission()->facade()->syncPermissionPlugins();

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

        $this->productTwo = $this->tester->haveProduct();
        $this->tester->haveProductInStock([StockProductTransfer::SKU => $this->productTwo->getSku()]);
    }

    /**
     * @return void
     */
    public function testOwnerCanAddItemToShoppingList(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->ownerCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);

        // Act
        $resultShoppingListItemTransfer = $this->tester->getFacade()->addItem($shoppingListItemTransfer);

        // Assert
        $this->assertNotNull($resultShoppingListItemTransfer->getIdShoppingListItem(), 'Owner should be able to add item to shopping list.');
    }

    /**
     * @return void
     */
    public function testOwnerCanNotAddItemWithNonPositiveQuantityToShoppingList(): void
    {
        $quantities = [0, -1];

        foreach ($quantities as $quantity) {
            // Arrange
            $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
            $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
                ShoppingListItemTransfer::ID_COMPANY_USER => $this->ownerCompanyUserTransfer->getIdCompanyUser(),
                ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
                ShoppingListItemTransfer::QUANTITY => $quantity,
                ShoppingListItemTransfer::SKU => $this->product->getSku(),
            ]);

            // Act
            $resultShoppingListItemTransfer = $this->tester->getFacade()->addItem($shoppingListItemTransfer);

            // Assert
            $this->assertNull($resultShoppingListItemTransfer->getIdShoppingListItem(), "Owner should not be able to add item with quantity '$quantity'' to shopping list.");
        }
    }

    /**
     * @return void
     */
    public function testCustomerCanNotAddItemToSharedShoppingList(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->otherCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);

        // Act
        $resultShoppingListItemTransfer = $this->tester->getFacade()->addItem($shoppingListItemTransfer);

        // Assert
        $this->assertNull($resultShoppingListItemTransfer->getIdShoppingListItem(), 'Shared shopping list should not modified by customer.');
    }

    /**
     * @return void
     */
    public function testOwnerCanGetListOfShoppingListItems(): void
    {
        // Arrange
        $fistShoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->ownerCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $fistShoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);
        $this->tester->getFacade()->addItem($shoppingListItemTransfer);

        $secondShoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->ownerCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $secondShoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);
        $this->tester->getFacade()->addItem($shoppingListItemTransfer);

        $shoppingListItemCollectionTransfer = (new ShoppingListCollectionTransfer())
            ->addShoppingList($fistShoppingListTransfer)
            ->addShoppingList($secondShoppingListTransfer);

        // Act
        $shoppingListItemResponseTransfer = $this->tester->getFacade()->getShoppingListItemCollection($shoppingListItemCollectionTransfer);

        // Assert
        $this->assertSame(2, $shoppingListItemResponseTransfer->getItems()->count(), 'Customer should be able to load shopping list items by shopping lists.');
    }

    /**
     * @return void
     */
    public function testSharedCompanyUserWithFullAccessPermissionCanAddItemToShoppingList(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);

        $shoppingListCompanyUserTransfer = (new ShoppingListCompanyUserTransfer())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdShoppingListPermissionGroup($this->fullAccessPermissionGroup->getIdShoppingListPermissionGroup());
        $shoppingListTransfer->addSharedCompanyUsers($shoppingListCompanyUserTransfer);

        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $shoppingListCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);

        // Act
        $this->tester->getFacade()->updateShoppingListSharedEntities($shoppingListTransfer);
        $this->tester->getFacade()->addItem($shoppingListItemTransfer);

        $shoppingListItemResponseTransfer = $this->tester->getFacade()->getShoppingListItemCollection(
            (new ShoppingListCollectionTransfer())->addShoppingList($shoppingListTransfer)
        );

        // Assert
        $this->assertSame(1, $shoppingListItemResponseTransfer->getItems()->count(), 'Shared company user with full access permission can add item to shopping list.');
    }

    /**
     * @return void
     */
    public function testSharedCompanyUserWithReadOnlyPermissionCanNotAddItemToShoppingList(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);

        $shoppingListCompanyUserTransfer = (new ShoppingListCompanyUserTransfer())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdShoppingListPermissionGroup($this->readOnlyPermissionGroup->getIdShoppingListPermissionGroup());
        $shoppingListTransfer->addSharedCompanyUsers($shoppingListCompanyUserTransfer);

        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $shoppingListCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);

        // Act
        $this->tester->getFacade()->updateShoppingListSharedEntities($shoppingListTransfer);
        $this->tester->getFacade()->addItem($shoppingListItemTransfer);

        $shoppingListItemResponseTransfer = $this->tester->getFacade()->getShoppingListItemCollection(
            (new ShoppingListCollectionTransfer())->addShoppingList($shoppingListTransfer)
        );

        // Assert
        $this->assertSame(0, $shoppingListItemResponseTransfer->getItems()->count(), 'Shared company user with read only permission can not add item to shopping list.');
    }

    /**
     * @return void
     */
    public function testGetCustomerShoppingListCollection(): void
    {
        // Arrange
        $fistShoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->ownerCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $fistShoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);
        $this->tester->getFacade()->addShoppingListItem($shoppingListItemTransfer);

        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->ownerCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $fistShoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 2,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);
        $this->tester->getFacade()->addShoppingListItem($shoppingListItemTransfer);

        // Act
        $shoppingListItemResponseTransfer = $this->tester->getFacade()->getCustomerShoppingListCollection($this->ownerCompanyUserTransfer->getCustomer());

        // Assert
        $this->assertSame(3, $shoppingListItemResponseTransfer->getShoppingLists()[0]->getNumberOfItems(), 'Customer should get correct count of items in the shopping list.');
    }

    /**
     * @return void
     */
    public function testCustomerCanGetItemListOfSharedShoppingList(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->ownerCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);
        $this->tester->getFacade()->addItem($shoppingListItemTransfer);

        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser())
            ->setShoppingListOwnerId($this->ownerCompanyUserTransfer->getIdCompanyUser())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdShoppingListPermissionGroup($this->fullAccessPermissionGroup->getIdShoppingListPermissionGroup());
        $this->tester->getFacade()->shareShoppingListWithCompanyUser($shoppingListShareRequestTransfer);
        $sharedShoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser());

        $shoppingListItemCollectionTransfer = (new ShoppingListCollectionTransfer())
            ->addShoppingList($sharedShoppingListTransfer);

        // Act
        $shoppingListItemResponseTransfer = $this->tester->getFacade()->getShoppingListItemCollection($shoppingListItemCollectionTransfer);

        // Assert
        $this->assertSame(1, $shoppingListItemResponseTransfer->getItems()->count(), 'Customer should be able to load shopping list items from shared shopping lists.');
    }

    /**
     * @return void
     */
    public function testOnlyConcreteProductCanBeAddedToShoppingList(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->ownerCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getAbstractSku(),
        ]);

        // Act
        $resultShoppingListItemTransfer = $this->tester->getFacade()->addItem($shoppingListItemTransfer);

        // Assert
        $this->assertNull($resultShoppingListItemTransfer->getIdShoppingListItem(), 'Abstract product should not be able to be added to shopping list.');
    }

    /**
     * @return void
     */
    public function testOwnerCanAddShoppingListItemToShoppingList(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->ownerCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);

        // Act
        $resultShoppingListItemResponseTransfer = $shoppingListFacade->addShoppingListItem($shoppingListItemTransfer);

        // Assert
        $this->assertTrue($resultShoppingListItemResponseTransfer->getIsSuccess(), 'Owner should be able to add item to shopping list.');
        $this->assertNotNull($resultShoppingListItemResponseTransfer->getShoppingListItem(), 'Owner should be able to add item to shopping list.');
    }

    /**
     * @return void
     */
    public function testOwnerCanNotAddShoppingListItemWithNonPositiveQuantityToShoppingList(): void
    {
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();

        $quantities = [0, -1];

        foreach ($quantities as $quantity) {
            // Arrange
            $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
            $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
                ShoppingListItemTransfer::ID_COMPANY_USER => $this->ownerCompanyUserTransfer->getIdCompanyUser(),
                ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
                ShoppingListItemTransfer::QUANTITY => $quantity,
                ShoppingListItemTransfer::SKU => $this->product->getSku(),
            ]);

            // Act
            $resultShoppingListItemResponseTransfer = $shoppingListFacade->addShoppingListItem($shoppingListItemTransfer);

            // Assert
            $this->assertFalse($resultShoppingListItemResponseTransfer->getIsSuccess(), "Owner should not be able to add item with quantity '$quantity' to shopping list.");
            $this->assertCount(1, $resultShoppingListItemResponseTransfer->getErrors(), "Owner should not be able to add item with quantity '$quantity' to shopping list.");
            $this->assertNull($resultShoppingListItemResponseTransfer->getShoppingListItem(), "Owner should not be able to add item with quantity '$quantity' to shopping list.");
        }
    }

    /**
     * @return void
     */
    public function testCustomerCanNotAddShoppingListItemToSharedShoppingList(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->otherCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);

        // Act
        $resultShoppingListItemResponseTransfer = $shoppingListFacade->addShoppingListItem($shoppingListItemTransfer);

        // Assert
        $this->assertFalse($resultShoppingListItemResponseTransfer->getIsSuccess(), 'Shared shopping list should not modified by customer.');
        $this->assertCount(1, $resultShoppingListItemResponseTransfer->getErrors(), 'Shared shopping list should not modified by customer.');
        $this->assertNull($resultShoppingListItemResponseTransfer->getShoppingListItem(), 'Shared shopping list should not modified by customer.');
    }

    /**
     * @return void
     */
    public function testOwnerCanRemoveItemFromShoppingList(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->ownerCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);
        $shoppingListFacade->addShoppingListItem($shoppingListItemTransfer);

        // Act
        $shoppingListItemResponseTransfer = $shoppingListFacade->removeItemById($shoppingListItemTransfer);

        // Assert
        $this->assertTrue($shoppingListItemResponseTransfer->getIsSuccess(), 'Owner should be able to remove item from shopping list.');
    }

    /**
     * @return void
     */
    public function testOwnerShouldBeAbleToRemoveShoppingListItemsFromShoppingList(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $firstShoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->ownerCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);
        $shoppingListFacade->addShoppingListItem($firstShoppingListItemTransfer);

        $secondShoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->ownerCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 2,
            ShoppingListItemTransfer::SKU => $this->productTwo->getSku(),
        ]);
        $shoppingListFacade->addShoppingListItem($secondShoppingListItemTransfer);

        $requestShoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($secondShoppingListItemTransfer->getIdShoppingListItem())
            ->setIdCompanyUser($this->ownerCompanyUserTransfer->getIdCompanyUser())
            ->setFkShoppingList($shoppingListTransfer->getIdShoppingList());

        // Act
        $shoppingListItemResponseTransfer = $shoppingListFacade->removeItemById($requestShoppingListItemTransfer);

        $shoppingListItemCollectionTransfer = $shoppingListFacade->getShoppingListItemCollection(
            (new ShoppingListCollectionTransfer())
                ->addShoppingList($shoppingListTransfer)
        );

        // Assert
        $this->assertTrue($shoppingListItemResponseTransfer->getIsSuccess(), 'Owner should be able to remove items from shopping list.');
        $this->assertCount(1, $shoppingListItemCollectionTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testCustomerCannotRemoveShoppingListItemsFromSharedShoppingList(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->ownerCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);
        $shoppingListFacade->addShoppingListItem($shoppingListItemTransfer);

        $requestShoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser())
            ->setFkShoppingList($shoppingListTransfer->getIdShoppingList());

        // Act
        $shoppingListItemResponseTransfer = $shoppingListFacade->removeItemById($requestShoppingListItemTransfer);
        $shoppingListItemCollectionTransfer = $shoppingListFacade->getShoppingListItemCollection(
            (new ShoppingListCollectionTransfer())
                ->addShoppingList($shoppingListTransfer)
        );

        // Assert
        $this->assertFalse($shoppingListItemResponseTransfer->getIsSuccess(), 'Customer cannot remove items from shared shopping list.');
        $this->assertCount(1, $shoppingListItemCollectionTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testSharedCompanyUserWithFullAccessPermissionCanAddShoppingListItemToShoppingList(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);

        $shoppingListCompanyUserTransfer = (new ShoppingListCompanyUserTransfer())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdShoppingListPermissionGroup($this->fullAccessPermissionGroup->getIdShoppingListPermissionGroup());
        $shoppingListTransfer->addSharedCompanyUsers($shoppingListCompanyUserTransfer);

        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $shoppingListCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);

        // Act
        $shoppingListFacade->updateShoppingListSharedEntities($shoppingListTransfer);
        $shoppingListFacade->addShoppingListItem($shoppingListItemTransfer);

        $shoppingListItemResponseTransfer = $shoppingListFacade->getShoppingListItemCollection(
            (new ShoppingListCollectionTransfer())->addShoppingList($shoppingListTransfer)
        );

        // Assert
        $this->assertSame(1, $shoppingListItemResponseTransfer->getItems()->count(), 'Shared company user with full access permission can add item to shopping list.');
    }

    /**
     * @return void
     */
    public function testSharedCompanyUserWithReadOnlyPermissionCanNotAddShoppingListItemToShoppingList(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);

        $shoppingListCompanyUserTransfer = (new ShoppingListCompanyUserTransfer())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdShoppingListPermissionGroup($this->readOnlyPermissionGroup->getIdShoppingListPermissionGroup());
        $shoppingListTransfer->addSharedCompanyUsers($shoppingListCompanyUserTransfer);

        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $shoppingListCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);

        // Act
        $shoppingListFacade->updateShoppingListSharedEntities($shoppingListTransfer);
        $shoppingListFacade->addShoppingListItem($shoppingListItemTransfer);

        $shoppingListItemResponseTransfer = $shoppingListFacade->getShoppingListItemCollection(
            (new ShoppingListCollectionTransfer())->addShoppingList($shoppingListTransfer)
        );

        // Assert
        $this->assertSame(0, $shoppingListItemResponseTransfer->getItems()->count(), 'Shared company user with read only permission can not add item to shopping list.');
    }

    /**
     * @return void
     */
    public function testAddItemsOwnerCanAddItemsToShoppingList(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $customerTransfer = (clone $this->ownerCompanyUserTransfer->getCustomer())
            ->setCompanyUserTransfer($this->ownerCompanyUserTransfer);
        $shoppingListTransfer = $this->tester->createShoppingList($customerTransfer->getCompanyUserTransfer());
        $shoppingListTransfer->addItem(
            $this->tester->buildShoppingListItem([
                ShoppingListItemTransfer::QUANTITY => 1,
                ShoppingListItemTransfer::SKU => $this->product->getSku(),
                ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ])
        );
        $shoppingListTransfer->addItem(
            $this->tester->buildShoppingListItem([
                ShoppingListItemTransfer::QUANTITY => 1,
                ShoppingListItemTransfer::SKU => $this->productTwo->getSku(),
                ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ])
        );

        // Act
        $shoppingListResponseTransfer = $shoppingListFacade->addItems($shoppingListTransfer);
        $shoppingListCollectionTransfer = (new ShoppingListCollectionTransfer())
            ->addShoppingList($shoppingListResponseTransfer->getShoppingList());
        $shoppingListItemCollectionTransfer = $shoppingListFacade->getShoppingListItemCollection($shoppingListCollectionTransfer);

        // Assert
        $this->assertTrue($shoppingListResponseTransfer->getIsSuccess(), 'Owner should be able to add items to shopping list.');
        $this->assertCount(2, $shoppingListItemCollectionTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testAddItemsOwnerCanNotAddIncorrectProductsToShoppingList(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $customerTransfer = (clone $this->ownerCompanyUserTransfer->getCustomer())
            ->setCompanyUserTransfer($this->ownerCompanyUserTransfer);
        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setIdCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());
        $shoppingListTransfer->addItem(
            $this->tester->buildShoppingListItem([
                ShoppingListItemTransfer::QUANTITY => 1,
                ShoppingListItemTransfer::SKU => $this->product->getSku(),
            ])
        );
        $shoppingListTransfer->addItem(
            $this->tester->buildShoppingListItem([
                ShoppingListItemTransfer::QUANTITY => 1,
                ShoppingListItemTransfer::SKU => uniqid('', true),
            ])
        );

        // Act
        $shoppingListResponseTransfer = $shoppingListFacade->addItems($shoppingListTransfer);
        $shoppingListCollectionTransfer = (new ShoppingListCollectionTransfer())
            ->addShoppingList($shoppingListTransfer);
        $shoppingListItemCollectionTransfer = $shoppingListFacade->getShoppingListItemCollection($shoppingListCollectionTransfer);

        // Assert
        $this->assertFalse($shoppingListResponseTransfer->getIsSuccess(), 'Owner should be able to add items to shopping list.');
        $this->assertCount(0, $shoppingListItemCollectionTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testAddItemsCustomerWithoutAccessCanNotAddItemsToShoppingList(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $customerTransfer = (clone $this->otherCompanyUserTransfer->getCustomer())
            ->setCompanyUserTransfer($this->otherCompanyUserTransfer);
        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setIdCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());
        $shoppingListTransfer->addItem(
            $this->tester->buildShoppingListItem([
                ShoppingListItemTransfer::QUANTITY => 1,
                ShoppingListItemTransfer::SKU => $this->product->getSku(),
            ])
        );
        $shoppingListTransfer->addItem(
            $this->tester->buildShoppingListItem([
                ShoppingListItemTransfer::QUANTITY => 1,
                ShoppingListItemTransfer::SKU => uniqid('', true),
            ])
        );

        // Act
        $shoppingListResponseTransfer = $shoppingListFacade->addItems($shoppingListTransfer);
        $shoppingListCollectionTransfer = (new ShoppingListCollectionTransfer())
            ->addShoppingList($shoppingListTransfer);
        $shoppingListItemCollectionTransfer = $shoppingListFacade->getShoppingListItemCollection($shoppingListCollectionTransfer);

        // Assert
        $this->assertFalse($shoppingListResponseTransfer->getIsSuccess(), 'Owner should be able to add items to shopping list.');
        $this->assertCount(0, $shoppingListItemCollectionTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testCheckShoppingListItemProductConcreteIsActiveShouldReturnTrue(): void
    {
        // Arrange
        $shoppingListItemTransfer = new ShoppingListItemTransfer();
        $productConcreteTransfer = $this->tester->createProductConcrete(true);
        $shoppingListItemTransfer->setSku($productConcreteTransfer->getSku());

        // Act
        $isActive = $this->tester->getFacade()
            ->checkShoppingListItemProductIsActive($shoppingListItemTransfer)
            ->getIsSuccess();

        // Assert
        $this->assertTrue($isActive);
    }

    /**
     * @return void
     */
    public function testCheckShoppingListItemProductConcreteIsActiveShouldReturnFalse(): void
    {
        // Arrange
        $shoppingListItemTransfer = new ShoppingListItemTransfer();
        $productConcreteTransfer = $this->tester->createProductConcrete(false);
        $shoppingListItemTransfer->setSku($productConcreteTransfer->getSku());

        // Act
        $isActive = $this->tester->getFacade()
            ->checkShoppingListItemProductIsActive($shoppingListItemTransfer)
            ->getIsSuccess();

        // Assert
        $this->assertFalse($isActive);
    }
}
