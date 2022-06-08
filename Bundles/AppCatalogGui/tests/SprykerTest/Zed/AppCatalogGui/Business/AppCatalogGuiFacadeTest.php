<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AppCatalogGui\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AccessTokenErrorTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Spryker\Zed\AppCatalogGui\AppCatalogGuiDependencyProvider;
use Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToOauthClientFacadeBridge;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AppCatalogGui
 * @group Business
 * @group Facade
 * @group AppCatalogGuiFacadeTest
 * Add your own group annotations below this line
 */
class AppCatalogGuiFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_ACCESS_TOKEN = 'some-access-token';

    /**
     * @var int
     */
    protected const TEST_EXPIRES_IN = 86400;

    /**
     * @var string
     */
    protected const TEST_ERROR = 'access_denied';

    /**
     * @var string
     */
    protected const TEST_ERROR_DESCRIPTION = 'Authentication failed.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_DE = 'Authentifizierung fehlgeschlagen';

    /**
     * @var string
     */
    protected const TEST_LOCALE_NAME_DE = 'de_DE';

    /**
     * @var \SprykerTest\Zed\AppCatalogGui\AppCatalogGuiBusinessTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected $expiresAt;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->expiresAt = (string)(time() + static::TEST_EXPIRES_IN);
    }

    /**
     * @return void
     */
    public function testRequestAccessTokenReturnsValidToken(): void
    {
        // Arrange
        $accessTokenResponseTransfer = (new AccessTokenResponseTransfer())
            ->setIsSuccessful(true)
            ->setAccessToken(static::TEST_ACCESS_TOKEN)
            ->setExpiresAt($this->expiresAt);

        $oauthClientFacadeBridgeMock = $this->getOauthClientFacadeBridgeMock();
        $oauthClientFacadeBridgeMock->method('getAccessToken')->willReturn($accessTokenResponseTransfer);

        // Act
        $accessTokenResponseTransfer = $this->tester->getFacade()->requestAccessToken();

        // Assert
        $this->assertTrue($accessTokenResponseTransfer->getIsSuccessful());
        $this->assertEquals(static::TEST_ACCESS_TOKEN, $accessTokenResponseTransfer->getAccessToken());
        $this->assertEquals($this->expiresAt, $accessTokenResponseTransfer->getExpiresAt());
    }

    /**
     * @return void
     */
    public function testRequestAccessTokenReturnsErrorWhenOauthRequestIsUnsuccessful(): void
    {
        // Arrange
        $accessTokenErrorTransfer = (new AccessTokenErrorTransfer())
            ->setError(static::TEST_ERROR)
            ->setErrorDescription(static::TEST_ERROR_DESCRIPTION);
        $accessTokenResponseTransfer = (new AccessTokenResponseTransfer())
            ->setIsSuccessful(false)
            ->setAccessTokenError($accessTokenErrorTransfer);

        $oauthClientFacadeBridgeMock = $this->getOauthClientFacadeBridgeMock();
        $oauthClientFacadeBridgeMock->method('getAccessToken')->willReturn($accessTokenResponseTransfer);

        // Act
        $accessTokenResponseTransfer = $this->tester->getFacade()->requestAccessToken();

        // Assert
        $this->assertFalse($accessTokenResponseTransfer->getIsSuccessful());
        $this->assertSame(static::TEST_ERROR, $accessTokenResponseTransfer->getAccessTokenError()->getError());
        $this->assertSame(static::TEST_ERROR_DESCRIPTION, $accessTokenResponseTransfer->getAccessTokenError()->getErrorDescription());
    }

    /**
     * @return \Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToOauthClientFacadeBridge|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getOauthClientFacadeBridgeMock(): AppCatalogGuiToOauthClientFacadeBridge
    {
        $oauthClientFacadeBridgeMock = $this->createMock(AppCatalogGuiToOauthClientFacadeBridge::class);

        $this->tester->setDependency(
            AppCatalogGuiDependencyProvider::FACADE_OAUTH_CLIENT,
            $oauthClientFacadeBridgeMock,
        );

        return $oauthClientFacadeBridgeMock;
    }
}
