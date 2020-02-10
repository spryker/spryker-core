<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingList\Business\ShoppingList;

use Codeception\Test\Unit;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
 *
 * @group SprykerTest
 * @group Zed
 * @group ShoppingList
 * @group Business
 * @group ShoppingList
 * @group ShoppingListTest
 * Add your own group annotations below this line
 */
class ShoppingListTest extends Unit
{
    use ArraySubsetAsserts;

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
    }

    /**
     * @return void
     */
    public function testCustomerCanCreateShoppingList(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->buildShoppingList()
            ->setIdCompanyUser($this->ownerCompanyUserTransfer->getIdCompanyUser())
            ->setCustomerReference($this->ownerCompanyUserTransfer->getCustomer()->getCustomerReference());

        // Act
        $shoppingListTransfer = $shoppingListFacade->createShoppingList($shoppingListTransfer)->getShoppingList();
        $loadedShoppingListTransfer = $shoppingListFacade->getShoppingList($shoppingListTransfer);

        // Assert
        $this->assertNotEmpty($loadedShoppingListTransfer->getIdShoppingList(), 'Customer should have been able to save shopping list to database.');
    }

    /**
     * @return void
     */
    public function testCustomersShoppingListNameShouldBeUnique(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $secondShoppingListTransfer = $this->tester->buildShoppingList()
            ->setName($shoppingListTransfer->getName())
            ->setIdCompanyUser($this->ownerCompanyUserTransfer->getIdCompanyUser())
            ->setCustomerReference($this->ownerCompanyUserTransfer->getCustomer()->getCustomerReference());

        // Act
        $shoppingListResponseTransfer = $shoppingListFacade->createShoppingList($secondShoppingListTransfer);

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
    public function testCustomersCanRenameShoppingList(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $newShoppingListName = 'NEW_' . $shoppingListTransfer->getName();
        $shoppingListTransfer->setName($newShoppingListName);

        // Act
        $shoppingListFacade->updateShoppingList($shoppingListTransfer);
        $loadedShoppingListTransfer = $shoppingListFacade->getShoppingList($shoppingListTransfer);

        // Assert
        $this->assertSame($loadedShoppingListTransfer->getName(), $newShoppingListName, 'Owner should have been able to rename.');
    }

    /**
     * @return void
     */
    public function testOwnerCanRemoveShoppingList(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);

        // Act
        $shoppingListResponseTransfer = $shoppingListFacade->removeShoppingList($shoppingListTransfer);

        // Assert
        $this->assertTrue($shoppingListResponseTransfer->getIsSuccess(), 'Owner should have been able to remove the shopping list from database.');
    }

    /**
     * @return void
     */
    public function testOnlyOwnerCanRemoveShoppingList(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListTransfer->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser());

        // Act
        $shoppingListResponseTransfer = $shoppingListFacade->removeShoppingList($shoppingListTransfer);

        // Assert
        $this->assertFalse($shoppingListResponseTransfer->getIsSuccess(), 'Only owner should have been able to remove the shopping list from database.');
    }

    /**
     * @return void
     */
    public function testCustomerCanShareShoppingListWithCompanyUser(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
                ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser())
                ->setShoppingListOwnerId($this->ownerCompanyUserTransfer->getIdCompanyUser())
                ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
                ->setIdShoppingListPermissionGroup($this->readOnlyPermissionGroup->getIdShoppingListPermissionGroup());

        // Act
        $shoppingListResponseTransfer = $shoppingListFacade->shareShoppingListWithCompanyUser($shoppingListShareRequestTransfer);
        $sharedShoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser());
        $sharedShoppingListTransfer = $shoppingListFacade->getShoppingList($sharedShoppingListTransfer);

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
    public function testCustomerCanShareShoppingListWithBusinessUnit(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
                ->setIdCompanyBusinessUnit($this->otherCompanyUserTransfer->getFkCompanyBusinessUnit())
                ->setShoppingListOwnerId($this->ownerCompanyUserTransfer->getIdCompanyUser())
                ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
                ->setIdShoppingListPermissionGroup($this->fullAccessPermissionGroup->getIdShoppingListPermissionGroup());

        // Act
        $shoppingListResponseTransfer = $shoppingListFacade->shareShoppingListWithCompanyBusinessUnit($shoppingListShareRequestTransfer);
        $sharedShoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser());
        $sharedShoppingListTransfer = $shoppingListFacade->getShoppingList($sharedShoppingListTransfer);

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
    public function testCustomerCanShareShoppingList(): void
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

        // Act
        $shoppingListResponseTransfer = $shoppingListFacade->updateShoppingListSharedEntities($shoppingListTransfer);
        $sharedShoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdCompanyUser($shoppingListCompanyUserTransfer->getIdCompanyUser());
        $sharedShoppingListTransfer = $shoppingListFacade->getShoppingList($sharedShoppingListTransfer);

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
    public function testCustomerCanNotRemoveSharedShoppingList(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
                ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser())
                ->setShoppingListOwnerId($this->ownerCompanyUserTransfer->getIdCompanyUser())
                ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
                ->setIdShoppingListPermissionGroup($this->readOnlyPermissionGroup->getIdShoppingListPermissionGroup());

        // Act
        $shoppingListFacade->shareShoppingListWithCompanyUser($shoppingListShareRequestTransfer);
        $sharedShoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser());
        $shoppingListResponseTransfer = $shoppingListFacade->removeShoppingList($sharedShoppingListTransfer);

        // Assert
        $this->assertFalse($shoppingListResponseTransfer->getIsSuccess(), 'Shared shopping list cannot be removed by customer.');
    }

    /**
     * @return void
     */
    public function testCustomerCanCreateShoppingListFromHisQuote(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::ITEMS => [ItemTransfer::SKU => $this->product->getSku(), ItemTransfer::UNIT_PRICE => 1],
            QuoteTransfer::CUSTOMER => $this->ownerCompanyUserTransfer->getCustomer(),
            QuoteTransfer::STORE => [StoreTransfer::NAME => 'DE'],
        ]);

        $shoppingListFromCartRequestTransfer = new ShoppingListFromCartRequestTransfer();
        $shoppingListFromCartRequestTransfer->setCustomer($this->ownerCompanyUserTransfer->getCustomer())
            ->setShoppingListName($this->tester->buildShoppingList()->getName())
            ->setIdQuote($quoteTransfer->getIdQuote());

        // Act
        $shoppingListItemResponseTransfer = $shoppingListFacade->createShoppingListFromQuote($shoppingListFromCartRequestTransfer);

        // Assert
        $this->assertNotNull($shoppingListItemResponseTransfer->getIdShoppingList(), 'Customer should be able to create shopping list from his quote.');
    }

    /**
     * @return void
     */
    public function testOnlyConcreteProductCanBeAddedToShoppingList(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->ownerCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getAbstractSku(),
        ]);

        // Act
        $resultShoppingListItemResponseTransfer = $shoppingListFacade->addShoppingListItem($shoppingListItemTransfer);

        // Assert
        $this->assertFalse($resultShoppingListItemResponseTransfer->getIsSuccess(), 'Abstract product should not be able to be added to shopping list.');
        $this->assertCount(1, $resultShoppingListItemResponseTransfer->getErrors(), 'Abstract product should not be able to be added to shopping list.');
        $this->assertNull($resultShoppingListItemResponseTransfer->getShoppingListItem(), 'Abstract product should not be able to be added to shopping list.');
    }

    /**
     * @return void
     */
    public function testShouldReturnShoppingListPermissionGroupCollection(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $this->tester->haveShoppingListPermissionGroup(self::FAKE_PERMISSION_READ_ONLY, [
            ReadShoppingListPermissionPlugin::KEY,
        ]);
        $this->tester->haveShoppingListPermissionGroup(self::FAKE_PERMISSION_FULL_ACCESS, [
            ReadShoppingListPermissionPlugin::KEY,
            WriteShoppingListPermissionPlugin::KEY,
        ]);

        // Act
        $resultShoppingListPermissionGroupCollection = $shoppingListFacade->getShoppingListPermissionGroups();

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
    public function testDismissShoppingListSharingCompanyUserCanDismissSharingShoppingListWithCompanyUser(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
                ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser())
                ->setShoppingListOwnerId($this->ownerCompanyUserTransfer->getIdCompanyUser())
                ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
                ->setIdShoppingListPermissionGroup($this->readOnlyPermissionGroup->getIdShoppingListPermissionGroup());
        $shoppingListFacade->shareShoppingListWithCompanyUser($shoppingListShareRequestTransfer);

        // Act
        $shoppingListShareResponseTransfer = $shoppingListFacade->dismissShoppingListSharing(
            (new ShoppingListDismissRequestTransfer())
                ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
                ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser())
        );
        $sharedShoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser());
        $sharedShoppingListTransfer = $shoppingListFacade->getShoppingList($sharedShoppingListTransfer);

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
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $shoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
                ->setIdCompanyBusinessUnit($this->otherCompanyUserTransfer->getFkCompanyBusinessUnit())
                ->setShoppingListOwnerId($this->ownerCompanyUserTransfer->getIdCompanyUser())
                ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
                ->setIdShoppingListPermissionGroup($this->fullAccessPermissionGroup->getIdShoppingListPermissionGroup());
        $shoppingListFacade->shareShoppingListWithCompanyBusinessUnit($shoppingListShareRequestTransfer);

        // Act
        $shoppingListShareResponseTransfer = $shoppingListFacade->dismissShoppingListSharing(
            (new ShoppingListDismissRequestTransfer())
                ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
                ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser())
        );
        $sharedShoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdCompanyUser($this->otherCompanyUserTransfer->getIdCompanyUser());
        $sharedShoppingListTransfer = $shoppingListFacade->getShoppingList($sharedShoppingListTransfer);

        // Assert
        $this->assertTrue($shoppingListShareResponseTransfer->getIsSuccess());
        $this->assertNull($sharedShoppingListTransfer->getIdShoppingList());
    }

    /**
     * @return void
     */
    public function testGetCustomerShoppingListCollection(): void
    {
        // Arrange
        /** @var \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface $shoppingListFacade */
        $shoppingListFacade = $this->tester->getFacade();
        $fistShoppingListTransfer = $this->tester->createShoppingList($this->ownerCompanyUserTransfer);
        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->ownerCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $fistShoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);
        $shoppingListFacade->addShoppingListItem($shoppingListItemTransfer);

        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->ownerCompanyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $fistShoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 2,
            ShoppingListItemTransfer::SKU => $this->product->getSku(),
        ]);
        $shoppingListFacade->addShoppingListItem($shoppingListItemTransfer);

        // Act
        $shoppingListItemResponseTransfer = $shoppingListFacade->getCustomerShoppingListCollection($this->ownerCompanyUserTransfer->getCustomer());

        // Assert
        $this->assertSame(3, $shoppingListItemResponseTransfer->getShoppingLists()[0]->getNumberOfItems(), 'Customer should get correct count of items in the shopping list.');
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
