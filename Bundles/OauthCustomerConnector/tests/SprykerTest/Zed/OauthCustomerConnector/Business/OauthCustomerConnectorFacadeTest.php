<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthCustomerConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthCustomerConnector
 * @group Business
 * @group Facade
 * @group OauthCustomerConnectorFacadeTest
 * Add your own group annotations below this line
 */
class OauthCustomerConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\OauthCustomerConnector\OauthCustomerConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetCustomerShouldReturnCustomerWhenCredentialsMatch(): void
    {
        // Arrange
        $oauthUserTransfer = new OauthUserTransfer();
        $oauthUserTransfer->setUsername('spencor.hopkin@spryker.com')
            ->setPassword('change123');

        // Act
        $oauthUserTransfer = $this->tester->getFacade()->getCustomerOauthUser($oauthUserTransfer);

        //Assert
        $this->assertTrue($oauthUserTransfer->getIsSuccess(), 'Customer must be authorized by valid credentials.');
    }

    /**
     * @return void
     */
    public function testGetCustomerShouldReturnFailureCustomerWhenCredentialsNotMatch(): void
    {
        // Arrange
        $oauthUserTransfer = new OauthUserTransfer();
        $oauthUserTransfer->setUsername('spencor.hopkin@spryker.com')
            ->setPassword('change1233');

        // Act
        $oauthUserTransfer = $this->tester->getFacade()->getCustomerOauthUser($oauthUserTransfer);

        //Assert
        $this->assertFalse($oauthUserTransfer->getIsSuccess(), 'Customer must not be authorized by invalid credentials.');
    }

    /**
     * @return void
     */
    public function testGetCustomerImpersonationOauthUserShouldReturnCustomerCustomerExists(): void
    {
        // Arrange
        $oauthUserTransfer = (new OauthUserTransfer())->setCustomerReference('DE--1');

        // Act
        $oauthUserTransfer = $this->tester->getFacade()->getCustomerImpersonationOauthUser($oauthUserTransfer);

        //Assert
        $this->assertTrue($oauthUserTransfer->getIsSuccess(), 'Customer must be authorized when the customer by the customer reference exists.');
    }

    /**
     * @return void
     */
    public function testGetCustomerImpersonationOauthUserShouldReturnFailureCustomerWhenCredentialsNotMatch(): void
    {
        // Arrange
        $oauthUserTransfer = (new OauthUserTransfer())
            ->setCustomerReference('DE--NOT_THERE');

        // Act
        $oauthUserTransfer = $this->tester->getFacade()->getCustomerImpersonationOauthUser($oauthUserTransfer);

        //Assert
        $this->assertFalse($oauthUserTransfer->getIsSuccess(), 'Customer must not be authorized when the customer is not found by the customer reference.');
    }

    /**
     * @return void
     */
    public function testGetScopesShouldReturnScopeListForRequest(): void
    {
        // Arrange
        $oauthScopeRequestTransfer = new OauthScopeRequestTransfer();

        // Act
        $scopes = $this->tester->getFacade()->getScopes($oauthScopeRequestTransfer);

        //Assert
        $this->assertNotEmpty($scopes, 'Customer scopes must be returned.');
    }

    /**
     * @return void
     */
    public function testGetCustomerImpersonationScopesShouldReturnScopeList(): void
    {
        // Arrange
        $oauthScopeRequestTransfer = new OauthScopeRequestTransfer();

        // Act
        $scopes = $this->tester->getFacade()->getCustomerImpersonationScopes($oauthScopeRequestTransfer);

        //Assert
        $this->assertNotEmpty($scopes, 'Customer impersonation scopes must be returned.');
    }

    /**
     * @return void
     */
    public function testGetCustomerImpersonationScopesWithDefaultShouldReturnScopeList(): void
    {
        // Arrange
        $defaultScopeTransfer = (new OauthScopeTransfer())->setIdentifier('test');
        $oauthScopeRequestTransfer = (new OauthScopeRequestTransfer())
            ->addScope($defaultScopeTransfer);

        // Act
        $scopes = $this->tester->getFacade()->getCustomerImpersonationScopes($oauthScopeRequestTransfer);

        //Assert
        $this->assertNotEmpty($scopes, 'Customer impersonation scopes must be returned.');
        $this->assertContains($defaultScopeTransfer, $scopes, 'Customer impersonation scopes must include the default one.');
    }
}
