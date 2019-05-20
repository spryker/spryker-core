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
use Spryker\Shared\SharedCartsRestApi\SharedCartsRestApiConfig;
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
    protected const QUOTE_PERMISSION_GROUP_READ_ONLY = 'READ_ONLY';
    protected const QUOTE_PERMISSION_GROUP_FULL_ACCESS = 'FULL_ACCESS';

    protected const READ_SHARED_CART_PERMISSION_PLUGIN_KEY = 'ReadSharedCartPermissionPlugin';
    protected const WRITE_SHARED_CART_PERMISSION_PLUGIN_KEY = 'WriteSharedCartPermissionPlugin';

    protected const WRONG_QUOTE_UUID = 'WRONG_QUOTE_UUID';
    protected const WRONG_CUSTOMER_REFERENCE = 'WRONG_CUSTOMER_REFERENCE';
    protected const WRONG_QUOTE_COMPANY_USER_UUID = 'WRONG_QUOTE_COMPANY_USER_UUID';

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
            static::READ_SHARED_CART_PERMISSION_PLUGIN_KEY,
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
    public function testCreateShouldCreateQuoteCompanyUser(): void
    {
        //Arrange
        $quotePermissionGroupTransfer = (new QuotePermissionGroupTransfer())->fromArray(
            $this->readOnlyQuotePermissionGroup->toArray(),
            true
        );
        $shareDetailTransfer = (new ShareDetailTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setQuotePermissionGroup($quotePermissionGroupTransfer);
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
        $this->assertEquals($quotePermissionGroupTransfer->getIdQuotePermissionGroup(), $shareDetailTransfer->getQuotePermissionGroup()->getIdQuotePermissionGroup());
        $this->assertNotNull($shareDetailTransfer->getUuid());
    }

    /**
     * @return void
     */
    public function testCreateShouldReturnErrorIfQuoteNotFound(): void
    {
        //Arrange
        $quotePermissionGroupTransfer = (new QuotePermissionGroupTransfer())->fromArray(
            $this->readOnlyQuotePermissionGroup->toArray(),
            true
        );
        $shareDetailTransfer = (new ShareDetailTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setQuotePermissionGroup($quotePermissionGroupTransfer);
        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->addShareDetail($shareDetailTransfer)
            ->setQuoteUuid(static::WRONG_QUOTE_UUID)
            ->setCustomerReference($this->quoteTransfer->getCustomerReference());

        //Act
        $shareCartResponseTransfer = $this->tester->getFacade()->create($shareCartRequestTransfer);

        //Assert
        $this->assertCount(0, $shareCartResponseTransfer->getShareDetails());
        $this->assertEquals(SharedCartsRestApiConfig::ERROR_IDENTIFIER_QUOTE_NOT_FOUND, $shareCartResponseTransfer->getErrorIdentifier());
    }

    /**
     * @return void
     */
    public function testCreateShouldReturnErrorIfCustomerDoesNotOwnQuote(): void
    {
        //Arrange
        $quotePermissionGroupTransfer = (new QuotePermissionGroupTransfer())->fromArray(
            $this->readOnlyQuotePermissionGroup->toArray(),
            true
        );
        $shareDetailTransfer = (new ShareDetailTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setQuotePermissionGroup($quotePermissionGroupTransfer);
        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->addShareDetail($shareDetailTransfer)
            ->setQuoteUuid($this->quoteTransfer->getUuid())
            ->setCustomerReference(static::WRONG_CUSTOMER_REFERENCE);

        //Act
        $shareCartResponseTransfer = $this->tester->getFacade()->create($shareCartRequestTransfer);

        //Assert
        $this->assertCount(0, $shareCartResponseTransfer->getShareDetails());
        $this->assertEquals(SharedCartsRestApiConfig::ERROR_IDENTIFIER_ACTION_FORBIDDEN, $shareCartResponseTransfer->getErrorIdentifier());
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
            $this->readOnlyQuotePermissionGroup
        );

        $fullAccessQuotePermissionGroup = $this->tester->haveQuotePermissionGroup(static::QUOTE_PERMISSION_GROUP_FULL_ACCESS, [
            static::READ_SHARED_CART_PERMISSION_PLUGIN_KEY,
            static::WRITE_SHARED_CART_PERMISSION_PLUGIN_KEY,
        ]);
        $quotePermissionGroupTransfer = (new QuotePermissionGroupTransfer())->fromArray(
            $fullAccessQuotePermissionGroup->toArray(),
            true
        );
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
        $this->assertEquals($quotePermissionGroupTransfer->getIdQuotePermissionGroup(), $shareDetailTransfer->getQuotePermissionGroup()->getIdQuotePermissionGroup());
        $this->assertNotNull($shareDetailTransfer->getUuid());
    }

    /**
     * @return void
     */
    public function testUpdateShouldReturnErrorIfQuoteCompanyUserNotFound(): void
    {
        //Arrange
        $fullAccessQuotePermissionGroup = $this->tester->haveQuotePermissionGroup(static::QUOTE_PERMISSION_GROUP_FULL_ACCESS, [
            static::READ_SHARED_CART_PERMISSION_PLUGIN_KEY,
            static::WRITE_SHARED_CART_PERMISSION_PLUGIN_KEY,
        ]);
        $quotePermissionGroupTransfer = (new QuotePermissionGroupTransfer())->fromArray(
            $fullAccessQuotePermissionGroup->toArray(),
            true
        );
        $shareDetailTransfer = (new ShareDetailTransfer())
            ->setQuotePermissionGroup($quotePermissionGroupTransfer)
            ->setUuid(static::WRONG_QUOTE_COMPANY_USER_UUID);
        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->addShareDetail($shareDetailTransfer)
            ->setCustomerReference($this->quoteTransfer->getCustomerReference());

        //Act
        $shareCartResponseTransfer = $this->tester->getFacade()->update($shareCartRequestTransfer);

        //Assert
        $this->assertCount(0, $shareCartResponseTransfer->getShareDetails());
        $this->assertEquals(SharedCartsRestApiConfig::ERROR_IDENTIFIER_SHARED_CART_NOT_FOUND, $shareCartResponseTransfer->getErrorIdentifier());
    }

    /**
     * @return void
     */
    public function testUpdateShouldReturnErrorIfCustomerDoesNotOwnQuote(): void
    {
        //Arrange
        $quoteCompanyUserTransfer = $this->tester->haveQuoteCompanyUser(
            $this->companyUserTransfer,
            $this->quoteTransfer,
            $this->readOnlyQuotePermissionGroup
        );

        $fullAccessQuotePermissionGroup = $this->tester->haveQuotePermissionGroup(static::QUOTE_PERMISSION_GROUP_FULL_ACCESS, [
            static::READ_SHARED_CART_PERMISSION_PLUGIN_KEY,
            static::WRITE_SHARED_CART_PERMISSION_PLUGIN_KEY,
        ]);
        $quotePermissionGroupTransfer = (new QuotePermissionGroupTransfer())->fromArray(
            $fullAccessQuotePermissionGroup->toArray(),
            true
        );
        $shareDetailTransfer = (new ShareDetailTransfer())
            ->setQuotePermissionGroup($quotePermissionGroupTransfer)
            ->setUuid($quoteCompanyUserTransfer->getUuid());
        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->addShareDetail($shareDetailTransfer)
            ->setCustomerReference(static::WRONG_CUSTOMER_REFERENCE);

        //Act
        $shareCartResponseTransfer = $this->tester->getFacade()->update($shareCartRequestTransfer);

        //Assert
        $this->assertCount(0, $shareCartResponseTransfer->getShareDetails());
        $this->assertEquals(SharedCartsRestApiConfig::ERROR_IDENTIFIER_ACTION_FORBIDDEN, $shareCartResponseTransfer->getErrorIdentifier());
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
            $this->readOnlyQuotePermissionGroup
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
     * @return void
     */
    public function testDeleteShouldReturnErrorIfQuoteCompanyUserNotFound(): void
    {
        //Arrange
        $shareDetailTransfer = (new ShareDetailTransfer())
            ->setUuid(static::WRONG_QUOTE_COMPANY_USER_UUID);
        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->addShareDetail($shareDetailTransfer)
            ->setCustomerReference($this->quoteTransfer->getCustomerReference());

        //Act
        $shareCartResponseTransfer = $this->tester->getFacade()->delete($shareCartRequestTransfer);

        //Assert
        $this->assertCount(0, $shareCartResponseTransfer->getShareDetails());
        $this->assertEquals(SharedCartsRestApiConfig::ERROR_IDENTIFIER_SHARED_CART_NOT_FOUND, $shareCartResponseTransfer->getErrorIdentifier());
    }

    /**
     * @return void
     */
    public function testDeleteShouldReturnErrorIfCustomerDoesNotOwnQuote(): void
    {
        //Arrange
        $quoteCompanyUserEntityTransfer = $this->tester->haveQuoteCompanyUser(
            $this->companyUserTransfer,
            $this->quoteTransfer,
            $this->readOnlyQuotePermissionGroup
        );

        $shareDetailTransfer = (new ShareDetailTransfer())
            ->setUuid($quoteCompanyUserEntityTransfer->getUuid());
        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->addShareDetail($shareDetailTransfer)
            ->setCustomerReference(static::WRONG_CUSTOMER_REFERENCE);

        //Act
        $shareCartResponseTransfer = $this->tester->getFacade()->delete($shareCartRequestTransfer);

        //Assert
        $this->assertCount(0, $shareCartResponseTransfer->getShareDetails());
        $this->assertEquals(SharedCartsRestApiConfig::ERROR_IDENTIFIER_ACTION_FORBIDDEN, $shareCartResponseTransfer->getErrorIdentifier());
    }

    /**
     * @return \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface
     */
    protected function getSharedCartFacade(): SharedCartFacadeInterface
    {
        return $this->tester->getLocator()->sharedCart()->facade();
    }
}
