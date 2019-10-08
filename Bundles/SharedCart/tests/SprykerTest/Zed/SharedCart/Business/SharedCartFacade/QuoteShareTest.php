<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCart\Business\SharedCartFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MultiCart\Communication\Plugin\AddDefaultNameBeforeQuoteSavePlugin;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use Spryker\Zed\Quote\QuoteDependencyProvider;
use Spryker\Zed\SharedCart\Communication\Plugin\QuotePermissionStoragePlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\UpdateShareDetailsQuoteAfterSavePlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\WriteSharedCartPermissionPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SharedCart
 * @group Business
 * @group SharedCartFacade
 * @group QuoteShareTest
 * Add your own group annotations below this line
 */
class QuoteShareTest extends Unit
{
    protected const READ_ONLY = 'READ_ONLY';
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var \SprykerTest\Zed\SharedCart\SharedCartBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION_STORAGE, [
            new QuotePermissionStoragePlugin(),
        ]);

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION, [
            new ReadSharedCartPermissionPlugin(),
            new WriteSharedCartPermissionPlugin(),
        ]);

        $this->tester->setDependency(QuoteDependencyProvider::PLUGINS_QUOTE_CREATE_BEFORE, [
            new AddDefaultNameBeforeQuoteSavePlugin(),
        ]);
        $this->tester->setDependency(QuoteDependencyProvider::PLUGINS_QUOTE_CREATE_AFTER, [
            new UpdateShareDetailsQuoteAfterSavePlugin(),
        ]);

        $this->tester->setDependency(QuoteDependencyProvider::PLUGINS_QUOTE_UPDATE_BEFORE, [
            new AddDefaultNameBeforeQuoteSavePlugin(),
        ]);
        $this->tester->setDependency(QuoteDependencyProvider::PLUGINS_QUOTE_UPDATE_AFTER, [
            new UpdateShareDetailsQuoteAfterSavePlugin(),
        ]);

        $this->tester->getFacade()->installSharedCartPermissions();
    }

    /**
     * @return void
     */
    public function testCompanyUserShouldNotHaveAccessToForeignCartsByDefault()
    {
        // Arrange
        $customerTransfer1 = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->createQuote($customerTransfer1);

        $customerTransfer2 = $this->tester->haveCustomer();
        $this->tester->createCompanyUser($customerTransfer2);

        // Act
        $actualQuoteResponseTransfer = $this->tester->getLocator()->persistentCart()->facade()->findQuote($quoteTransfer->getIdQuote(), $customerTransfer2);

        // Assert
        $this->assertFalse($actualQuoteResponseTransfer->getIsSuccessful(), 'Company user shouldn\'t have been able to read the quote from database.');
    }

    /**
     * @return void
     */
    public function testShareCartWithCompanyUserShouldGiveAccessToRead()
    {
        // Arrange
        $customerTransfer1 = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->createQuote($customerTransfer1);

        $customerTransfer2 = $this->tester->haveCustomer();
        $companyUserTransfer = $this->tester->createCompanyUser($customerTransfer2);

        // Act
        $quoteTransfer->addShareDetail(
            $this->tester->createShareCartDetail(
                $companyUserTransfer->getIdCompanyUser(),
                $this->findQuotePermissionGroup(static::READ_ONLY)
            )
        );
        $this->tester->getLocator()->quote()->facade()->updateQuote($quoteTransfer);

        $actualQuoteResponseTransfer = $this->tester->getLocator()->persistentCart()->facade()->findQuote($quoteTransfer->getIdQuote(), $customerTransfer2);

        // Assert
        $this->assertInstanceOf(QuoteTransfer::class, $actualQuoteResponseTransfer->getQuoteTransfer(), 'Company user should have been able to read the quote from database.');
    }

    /**
     * @return void
     */
    public function testRemoveCompanyUserPermissionGroupShouldDisallowToRead()
    {
        // Arrange
        $customerTransfer1 = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->createQuote($customerTransfer1);

        $customerTransfer2 = $this->tester->haveCustomer();
        $companyUserTransfer = $this->tester->createCompanyUser($customerTransfer2);

        // Act
        $quoteTransfer->addShareDetail(
            $this->tester->createShareCartDetail(
                $companyUserTransfer->getIdCompanyUser(),
                $this->findQuotePermissionGroup(static::READ_ONLY)
            )
        );
        $this->tester->getLocator()->quote()->facade()->updateQuote($quoteTransfer);

        // Act 2
        $quoteTransfer->setShareDetails(new ArrayObject());
        $this->tester->getLocator()->quote()->facade()->updateQuote($quoteTransfer);

        $actualQuoteResponseTransfer = $this->tester->getLocator()->persistentCart()->facade()->findQuote($quoteTransfer->getIdQuote(), $customerTransfer2);

        // Assert
        $this->assertFalse($actualQuoteResponseTransfer->getIsSuccessful(), 'Company user shouldn\'t have been able to read the quote from database.');
    }

    /**
     * @return void
     */
    public function testExpandQuoteCollectionWithCustomerSharedQuoteCollectionWillExpandQuoteCollectionWithSharedQuotes(): void
    {
        // Arrange
        $ownerCustomerTransfer = $this->tester->haveCustomer();
        $customerTransfer = $this->tester->haveCustomer();
        $companyTransfer = $this->tester->haveCompany();
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => static::STORE_NAME_DE,
        ]);
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER_REFERENCE => $ownerCustomerTransfer->getCustomerReference(),
            QuoteTransfer::CUSTOMER => $ownerCustomerTransfer,
            QuoteTransfer::STORE => $storeTransfer,
        ]);
        $spyQuotePermissionGroupEntityTransfer = $this->tester->haveQuotePermissionGroup(
            static::READ_ONLY,
            [ReadSharedCartPermissionPlugin::KEY]
        );
        $this->tester->haveQuoteCompanyUser(
            $companyUserTransfer,
            $quoteTransfer,
            $spyQuotePermissionGroupEntityTransfer
        );

        // Act
        $quoteCollectionTransfer = $this->tester->getFacade()->expandQuoteCollectionWithCustomerSharedQuoteCollection(
            (new QuoteCollectionTransfer()),
            (new QuoteCriteriaFilterTransfer())
                ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser())
                ->setIdStore($storeTransfer->getIdStore())
        );

        // Assert
        $this->assertCount(1, $quoteCollectionTransfer->getQuotes());
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $quoteCollectionTransfer->getQuotes()->offsetGet(0);
        $this->assertNotNull($quoteTransfer->getQuotePermissionGroup());
        $this->assertEquals(
            $spyQuotePermissionGroupEntityTransfer->getIdQuotePermissionGroup(),
            $quoteTransfer->getQuotePermissionGroup()->getIdQuotePermissionGroup()
        );
        $this->assertEquals($spyQuotePermissionGroupEntityTransfer->getName(), $quoteTransfer->getQuotePermissionGroup()->getName());
    }

    /**
     * @param string $permissionQuoteGroupName
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer|null
     */
    protected function findQuotePermissionGroup(string $permissionQuoteGroupName): ?QuotePermissionGroupTransfer
    {
        $criteriaFilterTransfer = new QuotePermissionGroupCriteriaFilterTransfer();
        $criteriaFilterTransfer->setName($permissionQuoteGroupName);

        $quotePermissionGroupTransferList = $this->tester->getFacade()->getQuotePermissionGroupList($criteriaFilterTransfer);

        return $quotePermissionGroupTransferList->getQuotePermissionGroups()->offsetGet(0);
    }
}
