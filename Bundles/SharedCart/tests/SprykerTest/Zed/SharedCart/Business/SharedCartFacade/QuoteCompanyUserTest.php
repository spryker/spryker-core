<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCart\Business\SharedCartFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailCriteriaFilterTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Zed\SharedCart\Communication\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\WriteSharedCartPermissionPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group SharedCart
 * @group Business
 * @group SharedCartFacade
 * @group QuoteCompanyUserTest
 * Add your own group annotations below this line
 */
class QuoteCompanyUserTest extends Unit
{
    protected const QUOTE_PERMISSION_GROUP_READ_ONLY = 'READ_ONLY';
    protected const QUOTE_PERMISSION_GROUP_FULL_ACCESS = 'FULL_ACCESS';

    /**
     * @var \SprykerTest\Zed\SharedCart\SharedCartBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $companyUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer
     */
    protected $readOnlyQuotePermissionGroup;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->readOnlyQuotePermissionGroup = $this->tester->haveQuotePermissionGroup(static::QUOTE_PERMISSION_GROUP_READ_ONLY, [
            ReadSharedCartPermissionPlugin::KEY,
        ]);

        $companyTransfer = $this->tester->haveCompany();
        $ownerCustomerTransfer = $this->tester->haveCustomer();
        $this->quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER_REFERENCE => $ownerCustomerTransfer->getIdCustomer(),
            QuoteTransfer::CUSTOMER => $ownerCustomerTransfer,
        ]);

        $otherCustomerTransfer = $this->tester->haveCustomer();
        $this->companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_CUSTOMER => $otherCustomerTransfer->getIdCustomer(),
            CompanyUserTransfer::CUSTOMER => $otherCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
    }

    /**
     * @return void
     */
    public function testFindShareDetailsCollectionByShareDetailCriteriaShouldReturnShareDetailCollectionWithOneShareDetail(): void
    {
        //Arrange
        $quoteCompanyUserTransfer = $this->tester->haveQuoteCompanyUser(
            $this->companyUserTransfer,
            $this->quoteTransfer,
            $this->readOnlyQuotePermissionGroup
        );

        //Act
        $shareDetailCollectionTransfer = $this->tester->getFacade()
            ->getShareDetailCollectionByShareDetailCriteria($this->getShareDetailCriteriaTransfer());

        //Assert
        $this->assertCount(1, $shareDetailCollectionTransfer->getShareDetails());
        /** @var \Generated\Shared\Transfer\ShareDetailTransfer $shareDetailTransfer */
        $shareDetailTransfer = $shareDetailCollectionTransfer->getShareDetails()->offsetGet(0);
        $this->assertEquals($quoteCompanyUserTransfer->getIdQuoteCompanyUser(), $shareDetailTransfer->getIdQuoteCompanyUser());
    }

    /**
     * @return void
     */
    public function testUpdateQuoteCompanyUserPermissionGroupShouldUpdateQuotePermissionGroupForQuoteCompanyUser(): void
    {
        //Arrange
        $quoteCompanyUserTransfer = $this->tester->haveQuoteCompanyUser(
            $this->companyUserTransfer,
            $this->quoteTransfer,
            $this->readOnlyQuotePermissionGroup
        );

        $fullAccessQuotePermissionGroup = $this->tester->haveQuotePermissionGroup(static::QUOTE_PERMISSION_GROUP_FULL_ACCESS, [
            ReadSharedCartPermissionPlugin::KEY,
            WriteSharedCartPermissionPlugin::KEY,
        ]);
        $quotePermissionGroupTransfer = (new QuotePermissionGroupTransfer())->fromArray(
            $fullAccessQuotePermissionGroup->toArray(),
            true
        );
        $shareDetailTransfer = (new ShareDetailTransfer())
            ->setQuotePermissionGroup($quotePermissionGroupTransfer)
            ->setIdQuoteCompanyUser($quoteCompanyUserTransfer->getIdQuoteCompanyUser());
        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->addShareDetail($shareDetailTransfer);

        //Act
        $this->tester->getFacade()->updateQuoteCompanyUserPermissionGroup($shareCartRequestTransfer);
        $shareDetailCollectionTransfer = $this->tester->getFacade()
            ->getShareDetailCollectionByShareDetailCriteria($this->getShareDetailCriteriaTransfer());

        //Assert
        $this->assertCount(1, $shareDetailCollectionTransfer->getShareDetails());
        /** @var \Generated\Shared\Transfer\ShareDetailTransfer $shareDetailTransfer */
        $shareDetailTransfer = $shareDetailCollectionTransfer->getShareDetails()->offsetGet(0);
        $this->assertEquals($shareDetailTransfer->getIdCompanyUser(), $this->companyUserTransfer->getIdCompanyUser());
        $this->assertEquals($fullAccessQuotePermissionGroup->getIdQuotePermissionGroup(), $shareDetailTransfer->getQuotePermissionGroup()->getIdQuotePermissionGroup());
    }

    /**
     * @return void
     */
    public function testDeleteQuoteCompanyUserShouldDeleteQuoteCompanyUser(): void
    {
        //Arrange
        $quoteCompanyUserTransfer = $this->tester->haveQuoteCompanyUser(
            $this->companyUserTransfer,
            $this->quoteTransfer,
            $this->readOnlyQuotePermissionGroup
        );

        $shareDetailTransfer = (new ShareDetailTransfer())
            ->setIdQuoteCompanyUser($quoteCompanyUserTransfer->getIdQuoteCompanyUser());
        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->addShareDetail($shareDetailTransfer);

        //Act
        $this->tester->getFacade()->deleteQuoteCompanyUser($shareCartRequestTransfer);
        $shareDetailCollectionTransfer = $this->tester->getFacade()
            ->getShareDetailCollectionByShareDetailCriteria($this->getShareDetailCriteriaTransfer());

        //Assert
        $this->assertCount(0, $shareDetailCollectionTransfer->getShareDetails());
    }

    /**
     * @return \Generated\Shared\Transfer\ShareDetailCriteriaFilterTransfer
     */
    protected function getShareDetailCriteriaTransfer(): ShareDetailCriteriaFilterTransfer
    {
        return (new ShareDetailCriteriaFilterTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setIdQuote($this->quoteTransfer->getIdQuote());
    }
}
