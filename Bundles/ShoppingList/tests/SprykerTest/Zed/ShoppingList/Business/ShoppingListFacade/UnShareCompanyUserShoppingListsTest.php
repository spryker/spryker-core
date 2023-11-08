<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingList\Business\ShoppingListFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShoppingListShareRequestTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use SprykerTest\Zed\ShoppingList\ShoppingListBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShoppingList
 * @group Business
 * @group ShoppingListFacade
 * @group UnShareCompanyUserShoppingListsTest
 * Add your own group annotations below this line
 */
class UnShareCompanyUserShoppingListsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ShoppingList\ShoppingListBusinessTester
     */
    protected ShoppingListBusinessTester $tester;

    /**
     * @return void
     */
    public function testUnShareCompanyUserShoppingListDeletesAllCompanyBusinessUnitBlacklistsAndSharedShoppingLists(): void
    {
        // Arrange
        $companyUserTransfer1 = $this->tester->createCompanyUserForBusinessUnit();
        $companyUserTransfer2 = $this->tester->createCompanyUserForBusinessUnit();
        $companyUserTransfer3 = $this->tester->createCompanyUserForBusinessUnit();

        $shoppingListTransfer1 = $this->tester->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => $companyUserTransfer1->getCustomerOrFail()->getCustomerReference(),
            ShoppingListTransfer::ID_COMPANY_USER => $companyUserTransfer1->getIdCompanyUser(),
        ]);
        $shoppingListTransfer2 = $this->tester->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => $companyUserTransfer2->getCustomerOrFail()->getCustomerReference(),
            ShoppingListTransfer::ID_COMPANY_USER => $companyUserTransfer2->getIdCompanyUser(),
        ]);

        $this->tester->shareShoppingListWithCompanyUser($shoppingListTransfer1, $companyUserTransfer3);
        $this->tester->shareShoppingListWithCompanyUser($shoppingListTransfer2, $companyUserTransfer3);

        $shoppingListCompanyBusinessUnitBlacklistTransfer1 = $this->tester
            ->createShoppingListCompanyBusinessUnitBlacklist(
                $companyUserTransfer3,
                $shoppingListTransfer1,
            );
        $shoppingListCompanyBusinessUnitBlacklistTransfer2 = $this->tester
            ->createShoppingListCompanyBusinessUnitBlacklist(
                $companyUserTransfer3,
                $shoppingListTransfer2,
            );

        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
            ->setIdCompanyUser($companyUserTransfer3->getIdCompanyUser())
            ->setWithCompanyBusinessUnitBlacklists(true);

        // Act
        $this->tester->getFacade()->unShareCompanyUserShoppingLists($shoppingListShareRequestTransfer);

        $shoppingListCompanyBusinessUnitBlacklistTransfers = $this->tester->findShoppingListCompanyBusinessUnitBlacklists(
            [
                $shoppingListCompanyBusinessUnitBlacklistTransfer1->getIdShoppingListCompanyBusinessUnitBlacklistOrFail(),
                $shoppingListCompanyBusinessUnitBlacklistTransfer2->getIdShoppingListCompanyBusinessUnitBlacklistOrFail(),
            ],
        );
        $shoppingListCompanyUserCollectionTransfer = $this->tester->findShoppingListCompanyUsers(
            $companyUserTransfer3->getIdCompanyUserOrFail(),
        );
        $persistedShoppingListTransfer1 = $this->tester->findShoppingList($shoppingListTransfer1);
        $persistedShoppingListTransfer2 = $this->tester->findShoppingList($shoppingListTransfer2);

        // Assert
        $this->assertEmpty($shoppingListCompanyBusinessUnitBlacklistTransfers);
        $this->assertEmpty($shoppingListCompanyUserCollectionTransfer->getShoppingListCompanyUsers());
        $this->assertNotNull($persistedShoppingListTransfer1);
        $this->assertNotNull($persistedShoppingListTransfer2);
    }

    /**
     * @return void
     */
    public function testUnShareCompanyUserShoppingListDeletesSharedShoppingListsButNotCompanyBusinessUnitBlacklists(): void
    {
        // Arrange
        $companyUserTransfer1 = $this->tester->createCompanyUserForBusinessUnit();
        $companyUserTransfer2 = $this->tester->createCompanyUserForBusinessUnit();
        $companyUserTransfer3 = $this->tester->createCompanyUserForBusinessUnit();

        $shoppingListTransfer1 = $this->tester->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => $companyUserTransfer1->getCustomerOrFail()->getCustomerReference(),
            ShoppingListTransfer::ID_COMPANY_USER => $companyUserTransfer1->getIdCompanyUser(),
        ]);
        $shoppingListTransfer2 = $this->tester->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => $companyUserTransfer2->getCustomerOrFail()->getCustomerReference(),
            ShoppingListTransfer::ID_COMPANY_USER => $companyUserTransfer2->getIdCompanyUser(),
        ]);

        $this->tester->shareShoppingListWithCompanyUser($shoppingListTransfer1, $companyUserTransfer3);
        $this->tester->shareShoppingListWithCompanyUser($shoppingListTransfer2, $companyUserTransfer3);

        $shoppingListCompanyBusinessUnitBlacklistTransfer1 = $this->tester
            ->createShoppingListCompanyBusinessUnitBlacklist(
                $companyUserTransfer3,
                $shoppingListTransfer1,
            );
        $shoppingListCompanyBusinessUnitBlacklistTransfer2 = $this->tester
            ->createShoppingListCompanyBusinessUnitBlacklist(
                $companyUserTransfer3,
                $shoppingListTransfer2,
            );

        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
            ->setIdCompanyUser($companyUserTransfer3->getIdCompanyUser());

        // Act
        $this->tester->getFacade()->unShareCompanyUserShoppingLists($shoppingListShareRequestTransfer);

        $shoppingListCompanyBusinessUnitBlacklistTransfers = $this->tester->findShoppingListCompanyBusinessUnitBlacklists(
            [
                $shoppingListCompanyBusinessUnitBlacklistTransfer1->getIdShoppingListCompanyBusinessUnitBlacklistOrFail(),
                $shoppingListCompanyBusinessUnitBlacklistTransfer2->getIdShoppingListCompanyBusinessUnitBlacklistOrFail(),
            ],
        );
        $shopppingListCompanyUserCollectionTransfer = $this->tester->findShoppingListCompanyUsers(
            $companyUserTransfer3->getIdCompanyUserOrFail(),
        );
        $persistedShoppingListTransfer1 = $this->tester->findShoppingList($shoppingListTransfer1);
        $persistedShoppingListTransfer2 = $this->tester->findShoppingList($shoppingListTransfer2);

        // Assert
        $this->assertEquals(
            [$shoppingListCompanyBusinessUnitBlacklistTransfer1, $shoppingListCompanyBusinessUnitBlacklistTransfer2],
            $shoppingListCompanyBusinessUnitBlacklistTransfers,
        );
        $this->assertEmpty($shopppingListCompanyUserCollectionTransfer->getShoppingListCompanyUsers());
        $this->assertNotNull($persistedShoppingListTransfer1);
        $this->assertNotNull($persistedShoppingListTransfer2);
    }

    /**
     * @return void
     */
    public function testUnShareCompanyUserShoppingListShouldUnShareShoppingListOnlyForRequestedUser(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserForBusinessUnit();
        $companyUserTransfer2 = $this->tester->createCompanyUserForBusinessUnit();
        $companyUserTransfer3 = $this->tester->createCompanyUserForBusinessUnit();

        $shoppingListTransfer = $this->tester->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => $companyUserTransfer->getCustomerOrFail()->getCustomerReference(),
            ShoppingListTransfer::ID_COMPANY_USER => $companyUserTransfer->getIdCompanyUser(),
        ]);

        $this->tester->shareShoppingListWithCompanyUser($shoppingListTransfer, $companyUserTransfer2);
        $this->tester->shareShoppingListWithCompanyUser($shoppingListTransfer, $companyUserTransfer3);

        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
            ->setIdCompanyUser($companyUserTransfer3->getIdCompanyUser());

        // Act
        $this->tester->getFacade()->unShareCompanyUserShoppingLists($shoppingListShareRequestTransfer);

        $shoppingListCompanyUserCollectionTransfer2 = $this->tester->findShoppingListCompanyUsers(
            $companyUserTransfer2->getIdCompanyUserOrFail(),
        );
        $shoppingListCompanyUserCollectionTransfer3 = $this->tester->findShoppingListCompanyUsers(
            $companyUserTransfer3->getIdCompanyUserOrFail(),
        );

        // Assert
        $this->assertCount(1, $shoppingListCompanyUserCollectionTransfer2->getShoppingListCompanyUsers());
        $this->assertCount(0, $shoppingListCompanyUserCollectionTransfer3->getShoppingListCompanyUsers());
    }
}
