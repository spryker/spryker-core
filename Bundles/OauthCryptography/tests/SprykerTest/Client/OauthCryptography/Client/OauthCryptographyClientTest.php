<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\OauthCryptography\Client;

use Codeception\Test\Unit;
use GuzzleHttp\Psr7\ServerRequest;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Spryker\Client\OauthCryptography\OauthCryptographyClient;
use Spryker\Client\OauthCryptography\OauthCryptographyClientInterface;
use Spryker\Client\OauthCryptography\OauthCryptographyFactory;
use Spryker\Client\OauthCryptography\Validator\BearerTokenAuthorizationValidator;
use Spryker\Client\OauthCryptography\Validator\BearerTokenAuthorizationValidatorInterface;
use Spryker\Shared\OauthCryptography\OauthCryptographyConstants;
use const APPLICATION_ROOT_DIR;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group OauthCryptography
 * @group Client
 * @group OauthCryptographyClientTest
 * Add your own group annotations below this line
 */
class OauthCryptographyClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\OauthCryptography\OauthCryptographyClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testLoadPublicKeysWillLoadDefaultSshKey(): void
    {
        // Arrange
        $this->tester->setConfig(
            OauthCryptographyConstants::PUBLIC_KEY_PATH,
            $this->getPathToSshKey()
        );

        $expectedKeys = [new CryptKey($this->getPathToSshKey())];

        // Act
        $actualKeys = $this->tester->getLocator()->oauthCryptography()->client()->loadPublicKeys();

        // Assert
        $this->assertEquals($expectedKeys, $actualKeys);
    }

    /**
     * @return void
     */
    public function testValidateAuthorizationWillCallLeagueValidator(): void
    {
        // Arrange
        $leagueBearerTokenValidator = $this->getMockBuilder(BearerTokenValidator::class)
            ->onlyMethods(['setPublicKey', 'validateAuthorization'])
            ->disableOriginalConstructor()
            ->getMock();
        $leagueBearerTokenValidator->expects($this->once())->method('setPublicKey');
        $leagueBearerTokenValidator->expects($this->once())->method('validateAuthorization');

        $bearerTokenAuthorizationValidatorMock = $this->createBearerTokenAuthorizationValidatorMock($leagueBearerTokenValidator);
        $oauthCryptographyFactory = $this->createOauthCryptographyFactoryMock($bearerTokenAuthorizationValidatorMock);
        $oauthCryptographyClientMock = $this->createOauthCryptographyClientMock($oauthCryptographyFactory);

        $this->expectException(OAuthServerException::class);

        // Act
        $oauthCryptographyClientMock->validateAuthorization(
            new ServerRequest('GET', '/'),
            [new CryptKey($this->getPathToSshKey())],
            $this->createMock(AccessTokenRepositoryInterface::class)
        );
    }

    /**
     * @return void
     */
    public function testValidateAuthorization(): void
    {
        // Arrange
        $incomingRequest = new ServerRequest('GET', '/');

        $leagueBearerTokenValidator = $this->getMockBuilder(BearerTokenValidator::class)
            ->onlyMethods(['setPublicKey', 'validateAuthorization'])
            ->disableOriginalConstructor()
            ->getMock();
        $leagueBearerTokenValidator->expects($this->once())->method('setPublicKey');
        $leagueBearerTokenValidator->expects($this->once())->method('validateAuthorization')->willReturnCallback(
            function (ServerRequestInterface $request) {
                return $request
                    ->withAttribute('oauth_access_token_id', '')
                    ->withAttribute('oauth_client_id', '')
                    ->withAttribute('oauth_user_id', '')
                    ->withAttribute('oauth_scopes', '');
            }
        );

        $bearerTokenAuthorizationValidatorMock = $this->createBearerTokenAuthorizationValidatorMock($leagueBearerTokenValidator);
        $oauthCryptographyFactory = $this->createOauthCryptographyFactoryMock($bearerTokenAuthorizationValidatorMock);
        $oauthCryptographyClientMock = $this->createOauthCryptographyClientMock($oauthCryptographyFactory);

        // Act
        $expectedRequest = $oauthCryptographyClientMock->validateAuthorization(
            $incomingRequest,
            [new CryptKey($this->getPathToSshKey())],
            $this->createMock(AccessTokenRepositoryInterface::class)
        );

        // Assert
        $expectedAttributes = $expectedRequest->getAttributes();
        $this->assertArrayHasKey('oauth_access_token_id', $expectedAttributes);
        $this->assertArrayHasKey('oauth_client_id', $expectedAttributes);
        $this->assertArrayHasKey('oauth_user_id', $expectedAttributes);
        $this->assertArrayHasKey('oauth_scopes', $expectedAttributes);
    }

    /**
     * @return string
     */
    protected function getPathToSshKey(): string
    {
        return APPLICATION_ROOT_DIR . '/config/Zed/dev_only_public.key';
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $factoryMock
     *
     * @return \Spryker\Client\OauthCryptography\OauthCryptographyClient|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createOauthCryptographyClientMock(MockObject $factoryMock): OauthCryptographyClientInterface
    {
        $oauthCryptographyClientMock = $this->getMockBuilder(OauthCryptographyClient::class)
            ->onlyMethods(['getFactory'])
            ->getMock();
        $oauthCryptographyClientMock->method('getFactory')->willReturn($factoryMock);

        return $oauthCryptographyClientMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $bearerTokenAuthorizationValidatorMock
     *
     * @return \Spryker\Client\OauthCryptography\OauthCryptographyFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createOauthCryptographyFactoryMock(MockObject $bearerTokenAuthorizationValidatorMock): OauthCryptographyFactory
    {
        $oauthCryptographyFactory = $this->getMockBuilder(OauthCryptographyFactory::class)
            ->onlyMethods(['createBearerTokenAuthorizationValidator'])
            ->getMock();
        $oauthCryptographyFactory->method('createBearerTokenAuthorizationValidator')->willReturn($bearerTokenAuthorizationValidatorMock);

        return $oauthCryptographyFactory;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $leagueBearerTokenValidator
     *
     * @return \Spryker\Client\OauthCryptography\Validator\BearerTokenAuthorizationValidator|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createBearerTokenAuthorizationValidatorMock(MockObject $leagueBearerTokenValidator): BearerTokenAuthorizationValidatorInterface
    {
        $bearerTokenAuthorizationValidatorMock = $this->getMockBuilder(BearerTokenAuthorizationValidator::class)
            ->onlyMethods(['createBearerTokenValidator'])
            ->getMock();
        $bearerTokenAuthorizationValidatorMock->method('createBearerTokenValidator')->willReturn($leagueBearerTokenValidator);

        return $bearerTokenAuthorizationValidatorMock;
    }
}
