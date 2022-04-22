<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\AppCatalogGui;

use Codeception\Test\Unit;
use GuzzleHttp\Psr7\Response as GuzzleHttpResponse;
use Psr\Http\Message\StreamInterface;
use Spryker\Client\AppCatalogGui\AppCatalogGuiDependencyProvider;
use Spryker\Client\AppCatalogGui\Dependency\External\AppCatalogGuiToHttpClientAdapterInterface;
use Spryker\Client\AppCatalogGui\Exception\ExternalHttpRequestException;
use Symfony\Component\HttpFoundation\Response as SymfonyHttpResponse;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group AppCatalogGui
 * @group AppCatalogGuiClientTest
 * Add your own group annotations below this line
 */
class AppCatalogGuiClientTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_ACCESS_TOKEN = 'some-access-token';

    /**
     * @var string
     */
    protected const TEST_EXPIRES_IN = '86400';

    /**
     * @var string
     */
    protected const TEST_ERROR = 'access_denied';

    /**
     * @var string
     */
    protected const TEST_ERROR_DESCRIPTION = 'Unauthorized';

    /**
     * @var string
     */
    protected const CONFIGURATION_ERROR_MESSAGE = 'Aop IDP url was not found.';

    /**
     * @var \SprykerTest\Client\AppCatalogGui\AppCatalogGuiClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testRequestAccessTokenReturnsErrorWhenAopIdpUrlNotFound(): void
    {
        // Arrange
        $this->tester->mockAopClientConfig(null);

        // Act
        $accessTokenResponseTransfer = $this->tester->getClient()->requestAccessToken();

        // Assert
        $this->assertFalse($accessTokenResponseTransfer->getIsSuccessful());
        $this->assertSame(static::CONFIGURATION_ERROR_MESSAGE, $accessTokenResponseTransfer->getAccessTokenError()->getError());
        $this->assertNull($accessTokenResponseTransfer->getAccessTokenError()->getErrorDescription());
    }

    /**
     * @return void
     */
    public function testRequestAccessTokenReturnsErrorWhenUnauthorized(): void
    {
        // Arrange
        $httpClientMock = $this->getHttpClientMock();

        $externalHttpRequestException = (new ExternalHttpRequestException())
            ->setResponseBody($this->tester->getFixture('unsuccessful_response.json'));

        $httpClientMock->method('request')->willThrowException(
            $externalHttpRequestException,
        );
        $this->tester->mockAopClientConfig();

        // Act
        $accessTokenResponseTransfer = $this->tester->getClient()->requestAccessToken();

        // Assert
        $this->assertFalse($accessTokenResponseTransfer->getIsSuccessful());
        $this->assertSame(static::TEST_ERROR, $accessTokenResponseTransfer->getAccessTokenError()->getError());
        $this->assertSame(static::TEST_ERROR_DESCRIPTION, $accessTokenResponseTransfer->getAccessTokenError()->getErrorDescription());
    }

    /**
     * @return void
     */
    public function testRequestAccessTokenReturnsAccessTokenWhenSuccess(): void
    {
        // Arrange
        $httpClientMock = $this->getHttpClientMock();
        $responseMock = $this->getResponseMock(
            'successful_response.json',
            SymfonyHttpResponse::HTTP_OK,
        );
        $httpClientMock->method('request')->willReturn($responseMock);
        $this->tester->mockAopClientConfig();

        // Act
        $accessTokenResponseTransfer = $this->tester->getClient()->requestAccessToken();

        // Assert
        $this->assertTrue($accessTokenResponseTransfer->getIsSuccessful());
        $this->assertSame(static::TEST_ACCESS_TOKEN, $accessTokenResponseTransfer->getAccessToken());
        $this->assertSame(static::TEST_EXPIRES_IN, $accessTokenResponseTransfer->getExpiresIn());
    }

    /**
     * @return \Spryker\Client\AppCatalogGui\Dependency\External\AppCatalogGuiToHttpClientAdapterInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getHttpClientMock(): AppCatalogGuiToHttpClientAdapterInterface
    {
        $httpClientMock = $this->createMock(AppCatalogGuiToHttpClientAdapterInterface::class);

        $this->tester->setDependency(
            AppCatalogGuiDependencyProvider::CLIENT_HTTP,
            $httpClientMock,
        );

        return $httpClientMock;
    }

    /**
     * @param string $responseFileName
     * @param int $responseCode
     *
     * @return \GuzzleHttp\Psr7\Response|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getResponseMock(string $responseFileName, int $responseCode): GuzzleHttpResponse
    {
        $responseBody = $this->tester->getFixture($responseFileName);
        $responseMock = $this->createMock(GuzzleHttpResponse::class);
        $streamMock = $this->createMock(StreamInterface::class);

        $streamMock->method('getContents')
            ->willReturn($responseBody);
        $responseMock->method('getBody')
            ->willReturn($streamMock);
        $responseMock->method('getStatusCode')
            ->willReturn($responseCode);

        return $responseMock;
    }
}
