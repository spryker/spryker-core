<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCartsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteCompanyUserTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer;
use Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SharedCartsRestApi
 * @group Business
 * @group Facade
 * @group SharedCartsRestApiFacadeTest
 * Add your own group annotations below this line
 */
class SharedCartsRestApiFacadeTest extends Unit
{
    protected const ID_QUOTE_PERMISSION_GROUP_READ = 1;
    protected const ID_QUOTE_PERMISSION_GROUP_FULL_ACCESS = 2;

    /**
     * @var \SprykerTest\Zed\SharedCartsRestApi\SharedCartsRestApiFacadeTester
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
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

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
    public function testCreateShouldCreateQuoteCompanyUser(): void
    {
        //Arrange
        $quotePermissionGroup = $this->createQuotePermissionTransfer(static::ID_QUOTE_PERMISSION_GROUP_READ);
        $shareDetailTransfer = (new ShareDetailTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setQuotePermissionGroup($quotePermissionGroup);
        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->addShareDetail($shareDetailTransfer)
            ->setQuoteUuid($this->quoteTransfer->getUuid())
            ->setCustomerReference($this->quoteTransfer->getCustomerReference());

        //Act
        $shareCartResponseTransfer = $this->tester->getFacade()->create($shareCartRequestTransfer);

        //Assert
        $this->assertCount(1, $shareCartResponseTransfer->getShareDetails());
        /** @var \Generated\Shared\Transfer\ShareDetailTransfer $shareDetailTransfer */
        $shareDetailTransfer = $shareCartResponseTransfer->getShareDetails()->offsetGet(0);
        $this->assertEquals($shareDetailTransfer->getIdCompanyUser(), $this->companyUserTransfer->getIdCompanyUser());
        $this->assertEquals(static::ID_QUOTE_PERMISSION_GROUP_READ, $shareDetailTransfer->getQuotePermissionGroup()->getIdQuotePermissionGroup());
        $this->assertNotNull($shareDetailTransfer->getUuid());
    }

    /**
     * @return void
     */
    public function testUpdateShouldUpdateQuotePermissionGroupForQuoteCompanyUser(): void
    {
        //Arrange
        $quoteCompanyUserTransfer = $this->tester->haveQuoteCompanyUser(
            $this->companyUserTransfer,
            $this->quoteTransfer,
            (new SpyQuotePermissionGroupEntityTransfer())->setIdQuotePermissionGroup(static::ID_QUOTE_PERMISSION_GROUP_READ)
        );

        $quotePermissionGroupTransfer = $this->createQuotePermissionTransfer(static::ID_QUOTE_PERMISSION_GROUP_FULL_ACCESS);
        $shareDetailTransfer = (new ShareDetailTransfer())
            ->setQuotePermissionGroup($quotePermissionGroupTransfer)
            ->setUuid($quoteCompanyUserTransfer->getUuid());
        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->addShareDetail($shareDetailTransfer)
            ->setCustomerReference($this->quoteTransfer->getCustomerReference());

        //Act
        $shareCartResponseTransfer = $this->tester->getFacade()->update($shareCartRequestTransfer);

        //Assert
        $this->assertCount(1, $shareCartResponseTransfer->getShareDetails());
        /** @var \Generated\Shared\Transfer\ShareDetailTransfer $shareDetailTransfer */
        $shareDetailTransfer = $shareCartResponseTransfer->getShareDetails()->offsetGet(0);
        $this->assertEquals($shareDetailTransfer->getIdCompanyUser(), $this->companyUserTransfer->getIdCompanyUser());
        $this->assertEquals(static::ID_QUOTE_PERMISSION_GROUP_FULL_ACCESS, $shareDetailTransfer->getQuotePermissionGroup()->getIdQuotePermissionGroup());
        $this->assertNotNull($shareDetailTransfer->getUuid());
    }

    /**
     * @return void
     */
    public function testDeleteShouldDeleteQuoteCompanyUser(): void
    {
        //Arrange
        $quoteCompanyUserEntityTransfer = $this->tester->haveQuoteCompanyUser(
            $this->companyUserTransfer,
            $this->quoteTransfer,
            (new SpyQuotePermissionGroupEntityTransfer())->setIdQuotePermissionGroup(static::ID_QUOTE_PERMISSION_GROUP_READ)
        );

        $shareDetailTransfer = (new ShareDetailTransfer())
            ->setUuid($quoteCompanyUserEntityTransfer->getUuid());
        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->addShareDetail($shareDetailTransfer)
            ->setCustomerReference($this->quoteTransfer->getCustomerReference());

        //Act
        $shareCartResponseTransfer = $this->tester->getFacade()->delete($shareCartRequestTransfer);
        $shareDetailCollectionTransfer = $this->getSharedCartFacade()->findQuoteCompanyUserByUuid(
            (new QuoteCompanyUserTransfer())->fromArray($quoteCompanyUserEntityTransfer->toArray(), true)
        );

        //Assert
        $this->assertCount(0, $shareCartResponseTransfer->getShareDetails());
        $this->assertNull($shareDetailCollectionTransfer);
    }

    /**
     * @return \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface
     */
    protected function getSharedCartFacade(): SharedCartFacadeInterface
    {
        return $this->tester->getLocator()->sharedCart()->facade();
    }

    /**
     * @param int $idQuotePermissionGroup
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer
     */
    protected function createQuotePermissionTransfer(int $idQuotePermissionGroup): QuotePermissionGroupTransfer
    {
        return (new QuotePermissionGroupTransfer())->setIdQuotePermissionGroup($idQuotePermissionGroup);
    }
}
