<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCart\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ResourceShareDataTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group SharedCart
 * @group Business
 * @group Facade
 * @group SharedCartFacadeTest
 * Add your own group annotations below this line
 */
class SharedCartFacadeTest extends Test
{
    /**
     * @uses \Spryker\Zed\SharedCart\Business\ResourceShare\ShareCartByUuidActivatorStrategy::GLOSSARY_KEY_CART_ACCESS_DENIED
     */
    protected const GLOSSARY_KEY_CART_ACCESS_DENIED = 'shared_cart.resource_share.strategy.cart_access_denied';

    /**
     * @uses \Spryker\Zed\SharedCart\Business\ResourceShare\ShareCartByUuidActivatorStrategy::GLOSSARY_KEY_UNABLE_TO_SHARE_CART
     */
    protected const GLOSSARY_KEY_UNABLE_TO_SHARE_CART = 'shared_cart.resource_share.strategy.error.unable_to_share_cart';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::KEY_SHARE_OPTION
     */
    protected const KEY_SHARE_OPTION = 'share_option';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::PERMISSION_GROUP_READ_ONLY
     */
    public const PERMISSION_GROUP_READ_ONLY = 'READ_ONLY';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::PERMISSION_GROUP_FULL_ACCESS
     */
    public const PERMISSION_GROUP_FULL_ACCESS = 'FULL_ACCESS';

    /**
     * @uses \Spryker\Client\PersistentCartShare\ResourceShare\ResourceShareRequestBuilder::KEY_ID_QUOTE
     */
    protected const KEY_ID_QUOTE = 'id_quote';

    /**
     * @uses \Spryker\Client\PersistentCartShare\ResourceShare\ResourceShareRequestBuilder::KEY_OWNER_ID_COMPANY_USER
     */
    protected const KEY_OWNER_ID_COMPANY_USER = 'owner_id_company_user';

    /**
     * @uses \Spryker\Client\PersistentCartShare\ResourceShare\ResourceShareRequestBuilder::KEY_OWNER_ID_COMPANY_BUSINESS_UNIT
     */
    protected const KEY_OWNER_ID_COMPANY_BUSINESS_UNIT = 'owner_id_company_business_unit';

    protected const VALUE_SHARE_OPTION = 'VALUE_SHARE_OPTION';
    protected const VALUE_ID_QUOTE = 1;
    protected const VALUE_OWNER_ID_COMPANY_USER = 1;
    protected const VALUE_OWNER_ID_COMPANY_BUSINESS_UNIT = 1;

    protected const VALUE_CUSTOMER_REFERENCE = 'VALUE_CUSTOMER_REFERENCE';
    protected const VALUE_NOT_EXISTING_ID_COMPANY_USER = 0;
    protected const VALUE_NOT_EXISTING_SHARE_OPTION = 'VALUE_NIT_EXISTING_SHARE_OPTION';

    /**
     * @var \SprykerTest\Zed\SharedCart\SharedCartBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testApplyShareCartByUuidActivatorStrategyShouldThrowExceptionWhenRequiredCustomerPropertyIsMissingInResourceShareRequestTransfer(): void
    {
        // Arrange
        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setCustomer(null)
            ->setResourceShare($this->createResourceShareTransfer());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->getFacade()->applyShareCartByUuidActivatorStrategy($resourceShareRequestTransfer);
    }

    /**
     * @return void
     */
    public function testApplyShareCartByUuidActivatorStrategyShouldThrowExceptionWhenRequiredResourceSharePropertyIsMissingInResourceShareRequestTransfer(): void
    {
        // Arrange
        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setCustomer($this->tester->haveCustomer())
            ->setResourceShare(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->getFacade()->applyShareCartByUuidActivatorStrategy($resourceShareRequestTransfer);
    }

    /**
     * @return void
     */
    public function testApplyShareCartByUuidActivatorStrategyShouldReturnErrorMessageWhenCompanyUserIsFromDifferentBusinessUnit(): void
    {
        // Arrange
        $firstCompanyUserTransfer = $this->createCompanyUserTransfer();
        $secondCompanyUserTransfer = $this->createCompanyUserTransfer();

        $resourceShareTransfer = $this->createResourceShareTransfer([
            static::KEY_OWNER_ID_COMPANY_USER => $secondCompanyUserTransfer->getIdCompanyUser(),
            static::KEY_OWNER_ID_COMPANY_BUSINESS_UNIT => $secondCompanyUserTransfer->getFkCompanyBusinessUnit(),
        ]);

        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setCustomer($firstCompanyUserTransfer->getCustomer())
            ->setResourceShare($resourceShareTransfer);

        // Act
        $resourceShareResponseTransfer = $this->getFacade()->applyShareCartByUuidActivatorStrategy($resourceShareRequestTransfer);

        // Assert
        $this->assertFalse($resourceShareResponseTransfer->getIsSuccessful());
        $this->hasResourceShareResponseTransferErrorMessage(
            $resourceShareResponseTransfer,
            static::GLOSSARY_KEY_CART_ACCESS_DENIED
        );
    }

    /**
     * @return void
     */
    public function testApplyShareCartByUuidActivatorStrategyShouldReturnErrorMessageWhenCompanyUserIsNotFoundByIdCompanyUser(): void
    {
        // Arrange
        $firstCompanyUserTransfer = $this->createCompanyUserTransfer();
        $secondCompanyUserTransfer = $this->createCompanyUserTransfer([
            static::KEY_OWNER_ID_COMPANY_USER => static::VALUE_NOT_EXISTING_ID_COMPANY_USER,
        ]);

        $resourceShareTransfer = $this->createResourceShareTransfer([
            static::KEY_OWNER_ID_COMPANY_USER => $secondCompanyUserTransfer->getIdCompanyUser(),
            static::KEY_OWNER_ID_COMPANY_BUSINESS_UNIT => $secondCompanyUserTransfer->getFkCompanyBusinessUnit(),
        ]);

        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setCustomer($firstCompanyUserTransfer->getCustomer())
            ->setResourceShare($resourceShareTransfer);

        // Act
        $resourceShareResponseTransfer = $this->getFacade()->applyShareCartByUuidActivatorStrategy($resourceShareRequestTransfer);

        // Assert
        $this->assertFalse($resourceShareResponseTransfer->getIsSuccessful());
        $this->hasResourceShareResponseTransferErrorMessage(
            $resourceShareResponseTransfer,
            static::GLOSSARY_KEY_UNABLE_TO_SHARE_CART
        );
    }

    /**
     * @return void
     */
    public function testApplyShareCartByUuidActivatorStrategyShouldReturnErrorMessageWhenQuotePermissionGroupIsNotFoundByShareOption(): void
    {
        // Arrange
        $firstCompanyUserTransfer = $this->createCompanyUserTransfer();
        $secondCompanyUserTransfer = $this->createCompanyUserTransfer([
            static::KEY_SHARE_OPTION => static::VALUE_NOT_EXISTING_SHARE_OPTION,
        ]);

        $resourceShareTransfer = $this->createResourceShareTransfer([
            static::KEY_OWNER_ID_COMPANY_USER => $secondCompanyUserTransfer->getIdCompanyUser(),
            static::KEY_OWNER_ID_COMPANY_BUSINESS_UNIT => $secondCompanyUserTransfer->getFkCompanyBusinessUnit(),
        ]);

        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setCustomer($firstCompanyUserTransfer->getCustomer())
            ->setResourceShare($resourceShareTransfer);

        // Act
        $resourceShareResponseTransfer = $this->getFacade()->applyShareCartByUuidActivatorStrategy($resourceShareRequestTransfer);

        // Assert
        $this->assertFalse($resourceShareResponseTransfer->getIsSuccessful());
        $this->hasResourceShareResponseTransferErrorMessage(
            $resourceShareResponseTransfer,
            static::GLOSSARY_KEY_UNABLE_TO_SHARE_CART
        );
    }

    /**
     * @return void
     */
    public function testApplyShareCartByUuidActivatorStrategyShouldShareCartWithReadOnlyAccessWhenAllParametersAreCorrect(): void
    {
        // Arrange
        $firstCompanyUserTransfer = $this->createCompanyUserTransfer();
        $secondCompanyUserTransfer = $this->createCompanyUserTransfer([
            CompanyUserTransfer::FK_COMPANY => $firstCompanyUserTransfer->getFkCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $firstCompanyUserTransfer->getFkCompanyBusinessUnit(),
        ]);

        $resourceShareTransfer = $this->createResourceShareTransfer([
            static::KEY_SHARE_OPTION => static::PERMISSION_GROUP_READ_ONLY,
            static::KEY_OWNER_ID_COMPANY_USER => $secondCompanyUserTransfer->getIdCompanyUser(),
            static::KEY_OWNER_ID_COMPANY_BUSINESS_UNIT => $secondCompanyUserTransfer->getFkCompanyBusinessUnit(),
        ]);

        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setCustomer($firstCompanyUserTransfer->getCustomer())
            ->setResourceShare($resourceShareTransfer);

        // Act
        $resourceShareResponseTransfer = $this->getFacade()->applyShareCartByUuidActivatorStrategy($resourceShareRequestTransfer);

        // Assert
        $this->assertTrue($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertNotNull($resourceShareResponseTransfer->getResourceShare());
    }

    /**
     * @return void
     */
    public function testApplyShareCartByUuidActivatorStrategyShouldShareCartWithFullAccessWhenAllParametersAreCorrect(): void
    {
        // Arrange
        $firstCompanyUserTransfer = $this->createCompanyUserTransfer();
        $secondCompanyUserTransfer = $this->createCompanyUserTransfer([
            CompanyUserTransfer::FK_COMPANY => $firstCompanyUserTransfer->getCompany()->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $firstCompanyUserTransfer->getCompanyBusinessUnit()->getIdCompanyBusinessUnit(),
        ]);

        $resourceShareTransfer = $this->createResourceShareTransfer([
            static::KEY_SHARE_OPTION => static::PERMISSION_GROUP_FULL_ACCESS,
            static::KEY_OWNER_ID_COMPANY_USER => $secondCompanyUserTransfer->getIdCompanyUser(),
            static::KEY_OWNER_ID_COMPANY_BUSINESS_UNIT => $secondCompanyUserTransfer->getFkCompanyBusinessUnit(),
        ]);

        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setCustomer($firstCompanyUserTransfer->getCustomer())
            ->setResourceShare($resourceShareTransfer);

        // Act
        $resourceShareResponseTransfer = $this->getFacade()->applyShareCartByUuidActivatorStrategy($resourceShareRequestTransfer);

        // Assert
        $this->assertTrue($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertNotNull($resourceShareResponseTransfer->getResourceShare());
    }

    /**
     * @param array $resourceShareDataSeed
     * @param array $resourceShareSeed
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer
     */
    protected function createResourceShareTransfer(array $resourceShareDataSeed = [], array $resourceShareSeed = []): ResourceShareTransfer
    {
        $resourceShareData = $resourceShareDataSeed + [
            static::KEY_SHARE_OPTION => static::VALUE_SHARE_OPTION,
            static::KEY_ID_QUOTE => static::VALUE_ID_QUOTE,
            static::KEY_OWNER_ID_COMPANY_USER => static::VALUE_OWNER_ID_COMPANY_USER,
            static::KEY_OWNER_ID_COMPANY_BUSINESS_UNIT => static::VALUE_OWNER_ID_COMPANY_BUSINESS_UNIT,
        ];

        $resourceShareDataTransfer = (new ResourceShareDataTransfer())
            ->fromArray($resourceShareData, true)
            ->setData($resourceShareData);

        return $this->tester->haveResourceShare($resourceShareSeed + [
            ResourceShareTransfer::RESOURCE_SHARE_DATA => $resourceShareDataTransfer,
        ]);
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function createCompanyUserTransfer(array $seed = []): CompanyUserTransfer
    {
        $customerTransfer = $this->tester->haveCustomer();
        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::IS_ACTIVE => true,
        ]);
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $companyUserTransfer = $this->tester->haveCompanyUser($seed + [
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);

        $companyUserTransfer->getCustomer()
            ->setCompanyUserTransfer($companyUserTransfer);

        return $companyUserTransfer->setCompany($companyTransfer)
            ->setCompanyBusinessUnit($companyBusinessUnitTransfer);
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

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
