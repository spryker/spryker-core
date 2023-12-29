<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingList\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ShoppingListConditionsTransfer;
use Generated\Shared\Transfer\ShoppingListCriteriaTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use SprykerTest\Zed\ShoppingList\ShoppingListBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShoppingList
 * @group Business
 * @group Facade
 * @group GetShoppingListCollectionTest
 * Add your own group annotations below this line
 */
class GetShoppingListCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const CUSTOMER_INVALID_REFERENCE = 'invalid-customer-reference';

    /**
     * @var \SprykerTest\Zed\ShoppingList\ShoppingListBusinessTester
     */
    protected ShoppingListBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldReturnEmptyCollection(): void
    {
        // Arrange
        $companyUserTransfer = $this->getCustomerUser();
        $this->tester->createShoppingList($companyUserTransfer);

        $shoppingListConditionsTransfers = (new ShoppingListConditionsTransfer())
            ->addCustomerReference(static::CUSTOMER_INVALID_REFERENCE);
        $shoppingListCriteriaTransfer = (new ShoppingListCriteriaTransfer())->setShoppingListConditions($shoppingListConditionsTransfers);

        // Act
        $shoppingListCollectionTransfer = $this->tester->getFacade()->getShoppingListCollection($shoppingListCriteriaTransfer);

        // Assert
        $this->assertCount(0, $shoppingListCollectionTransfer->getShoppingLists());
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionByCustomerReferences(): void
    {
        // Arrange
        $companyUserTransfer = $this->getCustomerUser();
        $shoppingListTransfer = $this->tester->createShoppingList($companyUserTransfer);

        $anotherCompanyUserTransfer = $this->getCustomerUser();
        $this->tester->createShoppingList($anotherCompanyUserTransfer);

        $shoppingListConditionsTransfers = (new ShoppingListConditionsTransfer())
            ->addCustomerReference($companyUserTransfer->getCustomerOrFail()->getCustomerReferenceOrFail());
        $shoppingListCriteriaTransfer = (new ShoppingListCriteriaTransfer())->setShoppingListConditions($shoppingListConditionsTransfers);

        // Act
        $shoppingListCollectionTransfer = $this->tester->getFacade()->getShoppingListCollection($shoppingListCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shoppingListCollectionTransfer->getShoppingLists());
        /** @var \Generated\Shared\Transfer\ShoppingListTransfer $foundShoppingListTransfer */
        $foundShoppingListTransfer = $shoppingListCollectionTransfer->getShoppingLists()->getIterator()->current();
        $this->assertSame($shoppingListTransfer->getIdShoppingListOrFail(), $foundShoppingListTransfer->getIdShoppingListOrFail());
        $this->assertNotNull($foundShoppingListTransfer->getNumberOfItemsOrFail());
        $this->assertSame($foundShoppingListTransfer->getCustomerReferenceOrFail(), $companyUserTransfer->getCustomerOrFail()->getCustomerReferenceOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionByCompanyUserIds(): void
    {
        // Arrange
        $companyUserTransfer = $this->getCustomerUser();
        $shoppingListTransfer = $this->tester->createShoppingList($companyUserTransfer);
        $shoppingListPermissionGroupTransfer = $this->tester->haveShoppingListPermissionGroup(uniqid(), []);

        $secondCompanyUserTransfer = $this->getCustomerUser();

        $this->tester->haveShoppingListCompanyUser(
            $secondCompanyUserTransfer,
            $shoppingListTransfer,
            $shoppingListPermissionGroupTransfer,
        );

        $thirdCompanyUserTransfer = $this->getCustomerUser();
        $this->tester->createShoppingList($thirdCompanyUserTransfer);

        $shoppingListConditionsTransfers = (new ShoppingListConditionsTransfer())
            ->setWithCustomerSharedShoppingLists(true)
            ->addIdCompanyUser($secondCompanyUserTransfer->getIdCompanyUserOrFail())
            ->addCustomerReference($secondCompanyUserTransfer->getCustomerOrFail()->getCustomerReferenceOrFail());
        $shoppingListCriteriaTransfer = (new ShoppingListCriteriaTransfer())->setShoppingListConditions($shoppingListConditionsTransfers);

        // Act
        $shoppingListCollectionTransfer = $this->tester->getFacade()->getShoppingListCollection($shoppingListCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shoppingListCollectionTransfer->getShoppingLists());
        /** @var \Generated\Shared\Transfer\ShoppingListTransfer $foundShoppingListTransfer */
        $foundShoppingListTransfer = $shoppingListCollectionTransfer->getShoppingLists()->getIterator()->current();
        $this->assertSame($shoppingListTransfer->getIdShoppingListOrFail(), $foundShoppingListTransfer->getIdShoppingListOrFail());
        $this->assertNotSame($foundShoppingListTransfer->getCustomerReferenceOrFail(), $secondCompanyUserTransfer->getCustomerOrFail()->getCustomerReferenceOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionByCompanyBusinessUnitIds(): void
    {
        // Arrange
        $companyUserTransfer = $this->getCustomerUser();
        $shoppingListTransfer = $this->tester->createShoppingList($companyUserTransfer);
        $shoppingListPermissionGroupTransfer = $this->tester->haveShoppingListPermissionGroup(uniqid(), []);

        $secondCompanyUserTransfer = $this->getCustomerUser();

        $this->tester->haveShoppingListCompanyBusinessUnit(
            $secondCompanyUserTransfer,
            $shoppingListTransfer,
            $shoppingListPermissionGroupTransfer,
        );

        $thirdCompanyUserTransfer = $this->getCustomerUser();
        $this->tester->createShoppingList($thirdCompanyUserTransfer);

        $shoppingListConditionsTransfers = (new ShoppingListConditionsTransfer())
            ->setWithBusinessUnitSharedShoppingLists(true)
            ->addIdCompanyBusinessUnit($secondCompanyUserTransfer->getFkCompanyBusinessUnitOrFail())
            ->addCustomerReference($secondCompanyUserTransfer->getCustomerOrFail()->getCustomerReferenceOrFail());
        $shoppingListCriteriaTransfer = (new ShoppingListCriteriaTransfer())->setShoppingListConditions($shoppingListConditionsTransfers);

        // Act
        $shoppingListCollectionTransfer = $this->tester->getFacade()->getShoppingListCollection($shoppingListCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shoppingListCollectionTransfer->getShoppingLists());
        /** @var \Generated\Shared\Transfer\ShoppingListTransfer $foundShoppingListTransfer */
        $foundShoppingListTransfer = $shoppingListCollectionTransfer->getShoppingLists()->getIterator()->current();
        $this->assertSame($shoppingListTransfer->getIdShoppingListOrFail(), $foundShoppingListTransfer->getIdShoppingListOrFail());
        $this->assertNotSame($foundShoppingListTransfer->getCustomerReferenceOrFail(), $secondCompanyUserTransfer->getCustomerOrFail()->getCustomerReferenceOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionWithoutBlacklistedShoppingLists(): void
    {
        // Arrange
        $companyUserTransfer = $this->getCustomerUser();
        $shoppingListTransfer = $this->tester->createShoppingList($companyUserTransfer);
        $blacklistedShoppingListTransfer = $this->tester->createShoppingList($companyUserTransfer);
        $shoppingListPermissionGroupTransfer = $this->tester->haveShoppingListPermissionGroup(uniqid(), []);

        $this->tester->haveShoppingListCompanyBusinessUnit(
            $companyUserTransfer,
            $shoppingListTransfer,
            $shoppingListPermissionGroupTransfer,
        );

        $this->tester->createShoppingListCompanyBusinessUnitBlacklist(
            $companyUserTransfer,
            $blacklistedShoppingListTransfer,
        );

        $shoppingListConditionsTransfers = (new ShoppingListConditionsTransfer())
            ->setWithExcludedBlacklistedShoppingLists(true)
            ->addIdBlacklistCompanyUser($companyUserTransfer->getIdCompanyUserOrFail())
            ->addCustomerReference($companyUserTransfer->getCustomerOrFail()->getCustomerReferenceOrFail());
        $shoppingListCriteriaTransfer = (new ShoppingListCriteriaTransfer())->setShoppingListConditions($shoppingListConditionsTransfers);

        // Act
        $shoppingListCollectionTransfer = $this->tester->getFacade()->getShoppingListCollection($shoppingListCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shoppingListCollectionTransfer->getShoppingLists());
        /** @var \Generated\Shared\Transfer\ShoppingListTransfer $foundShoppingListTransfer */
        $foundShoppingListTransfer = $shoppingListCollectionTransfer->getShoppingLists()->getIterator()->current();
        $this->assertSame($shoppingListTransfer->getIdShoppingListOrFail(), $foundShoppingListTransfer->getIdShoppingListOrFail());
        $this->assertNotNull($foundShoppingListTransfer->getNumberOfItemsOrFail());
        $this->assertSame($foundShoppingListTransfer->getCustomerReferenceOrFail(), $companyUserTransfer->getCustomerOrFail()->getCustomerReferenceOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionWithBlacklistedShoppingLists(): void
    {
        // Arrange
        $companyUserTransfer = $this->getCustomerUser();
        $shoppingListTransfer = $this->tester->createShoppingList($companyUserTransfer);
        $blacklistedShoppingListTransfer = $this->tester->createShoppingList($companyUserTransfer);
        $shoppingListPermissionGroupTransfer = $this->tester->haveShoppingListPermissionGroup(uniqid(), []);

        $this->tester->haveShoppingListCompanyBusinessUnit(
            $companyUserTransfer,
            $shoppingListTransfer,
            $shoppingListPermissionGroupTransfer,
        );

        $this->tester->createShoppingListCompanyBusinessUnitBlacklist(
            $companyUserTransfer,
            $blacklistedShoppingListTransfer,
        );

        $shoppingListConditionsTransfers = (new ShoppingListConditionsTransfer())
            ->setWithExcludedBlacklistedShoppingLists(false)
            ->addIdBlacklistCompanyUser($companyUserTransfer->getIdCompanyUserOrFail())
            ->addCustomerReference($companyUserTransfer->getCustomerOrFail()->getCustomerReferenceOrFail());
        $shoppingListCriteriaTransfer = (new ShoppingListCriteriaTransfer())->setShoppingListConditions($shoppingListConditionsTransfers);

        // Act
        $shoppingListCollectionTransfer = $this->tester->getFacade()->getShoppingListCollection($shoppingListCriteriaTransfer);

        // Assert
        $this->assertCount(2, $shoppingListCollectionTransfer->getShoppingLists());
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionWithShoppingListItems(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $companyUserTransfer = $this->getCustomerUser();
        $shoppingListTransfer = $this->tester->createShoppingList($companyUserTransfer);
        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $companyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $productConcreteTransfer->getSku(),
        ]);
        $this->tester->getFacade()->addShoppingListItem($shoppingListItemTransfer);

        $shoppingListConditionsTransfers = (new ShoppingListConditionsTransfer())
            ->setWithShoppingListItems(true)
            ->addCustomerReference($companyUserTransfer->getCustomerOrFail()->getCustomerReferenceOrFail());
        $shoppingListCriteriaTransfer = (new ShoppingListCriteriaTransfer())->setShoppingListConditions($shoppingListConditionsTransfers);

        // Act
        $shoppingListCollectionTransfer = $this->tester->getFacade()->getShoppingListCollection($shoppingListCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shoppingListCollectionTransfer->getShoppingLists());
        /** @var \Generated\Shared\Transfer\ShoppingListTransfer $foundShoppingListTransfer */
        $foundShoppingListTransfer = $shoppingListCollectionTransfer->getShoppingLists()->getIterator()->current();
        $this->assertSame($shoppingListTransfer->getIdShoppingListOrFail(), $foundShoppingListTransfer->getIdShoppingListOrFail());
        $this->assertCount(1, $foundShoppingListTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionWithoutShoppingListItems(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $companyUserTransfer = $this->getCustomerUser();
        $shoppingListTransfer = $this->tester->createShoppingList($companyUserTransfer);
        $shoppingListItemTransfer = $this->tester->buildShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $companyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $productConcreteTransfer->getSku(),
        ]);
        $this->tester->getFacade()->addShoppingListItem($shoppingListItemTransfer);

        $shoppingListConditionsTransfers = (new ShoppingListConditionsTransfer())
            ->setWithShoppingListItems(false)
            ->addCustomerReference($companyUserTransfer->getCustomerOrFail()->getCustomerReferenceOrFail());
        $shoppingListCriteriaTransfer = (new ShoppingListCriteriaTransfer())->setShoppingListConditions($shoppingListConditionsTransfers);

        // Act
        $shoppingListCollectionTransfer = $this->tester->getFacade()->getShoppingListCollection($shoppingListCriteriaTransfer);

        // Assert
        $this->assertCount(1, $shoppingListCollectionTransfer->getShoppingLists());
        /** @var \Generated\Shared\Transfer\ShoppingListTransfer $foundShoppingListTransfer */
        $foundShoppingListTransfer = $shoppingListCollectionTransfer->getShoppingLists()->getIterator()->current();
        $this->assertSame($shoppingListTransfer->getIdShoppingListOrFail(), $foundShoppingListTransfer->getIdShoppingListOrFail());
        $this->assertCount(0, $foundShoppingListTransfer->getItems());
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function getCustomerUser(): CompanyUserTransfer
    {
        $companyTransfer = $this->tester->createCompany();
        $companyTransferBusinessUnit = $this->tester->createCompanyBusinessUnit($companyTransfer);
        $customerTransfer = $this->tester->haveCustomer();
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompanyOrFail(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyTransferBusinessUnit->getIdCompanyBusinessUnitOrFail(),
        ]);
        $companyUserTransfer->setCustomer($customerTransfer);

        return $companyUserTransfer;
    }
}
