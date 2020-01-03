<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCartsRestApi\Business;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteCompanyUserTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
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
class SharedCartsRestApiFacadeTest extends Test
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
    protected $ownerCompanyUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $otherCompanyUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $companyUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\SpyQuoteCompanyUserEntityTransfer
     */
    protected $quoteCompanyUserEntityTransfer;

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

        $this->tester->setPermissionDependencies();
    }

    /**
     * @return void
     */
    public function testGetSharedCartsByCartUuidShouldReturnShareData(): void
    {
        // Assign
        /** @var \Spryker\Zed\SharedCartsRestApi\Business\SharedCartsRestApiFacadeInterface $sharedCartsRestApiFacade */
        $sharedCartsRestApiFacade = $this->tester->getFacade();

        $companyTransfer = $this->tester->haveCompany();

        $ownerCustomerTransfer = $this->tester->haveCustomer();
        $this->ownerCompanyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $ownerCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $otherCustomerTransfer = $this->tester->haveCustomer();
        $this->otherCompanyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $otherCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $ownerCustomerTransfer,
        ]);

        $this->quoteCompanyUserEntityTransfer = $this->tester->haveQuoteCompanyUser(
            $this->otherCompanyUserTransfer,
            $quoteTransfer,
            $this->readOnlyQuotePermissionGroup
        );

        // Act
        $shareDetailCollectionTransfer = $sharedCartsRestApiFacade->getSharedCartsByCartUuid($quoteTransfer);

        // Assert
        $this->assertCount(1, $shareDetailCollectionTransfer->getShareDetails());
        $shareDetailTransfer = $shareDetailCollectionTransfer->getShareDetails()[0];

        $this->assertEquals($this->otherCompanyUserTransfer->getIdCompanyUser(), $shareDetailTransfer->getIdCompanyUser());
        $this->assertEquals($this->readOnlyQuotePermissionGroup->getIdQuotePermissionGroup(), $shareDetailTransfer->getQuotePermissionGroup()->getIdQuotePermissionGroup());
    }

    /**
     * @return void
     */
    public function testGetSharedCartsByCartUuidShouldFailOnNoCartUuidProvided(): void
    {
        // Assign
        $this->expectException(RequiredTransferPropertyException::class);
        /** @var \Spryker\Zed\SharedCartsRestApi\Business\SharedCartsRestApiFacadeInterface $sharedCartsRestApiFacade */
        $sharedCartsRestApiFacade = $this->tester->getFacade();

        // Act
        $sharedCartsRestApiFacade->getSharedCartsByCartUuid((new QuoteTransfer()));
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
    public function testCreateShouldReturnErrorIfNoShareDetailsProvided(): void
    {
        $this->expectException(RequiredTransferPropertyException::class);

        //Arrange
        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->setShareDetails(new ArrayObject())
            ->setQuoteUuid($this->quoteTransfer->getUuid())
            ->setCustomerReference($this->quoteTransfer->getCustomerReference());

        //Act
        $this->tester->getFacade()->create($shareCartRequestTransfer);
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

    /**
     * @return void
     */
    public function testExpandQuoteWithQuotePermissionGroupSuccessfullyExpandsQuote(): void
    {
        // Assign
        $companyTransfer = $this->tester->haveCompany();

        $ownerCustomerTransfer = $this->tester->haveCustomerWithCompanyUser($companyTransfer);
        $ownerCustomerTransfer->setCompanyUserTransfer(null);
        $otherCustomerTransfer = $this->tester->haveCustomerWithCompanyUser($companyTransfer);

        $quoteTransfer = $this->tester->haveSharedQuote(
            $ownerCustomerTransfer,
            $otherCustomerTransfer->getCompanyUserTransfer(),
            $this->readOnlyQuotePermissionGroup
        );

        $quoteTransfer->setCustomer($otherCustomerTransfer);

        // Act
        $quoteTransfer = $this->tester->getFacade()->expandQuoteWithQuotePermissionGroup($quoteTransfer);

        // Assert
        $this->assertNotNull($quoteTransfer->getQuotePermissionGroup());
        $this->assertEquals(
            $quoteTransfer->getQuotePermissionGroup()->getIdQuotePermissionGroup(),
            $this->readOnlyQuotePermissionGroup->getIdQuotePermissionGroup()
        );
    }

    /**
     * @return void
     */
    public function testExpandQuoteWithQuotePermissionGroupShouldDoNothingIfCustomerIsNotCompanyUser(): void
    {
        // Assign
        $companyTransfer = $this->tester->haveCompany();

        $ownerCustomerTransfer = $this->tester->haveCustomerWithCompanyUser($companyTransfer);
        $ownerCustomerTransfer->setCompanyUserTransfer(null);
        $otherCustomerTransfer = $this->tester->haveCustomerWithCompanyUser($companyTransfer);

        $quoteTransfer = $this->tester->haveSharedQuote(
            $ownerCustomerTransfer,
            $otherCustomerTransfer->getCompanyUserTransfer(),
            $this->readOnlyQuotePermissionGroup
        );
        $otherCustomerTransfer->setCompanyUserTransfer(null);
        $quoteTransfer->setCustomer($otherCustomerTransfer);

        // Act
        $quoteTransfer = $this->tester->getFacade()->expandQuoteWithQuotePermissionGroup($quoteTransfer);

        // Assert
        $this->assertNull($quoteTransfer->getQuotePermissionGroup());
    }

    /**
     * @return void
     */
    public function testExpandQuoteWithQuotePermissionGroupShouldDoNothingIfCustomerIsCartOwner(): void
    {
        // Assign
        $companyTransfer = $this->tester->haveCompany();

        $ownerCustomerTransfer = $this->tester->haveCustomerWithCompanyUser($companyTransfer);
        $ownerCustomerTransfer->setCompanyUserTransfer(null);
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $ownerCustomerTransfer,
        ]);

        $quoteTransfer->setCustomer($ownerCustomerTransfer);

        // Act
        $quoteTransfer = $this->tester->getFacade()->expandQuoteWithQuotePermissionGroup($quoteTransfer);

        // Assert
        $this->assertNull($quoteTransfer->getQuotePermissionGroup());
    }
}
