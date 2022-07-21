<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Authentication\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueAuthenticationResponseTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Shared\AuthenticationExtension\Dependency\Plugin\AuthenticationServerPluginInterface;
use Spryker\Zed\Authentication\AuthenticationDependencyProvider;
use Spryker\Zed\Authentication\Business\Exception\MissingServerPluginException;
use Spryker\Zed\Oauth\OauthDependencyProvider;
use Spryker\Zed\OauthUserConnector\Communication\Plugin\Oauth\UserOauthUserProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Authentication
 * @group Business
 * @group Facade
 * @group AuthenticationFacadeTest
 * Add your own group annotations below this line
 */
class AuthenticationFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Authentication\AuthenticationBusinessTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const FAKE_ACCESS_TOKEN = 'FAKE_ACCESS_TOKEN';

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setUserProvider();
    }

    /**
     * @return void
     */
    public function testAuthenticateIsSuccessful(): void
    {
        // Arrange
        $this->setAuthenticationServer();
        $authenticateFacade = $this->tester->getLocator()->authentication()->facade();
        $glueAuthenticationRequestTransfer = $this->tester->haveGlueAuthenticationRequestTransfer();

        //Act
        $glueAuthenticationResponseTransfer = $authenticateFacade->authenticate($glueAuthenticationRequestTransfer);

        //Assert
        $this->assertTrue($glueAuthenticationResponseTransfer->getOauthResponse()->getIsValid());
        $this->assertNotEmpty($glueAuthenticationResponseTransfer->getOauthResponse()->getAccessToken());
    }

    /**
     * @return void
     */
    public function testAuthenticateThrowsMissingServerPluginException(): void
    {
        // Arrange
        $authenticateFacade = $this->tester->getLocator()->authentication()->facade();
        $glueAuthenticationRequestTransfer = $this->tester->haveGlueAuthenticationRequestTransfer();

        //Assert
        $this->expectException(MissingServerPluginException::class);
        $this->expectExceptionMessage('Missing instance of `Spryker\Shared\AuthenticationExtension\Dependency\Plugin\AuthenticationServerPluginInterface`! Authentication server needs to be configured.');

        //Act
        $glueAuthenticationResponseTransfer = $authenticateFacade->authenticate($glueAuthenticationRequestTransfer);
    }

    /**
     * @return void
     */
    protected function setAuthenticationServer(): void
    {
        $this->tester->setDependency(
            AuthenticationDependencyProvider::PLUGINS_AUTHENTICATION_SERVER,
            [$this->createAuthenticationServerPluginMock()],
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\AuthenticationExtension\Dependency\Plugin\AuthenticationServerPluginInterface
     */
    protected function createAuthenticationServerPluginMock(): AuthenticationServerPluginInterface
    {
        $oauthResponseTransfer = (new OauthResponseTransfer())
            ->setIsValid(true)
            ->setAccessToken(static::FAKE_ACCESS_TOKEN);
        $glueAuthenticationResponseTransfer = (new GlueAuthenticationResponseTransfer())
            ->setOauthResponse($oauthResponseTransfer);

        $authenticationServerPluginMock = $this->createMock(AuthenticationServerPluginInterface::class);
        $authenticationServerPluginMock->expects($this->once())
            ->method('authenticate')
            ->willReturn($glueAuthenticationResponseTransfer);
        $authenticationServerPluginMock->expects($this->once())
            ->method('isApplicable')
            ->willReturn(true);

        return $authenticationServerPluginMock;
    }

    /**
     * @return void
     */
    protected function setUserProvider(): void
    {
        $this->tester->setDependency(
            OauthDependencyProvider::PLUGIN_USER_PROVIDER,
            [new UserOauthUserProviderPlugin()],
        );
    }
}
