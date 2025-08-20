<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCart\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ResourceShareDataTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\SharedCart\Communication\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\WriteSharedCartPermissionPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SharedCart
 * @group Business
 * @group Facade
 * @group SharedCartFacadeTest
 * Add your own group annotations below this line
 */
class SharedCartFacadeTest extends Unit
{
    /**
     * @uses \Spryker\Zed\SharedCart\Business\ResourceShare\ResourceShareQuoteShare::GLOSSARY_KEY_CART_ACCESS_DENIED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_CART_ACCESS_DENIED = 'shared_cart.resource_share.strategy.error.cart_access_denied';

    /**
     * @uses \Spryker\Zed\SharedCart\Business\ResourceShare\ResourceShareQuoteShare::GLOSSARY_KEY_QUOTE_IS_NOT_AVAILABLE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_QUOTE_IS_NOT_AVAILABLE = 'persistent_cart_share.error.quote_is_not_available';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::PERMISSION_GROUP_READ_ONLY
     *
     * @var string
     */
    public const PERMISSION_GROUP_READ_ONLY = 'READ_ONLY';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::PERMISSION_GROUP_FULL_ACCESS
     *
     * @var string
     */
    public const PERMISSION_GROUP_FULL_ACCESS = 'FULL_ACCESS';

    /**
     * @var int
     */
    protected const VALUE_NOT_EXISTING_ID_COMPANY_USER = 0;

    /**
     * @var string
     */
    protected const VALUE_NOT_EXISTING_SHARE_OPTION = 'VALUE_NIT_EXISTING_SHARE_OPTION';

    /**
     * @var bool
     */
    protected const VALUE_IS_QUOTE_LOCKED_FALSE = false;

    /**
     * @var \SprykerTest\Zed\SharedCart\SharedCartBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testShareCartByResourceShareRequestShouldThrowExceptionWhenRequiredCustomerPropertyIsMissingInResourceShareRequestTransfer(): void
    {
        // Arrange
        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setCustomer(null)
            ->setResourceShare($this->tester->createResourceShare());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->shareCartByResourceShareRequest($resourceShareRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShareCartByResourceShareRequestShouldThrowExceptionWhenRequiredResourceSharePropertyIsMissingInResourceShareRequestTransfer(): void
    {
        // Arrange
        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setCustomer($this->tester->haveCustomer())
            ->setResourceShare(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->shareCartByResourceShareRequest($resourceShareRequestTransfer);
    }

    /**
     * @skip This test was temporarily skipped due to flikerness. See {@link https://spryker.atlassian.net/browse/CC-25718} for details
     *
     * @return void
     */
    public function testShareCartByResourceShareRequestShouldReturnErrorMessageWhenCompanyUserIsFromDifferentBusinessUnit(): void
    {
        // Arrange
        $firstCompanyUserTransfer = $this->tester->createCompanyUserTransfer();
        $secondCompanyUserTransfer = $this->tester->createCompanyUserTransfer();

        $resourceShareTransfer = $this->tester->createResourceShare([
            ResourceShareDataTransfer::OWNER_COMPANY_USER_ID => $secondCompanyUserTransfer->getIdCompanyUser(),
            ResourceShareDataTransfer::OWNER_COMPANY_BUSINESS_UNIT_ID => $secondCompanyUserTransfer->getFkCompanyBusinessUnit(),
        ]);

        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setCustomer($firstCompanyUserTransfer->getCustomer())
            ->setResourceShare($resourceShareTransfer);

        // Act
        $resourceShareResponseTransfer = $this->tester->getFacade()->shareCartByResourceShareRequest($resourceShareRequestTransfer);

        // Assert
        $this->assertFalse($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertTrue($this->hasResourceShareResponseTransferErrorMessage(
            $resourceShareResponseTransfer,
            static::GLOSSARY_KEY_CART_ACCESS_DENIED,
        ));
    }

    /**
     * @return void
     */
    public function testShareCartByResourceShareRequestShouldReturnErrorMessageWhenCompanyUserIsNotFoundByIdCompanyUser(): void
    {
        // Arrange
        $firstCompanyUserTransfer = $this->tester->createCompanyUserTransfer();
        $secondCompanyUserTransfer = $this->tester->createCompanyUserTransfer();

        $resourceShareTransfer = $this->tester->createResourceShare([
            ResourceShareDataTransfer::OWNER_COMPANY_USER_ID => $secondCompanyUserTransfer->getIdCompanyUser(),
            ResourceShareDataTransfer::OWNER_COMPANY_BUSINESS_UNIT_ID => $secondCompanyUserTransfer->getFkCompanyBusinessUnit(),
        ]);

        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setCustomer($firstCompanyUserTransfer->getCustomer())
            ->setResourceShare($resourceShareTransfer);

        // Act
        $resourceShareResponseTransfer = $this->tester->getFacade()->shareCartByResourceShareRequest($resourceShareRequestTransfer);

        // Assert
        $this->assertFalse($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertTrue($this->hasResourceShareResponseTransferErrorMessage(
            $resourceShareResponseTransfer,
            static::GLOSSARY_KEY_CART_ACCESS_DENIED,
        ));
    }

    /**
     * @return void
     */
    public function testShareCartByResourceShareRequestShouldReturnErrorMessageWhenQuotePermissionGroupIsNotFoundByShareOption(): void
    {
        // Arrange
        $firstCompanyUserTransfer = $this->tester->createCompanyUserTransfer();
        $secondCompanyUserTransfer = $this->tester->createCompanyUserTransfer([
            ResourceShareDataTransfer::SHARE_OPTION => static::VALUE_NOT_EXISTING_SHARE_OPTION,
        ]);

        $resourceShareTransfer = $this->tester->createResourceShare([
            ResourceShareDataTransfer::OWNER_COMPANY_USER_ID => $secondCompanyUserTransfer->getIdCompanyUser(),
            ResourceShareDataTransfer::OWNER_COMPANY_BUSINESS_UNIT_ID => $secondCompanyUserTransfer->getFkCompanyBusinessUnit(),
        ]);

        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setCustomer($firstCompanyUserTransfer->getCustomer())
            ->setResourceShare($resourceShareTransfer);

        // Act
        $resourceShareResponseTransfer = $this->tester->getFacade()->shareCartByResourceShareRequest($resourceShareRequestTransfer);

        // Assert
        $this->assertFalse($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertTrue($this->hasResourceShareResponseTransferErrorMessage(
            $resourceShareResponseTransfer,
            static::GLOSSARY_KEY_CART_ACCESS_DENIED,
        ));
    }

    /**
     * @return void
     */
    public function testShareCartByResourceShareRequestShouldShareCartWithReadOnlyAccessWhenAllParametersAreCorrect(): void
    {
        // Arrange
        $firstCompanyUserTransfer = $this->tester->createCompanyUserTransfer();
        $secondCompanyUserTransfer = $this->tester->createCompanyUserTransfer([
            CompanyUserTransfer::FK_COMPANY => $firstCompanyUserTransfer->getFkCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $firstCompanyUserTransfer->getFkCompanyBusinessUnit(),
        ]);

        $customerTransfer = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::IS_LOCKED => static::VALUE_IS_QUOTE_LOCKED_FALSE,
        ]);
        $resourceShareTransfer = $this->tester->createResourceShare([
            ResourceShareDataTransfer::SHARE_OPTION => static::PERMISSION_GROUP_READ_ONLY,
            ResourceShareDataTransfer::OWNER_COMPANY_USER_ID => $secondCompanyUserTransfer->getIdCompanyUser(),
            ResourceShareDataTransfer::OWNER_COMPANY_BUSINESS_UNIT_ID => $secondCompanyUserTransfer->getFkCompanyBusinessUnit(),
            ResourceShareDataTransfer::ID_QUOTE => $quoteTransfer->getIdQuote(),
        ]);

        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setCustomer($firstCompanyUserTransfer->getCustomer())
            ->setResourceShare($resourceShareTransfer);

        // Act
        $resourceShareResponseTransfer = $this->tester->getFacade()->shareCartByResourceShareRequest($resourceShareRequestTransfer);

        // Assert
        $this->assertTrue($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertNotNull($resourceShareResponseTransfer->getResourceShare());
    }

    /**
     * @return void
     */
    public function testShareCartByResourceShareRequestShouldShareCartWithFullAccessWhenAllParametersAreCorrect(): void
    {
        // Arrange
        $firstCompanyUserTransfer = $this->tester->createCompanyUserTransfer();
        $secondCompanyUserTransfer = $this->tester->createCompanyUserTransfer([
            CompanyUserTransfer::FK_COMPANY => $firstCompanyUserTransfer->getCompany()->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $firstCompanyUserTransfer->getCompanyBusinessUnit()->getIdCompanyBusinessUnit(),
        ]);

        $customerTransfer = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::IS_LOCKED => static::VALUE_IS_QUOTE_LOCKED_FALSE,
        ]);

        $resourceShareTransfer = $this->tester->createResourceShare([
            ResourceShareDataTransfer::SHARE_OPTION => static::PERMISSION_GROUP_FULL_ACCESS,
            ResourceShareDataTransfer::OWNER_COMPANY_USER_ID => $secondCompanyUserTransfer->getIdCompanyUser(),
            ResourceShareDataTransfer::OWNER_COMPANY_BUSINESS_UNIT_ID => $secondCompanyUserTransfer->getFkCompanyBusinessUnit(),
            ResourceShareDataTransfer::ID_QUOTE => $quoteTransfer->getIdQuote(),
        ]);

        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setCustomer($firstCompanyUserTransfer->getCustomer())
            ->setResourceShare($resourceShareTransfer);

        // Act
        $resourceShareResponseTransfer = $this->tester->getFacade()->shareCartByResourceShareRequest($resourceShareRequestTransfer);

        // Assert
        $this->assertTrue($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertNotNull($resourceShareResponseTransfer->getResourceShare());
    }

    /**
     * @return void
     */
    public function testShareCartByResourceShareRequestShouldReturnErrorMessageWhenWhenQuoteDoesNotExistsAnyMore(): void
    {
        // Arrange
        $firstCompanyUserTransfer = $this->tester->createCompanyUserTransfer();
        $secondCompanyUserTransfer = $this->tester->createCompanyUserTransfer([
            CompanyUserTransfer::FK_COMPANY => $firstCompanyUserTransfer->getCompany()->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $firstCompanyUserTransfer->getCompanyBusinessUnit()->getIdCompanyBusinessUnit(),
        ]);

        $resourceShareTransfer = $this->tester->createResourceShare([
            ResourceShareDataTransfer::SHARE_OPTION => static::PERMISSION_GROUP_FULL_ACCESS,
            ResourceShareDataTransfer::OWNER_COMPANY_USER_ID => $secondCompanyUserTransfer->getIdCompanyUser(),
            ResourceShareDataTransfer::OWNER_COMPANY_BUSINESS_UNIT_ID => $secondCompanyUserTransfer->getFkCompanyBusinessUnit(),
            ResourceShareDataTransfer::ID_QUOTE => 0,
        ]);

        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setCustomer($firstCompanyUserTransfer->getCustomer())
            ->setResourceShare($resourceShareTransfer);

        // Act
        $resourceShareResponseTransfer = $this->tester->getFacade()->shareCartByResourceShareRequest($resourceShareRequestTransfer);

        // Assert
        $this->assertFalse($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertTrue($this->hasResourceShareResponseTransferErrorMessage(
            $resourceShareResponseTransfer,
            static::GLOSSARY_KEY_QUOTE_IS_NOT_AVAILABLE,
        ));
    }

    /**
     * @return void
     */
    public function testGetCustomersSharingSameQuoteShouldReturnQuoteOwnerInCollection(): void
    {
        //Arrange
        $ownerCustomerTransfer = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER_REFERENCE => $ownerCustomerTransfer->getCustomerReference(),
            QuoteTransfer::CUSTOMER => $ownerCustomerTransfer,
        ]);

        $otherCustomerTransfer = $this->tester->haveCustomer();
        $otherCompanyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_CUSTOMER => $otherCustomerTransfer->getIdCustomer(),
            CompanyUserTransfer::CUSTOMER => $otherCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $this->tester->haveQuoteCompanyUser(
            $otherCompanyUserTransfer,
            $quoteTransfer,
            $this->tester->haveQuotePermissionGroup(static::PERMISSION_GROUP_FULL_ACCESS, [
                ReadSharedCartPermissionPlugin::KEY,
                WriteSharedCartPermissionPlugin::KEY,
            ]),
        );

        //Act
        $customerCollectionTransfer = $this->tester->getFacade()
            ->getCustomerCollectionByQuote($quoteTransfer->setCustomer($otherCustomerTransfer));

        //Assert
        $this->assertNotEmpty($customerCollectionTransfer->getCustomers());
        $this->assertCount(2, $customerCollectionTransfer->getCustomers());
    }

    /**
     * @return void
     */
    public function testGetCustomersSharingSameQuoteShouldReturnCustomerCollectionWithCustomerWithAccessToQuote(): void
    {
        //Arrange
        $ownerCustomerTransfer = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER_REFERENCE => $ownerCustomerTransfer->getCustomerReference(),
            QuoteTransfer::CUSTOMER => $ownerCustomerTransfer,
        ]);

        $otherCustomerTransfer = $this->tester->haveCustomer();
        $otherCompanyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_CUSTOMER => $otherCustomerTransfer->getIdCustomer(),
            CompanyUserTransfer::CUSTOMER => $otherCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $this->tester->haveQuoteCompanyUser(
            $otherCompanyUserTransfer,
            $quoteTransfer,
            $this->tester->haveQuotePermissionGroup(static::PERMISSION_GROUP_FULL_ACCESS, [
                ReadSharedCartPermissionPlugin::KEY,
                WriteSharedCartPermissionPlugin::KEY,
            ]),
        );

        //Act
        $customerCollectionTransfer = $this->tester->getFacade()
            ->getCustomerCollectionByQuote($quoteTransfer);

        //Assert
        $this->assertNotEmpty($customerCollectionTransfer->getCustomers());
        $this->assertCount(1, $customerCollectionTransfer->getCustomers());
        $this->assertSame(
            $otherCustomerTransfer->getCustomerReference(),
            $customerCollectionTransfer->getCustomers()->offsetGet(0)->getCustomerReference(),
        );
    }

    /**
     * @return void
     */
    public function testGetShareDetailsByIdQuoteShouldReturnNoCompanyUsersForAnonymizedCustomers(): void
    {
        // Arrange
        $anonymizedCustomerTransfer = $this->tester->haveCustomer([
            'anonymized_at' => date('Y-m-d H:i:s'),
        ]);

        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER_REFERENCE => $anonymizedCustomerTransfer->getCustomerReference(),
            QuoteTransfer::CUSTOMER => $anonymizedCustomerTransfer,
        ]);

        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_CUSTOMER => $anonymizedCustomerTransfer->getIdCustomer(),
            CompanyUserTransfer::CUSTOMER => $anonymizedCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $this->tester->haveQuoteCompanyUser(
            $companyUserTransfer,
            $quoteTransfer,
            $this->tester->haveQuotePermissionGroup(static::PERMISSION_GROUP_FULL_ACCESS, [
                ReadSharedCartPermissionPlugin::KEY,
                WriteSharedCartPermissionPlugin::KEY,
            ]),
        );

        // Act
        $shareDetailCollectionTransfer = $this->tester->getFacade()
            ->getShareDetailsByIdQuote($quoteTransfer);

        // Assert
        $this->assertEmpty(
            $shareDetailCollectionTransfer->getShareDetails(),
            'No company user should be returned for an anonymized customer.',
        );
    }

    /**
     * @return void
     */
    public function testGetSharedCartDetailsShouldReturnNoCompanyUsersForAnonymizedCustomers(): void
    {
        // Arrange
        $anonymizedCustomerTransfer = $this->tester->haveCustomer([
            'anonymized_at' => date('Y-m-d H:i:s'),
        ]);

        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER_REFERENCE => $anonymizedCustomerTransfer->getCustomerReference(),
            QuoteTransfer::CUSTOMER => $anonymizedCustomerTransfer,
        ]);

        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_CUSTOMER => $anonymizedCustomerTransfer->getIdCustomer(),
            CompanyUserTransfer::CUSTOMER => $anonymizedCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $this->tester->haveQuoteCompanyUser(
            $companyUserTransfer,
            $quoteTransfer,
            $this->tester->haveQuotePermissionGroup(static::PERMISSION_GROUP_FULL_ACCESS, [
                ReadSharedCartPermissionPlugin::KEY,
                WriteSharedCartPermissionPlugin::KEY,
            ]),
        );

        // Act
        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->setQuoteIds([$quoteTransfer->getIdQuote()]);

        $shareDetails = $this->tester->getFacade()
            ->getSharedCartDetails($shareCartRequestTransfer);

        // Assert
        $this->assertEmpty(
            $shareDetails,
            'No company user should be returned for an anonymized customer.',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer
     * @param string $errorMessage
     *
     * @return bool
     */
    protected function hasResourceShareResponseTransferErrorMessage(
        ResourceShareResponseTransfer $resourceShareResponseTransfer,
        string $errorMessage
    ): bool {
        $resourceShareResponseTransfer->requireMessages();
        foreach ($resourceShareResponseTransfer->getMessages() as $messageTransfer) {
            $messageTransfer->requireValue();

            if ($messageTransfer->getValue() === $errorMessage) {
                return true;
            }
        }

        return false;
    }
}
