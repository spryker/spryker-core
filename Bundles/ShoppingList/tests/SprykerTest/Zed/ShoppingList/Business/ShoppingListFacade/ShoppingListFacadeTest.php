<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingList\Business\ShoppingListFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ShoppingListBuilder;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyUserTransfer;
use Generated\Shared\Transfer\ShoppingListDismissRequestTransfer;
use Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListShareRequestTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\ShoppingList\ShoppingListConfig;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use Spryker\Zed\ShoppingList\Communication\Plugin\ReadShoppingListPermissionPlugin;
use Spryker\Zed\ShoppingList\Communication\Plugin\ShoppingListPermissionStoragePlugin;
use Spryker\Zed\ShoppingList\Communication\Plugin\WriteShoppingListPermissionPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ShoppingList
 * @group Business
 * @group ShoppingListFacade
 * @group Facade
 * @group ShoppingListFacadeTest
 * Add your own group annotations below this line
 */
class ShoppingListFacadeTest extends Unit
{
    protected const ERROR_DUPLICATE_NAME_SHOPPING_LIST = 'customer.account.shopping_list.error.duplicate_name';

    protected const FAKE_PERMISSION_READ_ONLY = 'FAKE_READ_ONLY';
    protected const FAKE_PERMISSION_FULL_ACCESS = 'FAKE_FULL_ACCESS';

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
     * @var \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected $shoppingList;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $product;

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
    protected function setUp()
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
    }

    /**
     * @return void
     */
    public function testCustomerCanCreateShoppingList()
    {
        // Arrange
        $shoppingListTransfer = (new ShoppingListBuilder())->build()
            ->setIdCompanyUser($this->ownerCompanyUserTransfer->getIdCompanyUser())
            ->setCustomerReference($this->ownerCompanyUserTransfer->getCustomer()->getCustomerReference());

        // Act
        $shoppingListTransfer = $this->tester->getFacade()->createShoppingList($shoppingListTransfer)->getShoppingList();
        $loadedShoppingListTransfer = $this->tester->getFacade()->getShoppingList($shoppingListTransfer);

        // Assert
        $this->assertNotEmpty($loadedShoppingListTransfer->getIdShoppingList(), 'Customer should have been able to save shopping list to database.');
    }

    /**
     * @return void
     */
    public function testCustomersShoppingListNameShouldBeUnique()
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $secondShoppingListTransfer = (new ShoppingListBuilder())->build()
            ->setName($shoppingListTransfer->getName())
            ->setIdCompanyUser($this->ownerCompanyUserTransfer->getIdCompanyUser())
            ->setCustomerReference($this->ownerCompanyUserTransfer->getCustomer()->getCustomerReference());

        // Act
        $shoppingListResponseTransfer = $this->tester->getFacade()->createShoppingList($secondShoppingListTransfer);

        // Assert
        $this->assertFalse($shoppingListResponseTransfer->getIsSuccess(), 'Customer should not be able to have two shopping lists with same name.');
        $this->assertArraySubset(
            [static::ERROR_DUPLICATE_NAME_SHOPPING_LIST],
            $shoppingListResponseTransfer->getErrors(),
            'Customer should not be able to have two shopping lists with same name.'
        );
    }

    /**
     * @return void
     */
    public function testCustomersCanRenameShoppinglist()
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $newShoppingListName = 'NEW_' . $shoppingListTransfer->getName();
        $shoppingListTransfer->setName($newShoppingListName);

        // Act
        $this->tester->getFacade()->updateShoppingList($shoppingListTransfer);
        $loadedShoppingListTransfer = $this->tester->getFacade()->getShoppingList($shoppingListTransfer);

        // Assert
        $this->assertSame($loadedShoppingListTransfer->getName(), $newShoppingListName, 'Owner should have been able to rename.');
    }

    /**
     * @return void
     */
    public function testOwnerCanRemoveShoppingList()
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);

        // Act
        $shoppingListResponseTransfer = $this->tester->getFacade()->removeShoppingList($shoppingListTransfer);

        // Assert
        $this->assertTrue($shoppingListResponseTransfer->getIsSuccess(), 'Owner should have been able to remove the shopping list from database.');
    }

    /**
     * @return void
     */
    public function testOnlyOwnerCanRemoveShoppingList()
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListTransfer->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser());

        // Act
        $shoppingListResponseTransfer = $this->tester->getFacade()->removeShoppingList($shoppingListTransfer);

        // Assert
        $this->assertFalse($shoppingListResponseTransfer->getIsSuccess(), 'Only owner should have been able to remove the shopping list from database.');
    }

    /**
     * @return void
     */
    public function testCustomerCanShareShoppingListWithCompanyUser()
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
                ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser())
                ->setShoppingListOwnerId($this->ownerCompanyUserTransfer->getIdCompanyUser())
                ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
                ->setIdShoppingListPermissionGroup($this->readOnlyPermissionGroup->getIdShoppingListPermissionGroup());

        // Act
        $shoppingListResponseTransfer = $this->tester->getFacade()->shareShoppingListWithCompanyUser($shoppingListShareRequestTransfer);
        $sharedShoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser());
        $sharedShoppingListTransfer = $this->tester->getFacade()->getShoppingList($sharedShoppingListTransfer);

        // Assert
        $this->assertTrue($shoppingListResponseTransfer->getIsSuccess(), 'Owner should be able to share his shopping list company user.');
        $this->assertSame(
            $shoppingListTransfer->getIdShoppingList(),
            $sharedShoppingListTransfer->getIdShoppingList(),
            'Company user should be able to load shopping list shared with him.'
        );
    }

    /**
     * @return void
     */
    public function testCustomerCanShareShoppingListWithBusinessUnit()
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
                ->setIdCompanyBusinessUnit($this->otherCompanyUserTransfer->getFkCompanyBusinessUnit())
                ->setShoppingListOwnerId($this->ownerCompanyUserTransfer->getIdCompanyUser())
                ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
                ->setIdShoppingListPermissionGroup($this->fullAccessPermissionGroup->getIdShoppingListPermissionGroup());

        // Act
        $shoppingListResponseTransfer = $this->tester->getFacade()->shareShoppingListWithCompanyBusinessUnit($shoppingListShareRequestTransfer);
        $sharedShoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser());
        $sharedShoppingListTransfer = $this->tester->getFacade()->getShoppingList($sharedShoppingListTransfer);

        // Assert
        $this->assertTrue($shoppingListResponseTransfer->getIsSuccess(), 'Owner should be able to share his shopping list with business unit.');
        $this->assertSame(
            $shoppingListTransfer->getIdShoppingList(),
            $sharedShoppingListTransfer->getIdShoppingList(),
            'Any user from business unit should be able to load shopping list shared with it.'
        );
    }

    /**
     * @return void
     */
    public function testCustomerCanShareShoppingList()
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListCompanyUserTransfer = (new ShoppingListCompanyUserTransfer())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdShoppingListPermissionGroup($this->fullAccessPermissionGroup->getIdShoppingListPermissionGroup());
        $shoppingListTransfer->addSharedCompanyUsers($shoppingListCompanyUserTransfer);

        // Act
        $shoppingListResponseTransfer = $this->tester->getFacade()->updateShoppingListSharedEntities($shoppingListTransfer);
        $sharedShoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdCompanyUser($shoppingListCompanyUserTransfer->getIdCompanyUser());
        $sharedShoppingListTransfer = $this->tester->getFacade()->getShoppingList($sharedShoppingListTransfer);

        // Assert
        $this->assertTrue($shoppingListResponseTransfer->getIsSuccess(), 'Owner should be able to share his shopping list.');
        $this->assertSame(
            $shoppingListTransfer->getIdShoppingList(),
            $sharedShoppingListTransfer->getIdShoppingList(),
            'Any company user or business unit should be able to load shopping list shared with it.'
        );
    }

    /**
     * @return void
     */
    public function testCustomerCanNotRemoveSharedShoppingList()
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
                ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser())
                ->setShoppingListOwnerId($this->ownerCompanyUserTransfer->getIdCompanyUser())
                ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
                ->setIdShoppingListPermissionGroup($this->readOnlyPermissionGroup->getIdShoppingListPermissionGroup());

        // Act
        $this->tester->getFacade()->shareShoppingListWithCompanyUser($shoppingListShareRequestTransfer);
        $sharedShoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser());
        $shoppingListResponseTransfer = $this->tester->getFacade()->removeShoppingList($sharedShoppingListTransfer);

        // Assert
        $this->assertFalse($shoppingListResponseTransfer->getIsSuccess(), 'Shared shopping list cannot be removed by customer.');
    }

    /**
     * @return void
     */
    public function testOwnerCanAddItemToShoppingList()
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
    public function testCustomerCanNotAddItemToSharedShoppingList()
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
    public function testOwnerCanRemoveItemFromShoppingList()
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

        // Act
        $shoppingListItemResponseTransfer = $this->tester->getFacade()->removeItemById($shoppingListItemTransfer);

        // Assert
        $this->assertTrue($shoppingListItemResponseTransfer->getIsSuccess(), 'Owner should be able to remove item from shopping list.');
    }

    /**
     * @return void
     */
    public function testCustomerCanCreateShoppingListFromHisQuote()
    {
        // Arrange
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::ITEMS => [ItemTransfer::SKU => $this->product->getSku(), ItemTransfer::UNIT_PRICE => 1],
            QuoteTransfer::CUSTOMER => $this->ownerCompanyUserTransfer->getCustomer(),
            QuoteTransfer::STORE => [StoreTransfer::NAME => 'DE'],
        ]);

        $shoppingListFromCartRequestTransfer = new ShoppingListFromCartRequestTransfer();
        $shoppingListFromCartRequestTransfer->setCustomer($this->ownerCompanyUserTransfer->getCustomer())
            ->setShoppingListName((new ShoppingListBuilder())->build()->getName())
            ->setIdQuote($quoteTransfer->getIdQuote());

        // Act
        $shoppingListItemResponseTransfer = $this->tester->getFacade()->createShoppingListFromQuote($shoppingListFromCartRequestTransfer);

        // Assert
        $this->assertNotNull($shoppingListItemResponseTransfer->getIdShoppingList(), 'Customer should be able to create shopping list from his quote.');
    }

    /**
     * @return void
     */
    public function testOwnerCanGetListOfShoppingListItems()
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
    public function testCustomerCanGetItemListOfSharedShoppingList()
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
    public function testOnlyConcreteProductCanBeAddedToShoppingList()
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
    public function testShouldReturnShoppingListPermissionGroupCollection()
    {
        // Arrange
        $this->tester->haveShoppingListPermissionGroup(self::FAKE_PERMISSION_READ_ONLY, [
            ReadShoppingListPermissionPlugin::KEY,
        ]);
        $this->tester->haveShoppingListPermissionGroup(self::FAKE_PERMISSION_FULL_ACCESS, [
            ReadShoppingListPermissionPlugin::KEY,
            WriteShoppingListPermissionPlugin::KEY,
        ]);

        // Act
        $resultShoppingListPermissionGroupCollection = $this->tester->getFacade()->getShoppingListPermissionGroups();

        // Assert
        $this->assertInstanceOf(
            ShoppingListPermissionGroupCollectionTransfer::class,
            $resultShoppingListPermissionGroupCollection
        );

        $shoppingListPermissionGroupNames = $this->getPermissionGroupNamesFromCollection($resultShoppingListPermissionGroupCollection);

        $this->assertContains(self::FAKE_PERMISSION_READ_ONLY, $shoppingListPermissionGroupNames);
        $this->assertContains(self::FAKE_PERMISSION_FULL_ACCESS, $shoppingListPermissionGroupNames);
    }

    /**
     * @return void
     */
    public function testSharedCompanyUserWithFullAccessPermissionCanAddItemToShoppingList()
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
    public function testSharedCompanyUserWithReadOnlyPermissionCanNotAddItemToShoppingList()
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
    public function testDismissShoppingListSharingCompanyUserCanDismissSharingShoppingListWithCompanyUser(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
                ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser())
                ->setShoppingListOwnerId($this->ownerCompanyUserTransfer->getIdCompanyUser())
                ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
                ->setIdShoppingListPermissionGroup($this->readOnlyPermissionGroup->getIdShoppingListPermissionGroup());
        $this->tester->getFacade()->shareShoppingListWithCompanyUser($shoppingListShareRequestTransfer);

        // Act
        $shoppingListShareResponseTransfer = $this->tester->getFacade()->dismissShoppingListSharing(
            (new ShoppingListDismissRequestTransfer())
                ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
                ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser())
        );
        $sharedShoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser());
        $sharedShoppingListTransfer = $this->tester->getFacade()->getShoppingList($sharedShoppingListTransfer);

        // Assert
        $this->assertTrue($shoppingListShareResponseTransfer->getIsSuccess());
        $this->assertNull($sharedShoppingListTransfer->getIdShoppingList());
    }

    /**
     * @return void
     */
    public function testDismissShoppingListSharingCompanyUserCanDismissSharingShoppingListWithBusinessUnit(): void
    {
        // Arrange
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
                ->setIdCompanyBusinessUnit($this->otherCompanyUserTransfer->getFkCompanyBusinessUnit())
                ->setShoppingListOwnerId($this->ownerCompanyUserTransfer->getIdCompanyUser())
                ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
                ->setIdShoppingListPermissionGroup($this->fullAccessPermissionGroup->getIdShoppingListPermissionGroup());
        $this->tester->getFacade()->shareShoppingListWithCompanyBusinessUnit($shoppingListShareRequestTransfer);

        // Act
        $shoppingListShareResponseTransfer = $this->tester->getFacade()->dismissShoppingListSharing(
            (new ShoppingListDismissRequestTransfer())
                ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
                ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser())
        );
        $sharedShoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser());
        $sharedShoppingListTransfer = $this->tester->getFacade()->getShoppingList($sharedShoppingListTransfer);

        // Assert
        $this->assertTrue($shoppingListShareResponseTransfer->getIsSuccess());
        $this->assertNull($sharedShoppingListTransfer->getIdShoppingList());
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer $shoppingListPermissionGroupCollectionTransfer
     *
     * @return string[]
     */
    protected function getPermissionGroupNamesFromCollection(
        ShoppingListPermissionGroupCollectionTransfer $shoppingListPermissionGroupCollectionTransfer
    ): array {
        $shoppingListPermissionGroupNames = [];

        foreach ($shoppingListPermissionGroupCollectionTransfer->getPermissionGroups() as $shoppingListPermissionGroupTransfer) {
            $shoppingListPermissionGroupNames[] = $shoppingListPermissionGroupTransfer->getName();
        }

        return $shoppingListPermissionGroupNames;
    }
}
