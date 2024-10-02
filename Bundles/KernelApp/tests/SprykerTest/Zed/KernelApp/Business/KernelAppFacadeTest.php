<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\KernelApp\Business;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;
use GuzzleHttp\Client;
use GuzzleHttp\Stream\StreamInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Spryker\Client\KernelApp\Dependency\External\KernelAppToGuzzleHttpClientAdapter;
use Spryker\Client\KernelApp\KernelAppDependencyProvider;
use Spryker\Shared\KernelApp\KernelAppConstants;
use SprykerTest\Zed\KernelApp\KernelAppBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group KernelApp
 * @group Business
 * @group Facade
 * @group KernelAppFacadeTest
 * Add your own group annotations below this line
 */
class KernelAppFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\KernelApp\KernelAppBusinessTester
     */
    protected KernelAppBusinessTester $tester;

    /**
     * @return void
     */
    public function testRequestIsExpendedWithTenantIdentifierBeforeRequestIsSend(): void
    {
        // Arrange
        $this->tester->setConfig(KernelAppConstants::TENANT_IDENTIFIER, Uuid::uuid4()->toString());

        $kernelAppFacade = $this->tester->getFacade();
        $expectedTenantIdentifier = Uuid::uuid4()->toString();

        $this->tester->mockEnvironmentConfig(KernelAppConstants::TENANT_IDENTIFIER, $expectedTenantIdentifier);
        $this->mockGuzzleClient(function (RequestInterface $request) use ($expectedTenantIdentifier): ResponseInterface {
            $this->assertTrue($request->hasHeader('x-tenant-identifier'));
            $tenantIdentifier = $request->getHeader('x-tenant-identifier')[0];
            $this->assertSame($expectedTenantIdentifier, $tenantIdentifier);

            return $this->mockResponse();
        });

        // Act
        $acpHttpRequestTransfer = $this->createAcpHttpRequestTransfer();
        $acpHttpResponseTransfer = $kernelAppFacade->makeRequest($acpHttpRequestTransfer);

        // Assert
        $this->assertInstanceOf(AcpHttpResponseTransfer::class, $acpHttpResponseTransfer);
    }

    /**
     * @return void
     */
    public function testDefaultHeadersAreSetWhenNotPresentInRequest(): void
    {
        // Arrange
        $this->tester->setConfig(KernelAppConstants::TENANT_IDENTIFIER, Uuid::uuid4()->toString());

        $kernelAppFacade = $this->tester->getFacade();
        $expectedContentType = 'application/json';

        $this->mockGuzzleClient(function (RequestInterface $request) use ($expectedContentType): ResponseInterface {
            $this->assertTrue($request->hasHeader('Content-Type'));
            $contentType = $request->getHeader('Content-Type')[0];
            $this->assertSame($expectedContentType, $contentType);

            return $this->mockResponse();
        });

        // Act
        $acpHttpRequestTransfer = $this->createAcpHttpRequestTransfer();
        $acpHttpResponseTransfer = $kernelAppFacade->makeRequest($acpHttpRequestTransfer);

        // Assert
        $this->assertInstanceOf(AcpHttpResponseTransfer::class, $acpHttpResponseTransfer);
    }

    /**
     * @return void
     */
    public function testDefaultHeadersAreIgnoredWhenPresentInRequest(): void
    {
        // Arrange
        $this->tester->setConfig(KernelAppConstants::TENANT_IDENTIFIER, Uuid::uuid4()->toString());

        $kernelAppFacade = $this->tester->getFacade();
        $expectedContentType = 'application/xml';

        $this->mockGuzzleClient(function (RequestInterface $request) use ($expectedContentType): ResponseInterface {
            $this->assertTrue($request->hasHeader('Content-Type'));
            $contentType = $request->getHeader('Content-Type')[0];
            $this->assertSame($expectedContentType, $contentType);

            return $this->mockResponse();
        });

        // Act
        $acpHttpRequestTransfer = $this->createAcpHttpRequestTransfer(['Content-Type' => 'application/xml']);
        $acpHttpResponseTransfer = $kernelAppFacade->makeRequest($acpHttpRequestTransfer);

        // Assert
        $this->assertInstanceOf(AcpHttpResponseTransfer::class, $acpHttpResponseTransfer);
    }

    /**
     * @param callable $sendCallback
     *
     * @return void
     */
    protected function mockGuzzleClient(callable $sendCallback): void
    {
        $clientMock = Stub::make(Client::class, ['send' => $sendCallback]);
        $this->tester->setDependency(KernelAppDependencyProvider::CLIENT_HTTP, new KernelAppToGuzzleHttpClientAdapter($clientMock));
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function mockResponse(): ResponseInterface
    {
        return Stub::makeEmpty(ResponseInterface::class, [
            'getBody' => function () {
                $streamMock = Stub::makeEmpty(StreamInterface::class, [
                    'getContents' => function () {
                        return '{"foo": "bar"}';
                    },
                ]);

                return $streamMock;
            },
            'getStatusCode' => 200,
        ]);
    }

    /**
     * @param array<string, string> $headers
     * @param string $method
     * @param string $uri
     * @param string $body
     *
     * @return \Generated\Shared\Transfer\AcpHttpRequestTransfer
     */
    protected function createAcpHttpRequestTransfer(
        array $headers = [],
        string $method = 'POST',
        string $uri = 'www.example.com',
        string $body = '{"foo": "bar"}'
    ): AcpHttpRequestTransfer {
        $acpHttpRequestTransfer = new AcpHttpRequestTransfer();
        $acpHttpRequestTransfer
            ->setMethod($method)
            ->setUri($uri)
            ->setBody($body);

        foreach ($headers as $headerName => $headerValue) {
            $acpHttpRequestTransfer->addHeader($headerName, $headerValue);
        }

        return $acpHttpRequestTransfer;
    }
}
