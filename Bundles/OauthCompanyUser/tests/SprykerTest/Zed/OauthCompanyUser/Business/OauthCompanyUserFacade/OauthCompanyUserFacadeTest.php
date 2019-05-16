<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthCompanyUser\Business\OauthCompanyUserFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserAccessTokenRequestTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Oauth\Communication\Plugin\Oauth\PasswordOauthGrantTypeConfigurationProviderPlugin;
use Spryker\Zed\Oauth\Communication\Plugin\Oauth\RefreshTokenOauthGrantTypeConfigurationProviderPlugin;
use Spryker\Zed\Oauth\OauthDependencyProvider;
use Spryker\Zed\OauthCompanyUser\Communication\Plugin\Oauth\CompanyUserAccessTokenOauthGrantTypeConfigurationProviderPlugin;
use Spryker\Zed\OauthCompanyUser\Communication\Plugin\Oauth\CompanyUserAccessTokenOauthUserProviderPlugin;
use Spryker\Zed\OauthCompanyUser\Communication\Plugin\Oauth\CompanyUserOauthUserProviderPlugin;
use Spryker\Zed\OauthCompanyUser\Communication\Plugin\Oauth\IdCompanyUserOauthGrantTypeConfigurationProviderPlugin;
use Spryker\Zed\OauthCustomerConnector\Communication\Plugin\Oauth\CustomerOauthUserProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthCompanyUser
 * @group Business
 * @group OauthCompanyUserFacade
 * @group Facade
 * @group OauthCompanyUserFacadeTest
 * Add your own group annotations below this line
 */
class OauthCompanyUserFacadeTest extends Unit
{
    protected const FAKE_TOKEN = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

    /**
     * @var \SprykerTest\Zed\OauthCompanyUser\OauthCompanyUserBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(
            OauthDependencyProvider::PLUGIN_USER_PROVIDER,
            [
                new CustomerOauthUserProviderPlugin(),
                new CompanyUserOauthUserProviderPlugin(),
                new CompanyUserAccessTokenOauthUserProviderPlugin(),
            ]
        );

        $this->tester->setDependency(
            OauthDependencyProvider::PLUGINS_GRANT_TYPE_CONFIGURATION_PROVIDER,
            [
                new PasswordOauthGrantTypeConfigurationProviderPlugin(),
                new RefreshTokenOauthGrantTypeConfigurationProviderPlugin(),
                new IdCompanyUserOauthGrantTypeConfigurationProviderPlugin(),
                new CompanyUserAccessTokenOauthGrantTypeConfigurationProviderPlugin(),
            ]
        );
    }

    /**
     * @return void
     */
    public function testCreateCompanyUserAccessToken(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::IS_ACTIVE => true,
            CompanyTransfer::STATUS => 'approved',
        ]);
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::IS_ACTIVE => true,
        ]);

        $customerTransfer->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $oauthResponseTransfer = $this->tester
            ->getFacade()
            ->createCompanyUserAccessToken($customerTransfer);

        // Assert
        $this->assertTrue($oauthResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testCreateCompanyUserAccessTokenWithInactiveCompanyUser(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::IS_ACTIVE => true,
            CompanyTransfer::STATUS => 'approved',
        ]);
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::IS_ACTIVE => false,
        ]);

        $customerTransfer->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $oauthResponseTransfer = $this->tester
            ->getFacade()
            ->createCompanyUserAccessToken($customerTransfer);

        // Assert
        $this->assertFalse($oauthResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testCreateCompanyUserAccessTokenWithInactiveCompany(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::IS_ACTIVE => false,
            CompanyTransfer::STATUS => 'approved',
        ]);
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::IS_ACTIVE => true,
        ]);

        $customerTransfer->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $oauthResponseTransfer = $this->tester
            ->getFacade()
            ->createCompanyUserAccessToken($customerTransfer);

        // Assert
        $this->assertFalse($oauthResponseTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testCreateCompanyUserAccessTokenWithoutIdCompanyUser(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->createCompanyUserAccessToken($customerTransfer);
    }

    /**
     * @return void
     */
    public function testGetCustomerByAccessToken(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::IS_ACTIVE => true,
            CompanyTransfer::STATUS => 'approved',
        ]);
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::IS_ACTIVE => true,
        ]);

        $customerTransfer->setCompanyUserTransfer($companyUserTransfer);

        $oauthResponseTransfer = $this->tester
            ->getFacade()
            ->createCompanyUserAccessToken($customerTransfer);

        $companyUserAccessTokenRequestTransfer = (new CompanyUserAccessTokenRequestTransfer())
            ->setAccessToken($oauthResponseTransfer->getAccessToken());

        // Act
        $customerResponseTransfer = $this->tester
            ->getFacade()
            ->getCustomerByAccessToken($companyUserAccessTokenRequestTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertEquals(
            $customerTransfer->getIdCustomer(),
            $customerResponseTransfer->getCustomerTransfer()->getIdCustomer()
        );
    }

    /**
     * @return void
     */
    public function testGetCustomerByAccessTokenWithInvalidToken(): void
    {
        // Arrange
        $companyUserAccessTokenRequestTransfer = (new CompanyUserAccessTokenRequestTransfer())
            ->setAccessToken(static::FAKE_TOKEN);

        // Act
        $customerResponseTransfer = $this->tester
            ->getFacade()
            ->getCustomerByAccessToken($companyUserAccessTokenRequestTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
    }
}
