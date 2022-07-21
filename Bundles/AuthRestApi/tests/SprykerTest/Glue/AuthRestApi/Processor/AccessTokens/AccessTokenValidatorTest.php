<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Request;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Spryker\Glue\AuthRestApi\AuthRestApiConfig;
use Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface;
use Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokenValidator;
use Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokenValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group Request
 * @group AccessTokenValidatorTest
 * Add your own group annotations below this line
 */
class AccessTokenValidatorTest extends Unit
{
    /**
     * @uses \Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokenValidator::REQUEST_ATTRIBUTE_IS_PROTECTED
     *
     * @var string
     */
    protected const TEST_REQUEST_ATTRIBUTE_IS_PROTECTED = 'is-protected';

    /**
     * @var string
     */
    protected const TEST_TOKEN_TYPE = 'Bearer';

    /**
     * @var string
     */
    protected const TEST_TOKEN = 'Token';

    /**
     * @var \SprykerTest\Glue\AuthRestApi\AuthRestApiTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateShouldReturnErrorWhenTokenIsNotProvidedForProtectedRoutes(): void
    {
        // Arrange
        $oauthClient = $this->createOauthClientMock();
        $request = $this->createRequest([], [
            static::TEST_REQUEST_ATTRIBUTE_IS_PROTECTED => true,
        ]);

        // Act
        $restErrorMessageTransfer = $this->createAccessTokenValidator($oauthClient)->validate($request);

        // Assert
        $this->assertNotNull($restErrorMessageTransfer);
        $this->assertSame(Response::HTTP_FORBIDDEN, $restErrorMessageTransfer->getStatus());
        $this->assertSame(AuthRestApiConfig::RESPONSE_CODE_FORBIDDEN, $restErrorMessageTransfer->getCode());
    }

    /**
     * @return void
     */
    public function testValidateShouldReturnNullWhenTokenIsNotProvidedForUnprotectedRoutes(): void
    {
        // Arrange
        $oauthClient = $this->createOauthClientMock();
        $request = $this->createRequest();

        // Act
        $restErrorMessageTransfer = $this->createAccessTokenValidator($oauthClient)->validate($request);

        // Assert
        $this->assertNull($restErrorMessageTransfer);
    }

    /**
     * @return void
     */
    public function testValidateShouldReturnErrorWhenTokenIsEmpty(): void
    {
        // Arrange
        $oauthClient = $this->createOauthClientMock();
        $request = $this->createRequest([
            AuthRestApiConfig::HEADER_AUTHORIZATION => $this->createAuthorizationToken(static::TEST_TOKEN_TYPE, ''),
        ]);

        // Act
        $restErrorMessageTransfer = $this->createAccessTokenValidator($oauthClient)->validate($request);

        // Assert
        $this->assertNotNull($restErrorMessageTransfer);
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $restErrorMessageTransfer->getStatus());
        $this->assertSame(AuthRestApiConfig::RESPONSE_CODE_ACCESS_CODE_INVALID, $restErrorMessageTransfer->getCode());
    }

    /**
     * @return void
     */
    public function testValidateShouldReturnErrorWhenTokenTypeIsEmpty(): void
    {
        // Arrange
        $oauthClient = $this->createOauthClientMock();
        $request = $this->createRequest([
            AuthRestApiConfig::HEADER_AUTHORIZATION => $this->createAuthorizationToken('', static::TEST_TOKEN),
        ]);

        // Act
        $restErrorMessageTransfer = $this->createAccessTokenValidator($oauthClient)->validate($request);

        // Assert
        $this->assertNotNull($restErrorMessageTransfer);
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $restErrorMessageTransfer->getStatus());
        $this->assertSame(AuthRestApiConfig::RESPONSE_CODE_ACCESS_CODE_INVALID, $restErrorMessageTransfer->getCode());
    }

    /**
     * @return void
     */
    public function testValidateShouldReturnErrorWhenTokenIsInvalid(): void
    {
        // Arrange
        $oauthClient = $this->createOauthClientMock(false);
        $request = $this->createRequest([
            AuthRestApiConfig::HEADER_AUTHORIZATION => $this->createAuthorizationToken(static::TEST_TOKEN_TYPE, static::TEST_TOKEN),
        ]);

        // Act
        $restErrorMessageTransfer = $this->createAccessTokenValidator($oauthClient)->validate($request);

        // Assert
        $this->assertNotNull($restErrorMessageTransfer);
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $restErrorMessageTransfer->getStatus());
        $this->assertSame(AuthRestApiConfig::RESPONSE_CODE_ACCESS_CODE_INVALID, $restErrorMessageTransfer->getCode());
    }

    /**
     * @return void
     */
    public function testValidateShouldReturnNullWhenTokenIsValid(): void
    {
        // Arrange
        $oauthClient = $this->createOauthClientMock();
        $request = $this->createRequest([
            AuthRestApiConfig::HEADER_AUTHORIZATION => $this->createAuthorizationToken(static::TEST_TOKEN_TYPE, static::TEST_TOKEN),
        ]);

        // Act
        $restErrorMessageTransfer = $this->createAccessTokenValidator($oauthClient)->validate($request);

        // Assert
        $this->assertNull($restErrorMessageTransfer);
    }

    /**
     * @param \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface $oauthClient
     *
     * @return \Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokenValidatorInterface
     */
    protected function createAccessTokenValidator(AuthRestApiToOauthClientInterface $oauthClient): AccessTokenValidatorInterface
    {
        return new AccessTokenValidator($oauthClient);
    }

    /**
     * @param bool $isTokenValid
     *
     * @return \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface
     */
    protected function createOauthClientMock(bool $isTokenValid = true): AuthRestApiToOauthClientInterface
    {
        $oauthClient = $this->createMock(AuthRestApiToOauthClientInterface::class);
        $oauthClient->method('validateOauthAccessToken')
            ->willReturn((new OauthAccessTokenValidationResponseTransfer())->setIsValid($isTokenValid));

        return $oauthClient;
    }

    /**
     * @param array<string, mixed> $headers
     * @param array<string, mixed> $attributes
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function createRequest(array $headers = [], array $attributes = []): Request
    {
        $request = Request::create('/', Request::METHOD_GET);

        $request->headers->add($headers);
        $request->attributes->add($attributes);

        return $request;
    }

    /**
     * @param string $type
     * @param string $token
     *
     * @return string
     */
    protected function createAuthorizationToken(string $type, string $token): string
    {
        return sprintf('%s %s', $type, $token);
    }
}
