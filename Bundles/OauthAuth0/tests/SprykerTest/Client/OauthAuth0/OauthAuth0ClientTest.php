<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\OauthAuth0\Client;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AccessTokenRequestOptionsTransfer;
use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use League\OAuth2\Client\Token\AccessToken;
use Spryker\Client\OauthAuth0\Dependency\External\Auth0Adapter;
use Spryker\Client\OauthAuth0\Dependency\External\Auth0AdapterInterface;
use Spryker\Client\OauthAuth0\OauthAuth0DependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group OauthAuth0
 * @group Client
 * @group OauthAuth0ClientTest
 * Add your own group annotations below this line
 */
class OauthAuth0ClientTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_GRANT_TYPE = 'test-grant-type';

    /**
     * @var string
     */
    protected const TEST_TOKEN = 'test-token';

    /**
     * @var string
     */
    protected const TEST_EXPIRES_IN = '86400';

    /**
     * @var string
     */
    protected const TEST_AUDIENCE = 'test-audience';

    /**
     * @var \SprykerTest\Client\OauthAuth0\OauthAuth0ClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetAccessTokenReturnsValidToken(): void
    {
        // Arrange
        $expectedAccessToken = (new AccessToken([
            'access_token' => static::TEST_TOKEN,
            'expires_in' => static::TEST_EXPIRES_IN,
        ]));

        $accessTokenRequestOptionsTransfer = (new AccessTokenRequestOptionsTransfer())
            ->setAudience(static::TEST_AUDIENCE);

        $accessTokenRequestTransfer = (new AccessTokenRequestTransfer())
            ->setGrantType(static::TEST_GRANT_TYPE)
            ->setAccessTokenRequestOptions($accessTokenRequestOptionsTransfer);

        $this->getAuth0AdapterMock()
            ->expects($this->once())
            ->method('getAccessToken')
            ->with(
                $this->equalTo(static::TEST_GRANT_TYPE),
                $this->equalTo($accessTokenRequestOptionsTransfer->toArray()),
            )
            ->willReturn($expectedAccessToken);

        // Act
        $accessTokenResponseTransfer = $this->tester->getClient()->getAccessToken($accessTokenRequestTransfer);

        // Assert
        $this->assertInstanceOf(AccessTokenResponseTransfer::class, $accessTokenResponseTransfer);
        $this->assertTrue($accessTokenResponseTransfer->getIsSuccessful());
        $this->assertEquals($expectedAccessToken->getToken(), $accessTokenResponseTransfer->getAccessToken());
    }

    /**
     * @return void
     */
    public function testGetAccessTokenReturnsErrorWhenGrantTypeNotSpecified(): void
    {
        // Arrange
        $this->getAuth0AdapterMock();

        $accessTokenRequestTransfer = (new AccessTokenRequestTransfer());

        // Act
        $accessTokenResponseTransfer = $this->tester->getClient()->getAccessToken($accessTokenRequestTransfer);

        // Assert
        $this->assertInstanceOf(AccessTokenResponseTransfer::class, $accessTokenResponseTransfer);
        $this->assertFalse($accessTokenResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            'Property "grantType" of transfer `Generated\Shared\Transfer\AccessTokenRequestTransfer` is null.',
            $accessTokenResponseTransfer->getAccessTokenError()->getError(),
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\OauthAuth0\Dependency\External\Auth0AdapterInterface
     */
    protected function getAuth0AdapterMock(): Auth0AdapterInterface
    {
        $oauthAuth0ClientMock = $this->getMockBuilder(Auth0Adapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->tester->setDependency(
            OauthAuth0DependencyProvider::CLIENT_AUTH0_ADAPTER,
            $oauthAuth0ClientMock,
        );

        return $oauthAuth0ClientMock;
    }
}
