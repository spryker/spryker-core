<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Authentication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueAuthenticationResponseTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Client\Authentication\AuthenticationDependencyProvider;
use Spryker\Client\Authentication\Exception\MissingServerPluginException;
use Spryker\Shared\AuthenticationExtension\Dependency\Plugin\AuthenticationServerPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Authentication
 * @group AuthenticationClientTest
 * Add your own group annotations below this line
 */
class AuthenticationClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\Authentication\AuthenticationClientTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const FAKE_ACCESS_TOKEN = 'FAKE_ACCESS_TOKEN';

    /**
     * @return void
     */
    public function testAuthenticateIsSuccessful(): void
    {
        // Arrange
        $this->setAuthenticationServer();
        $authenticateClient = $this->tester->getLocator()->authentication()->client();
        $glueAuthenticationRequestTransfer = $this->tester->haveGlueAuthenticationRequestTransfer();

        //Act
        $glueAuthenticationResponseTransfer = $authenticateClient->authenticate($glueAuthenticationRequestTransfer);

        //Assert
        $this->assertTrue($glueAuthenticationResponseTransfer->getOauthResponse()->getIsValid());
        $this->assertNotEmpty($glueAuthenticationResponseTransfer->getOauthResponse()->getAccessToken());
    }

    /**
     * @return void
     */
    public function testAuthenticateThrowsMissingServerPluginException(): void
    {
        //Arrange
        $authenticateClient = $this->tester->getLocator()->authentication()->client();
        $glueAuthenticationRequestTransfer = $this->tester->haveGlueAuthenticationRequestTransfer();

        //Assert
        $this->expectException(MissingServerPluginException::class);
        $this->expectExceptionMessage('Missing instance of `Spryker\Shared\AuthenticationExtension\Dependency\Plugin\AuthenticationServerPluginInterface`! Authentication server needs to be configured.');

        //Act
        $glueAuthenticationResponseTransfer = $authenticateClient->authenticate($glueAuthenticationRequestTransfer);
    }

    /**
     * @return void
     */
    protected function setAuthenticationServer(): void
    {
        $this->tester->setDependency(
            AuthenticationDependencyProvider::PLUGINS_AUTHENTICATION_SERVER,
            [$this->createAuthenticationServerPluginMockMock()],
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\AuthenticationExtension\Dependency\Plugin\AuthenticationServerPluginInterface
     */
    protected function createAuthenticationServerPluginMockMock(): AuthenticationServerPluginInterface
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

        return $authenticationServerPluginMock;
    }
}
