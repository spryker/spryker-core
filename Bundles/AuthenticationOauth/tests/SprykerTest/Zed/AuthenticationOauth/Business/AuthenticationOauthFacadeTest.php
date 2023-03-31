<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AuthenticationOauth\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Oauth\Communication\Plugin\Oauth\UserPasswordOauthRequestGrantTypeConfigurationProviderPlugin;
use Spryker\Zed\Oauth\OauthDependencyProvider;
use Spryker\Zed\OauthUserConnector\Communication\Plugin\Oauth\UserOauthUserProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AuthenticationOauth
 * @group Business
 * @group Facade
 * @group AuthenticationOauthFacadeTest
 * Add your own group annotations below this line
 */
class AuthenticationOauthFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\AuthenticationOauth\AuthenticationOauthBusinessTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const TEST_USERNAME = 'harald@spryker.com';

    /**
     * @var string
     */
    protected const TEST_USERNAME_INVALID = 'sonia@spryker.com';

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setUserProvider();
        $this->setUserPasswordOauthRequestGrantTypeConfigurationProvider();
        $this->tester->addDependencies();
    }

    /**
     * @return void
     */
    public function testAuthenticateWithValidCredentialsIsSuccessful(): void
    {
        //Arrange
        $authenticationOauthFacade = $this->tester->getLocator()->authenticationOauth()->facade();
        $glueAuthenticationRequestTransfer = $this->tester->haveGlueAuthenticationRequestTransfer(static::TEST_USERNAME);

        //Act
        $glueAuthenticationResponseTransfer = $authenticationOauthFacade->authenticate($glueAuthenticationRequestTransfer);

        //Assert
        $this->assertTrue($glueAuthenticationResponseTransfer->getOauthResponse()->getIsValid());
        $this->assertNotEmpty($glueAuthenticationResponseTransfer->getOauthResponse()->getAccessToken());
    }

    /**
     * @return void
     */
    public function testAuthenticateWithInvalidCredentialsIsFailed(): void
    {
        //Arrange
        $authenticationOauthFacade = $this->tester->getLocator()->authenticationOauth()->facade();
        $glueAuthenticationRequestTransfer = $this->tester->haveGlueAuthenticationRequestTransfer(static::TEST_USERNAME_INVALID);

        //Act
        $glueAuthenticationResponseTransfer = $authenticationOauthFacade->authenticate($glueAuthenticationRequestTransfer);

        //Assert
        $this->assertFalse($glueAuthenticationResponseTransfer->getOauthResponse()->getIsValid());
        $this->assertEmpty($glueAuthenticationResponseTransfer->getOauthResponse()->getAccessToken());
    }

    /**
     * @return void
     */
    protected function setUserProvider(): void
    {
        $this->tester->setDependency(
            OauthDependencyProvider::PLUGINS_OAUTH_USER_PROVIDER,
            [new UserOauthUserProviderPlugin()],
        );
    }

    /**
     * @return void
     */
    protected function setUserPasswordOauthRequestGrantTypeConfigurationProvider(): void
    {
        $this->tester->setDependency(
            OauthDependencyProvider::PLUGINS_OAUTH_REQUEST_GRANT_TYPE_CONFIGURATION_PROVIDER,
            [new UserPasswordOauthRequestGrantTypeConfigurationProviderPlugin()],
        );
    }
}
