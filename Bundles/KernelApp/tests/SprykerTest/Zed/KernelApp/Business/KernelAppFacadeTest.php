<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\KernelApp\Business;

use Codeception\Stub;
use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
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
     * @return void
     */
    public function testFilterMessageChannelsFiltersGivenMessageChannelsWhenAppSpecificMessageChannelsExistAndAppIsActive(): void
    {
        // Arrange
        $kernelAppFacade = $this->tester->getFacade();
        $messageChannels = ['foo', 'bar'];
        $this->tester->emptyAppConfigTable();
        $appConfigTransfer = $this->tester->haveAppConfigPersisted([
            AppConfigTransfer::IS_ACTIVE => true,
            AppConfigTransfer::APP_IDENTIFIER => 'app-identifier',
            AppConfigTransfer::MESSAGE_CHANNELS => ['foo'],
        ]);

        $expectedFilteredMessageChannels = array_intersect($messageChannels, $appConfigTransfer->getMessageChannels());

        // Act
        $filteredMessageChannels = $kernelAppFacade->filterMessageChannels($messageChannels);

        // Assert
        $this->assertSame($expectedFilteredMessageChannels, $filteredMessageChannels);
    }

    /**
     * @return void
     */
    public function testFilterMessageChannelsDoesNothingWithGivenMessageChannelsWhenAppSpecificMessageChannelsExistButNotForAllAppConfigs(): void
    {
        // Arrange
        $kernelAppFacade = $this->tester->getFacade();
        $messageChannels = ['foo', 'bar'];
        $this->tester->emptyAppConfigTable();
        $this->tester->haveAppConfigPersisted([
            AppConfigTransfer::APP_IDENTIFIER => 'app-identifier-1',
            AppConfigTransfer::MESSAGE_CHANNELS => ['foo'],
        ]);
        $this->tester->haveAppConfigPersisted([
            AppConfigTransfer::APP_IDENTIFIER => 'app-identifier-2',
        ]);

        // Act
        $filteredMessageChannels = $kernelAppFacade->filterMessageChannels($messageChannels);

        // Assert
        $this->assertSame($messageChannels, $filteredMessageChannels);
    }

    /**
     * @return void
     */
    public function testFilterMessageChannelsFiltersGivenMessageChannelsWhenAppIsNotActiveButGracePeriodHasNotBeenExpired(): void
    {
        // Arrange
        $kernelAppFacade = $this->tester->getFacade();
        $gracePeriod = $this->tester->getModuleConfig()->getAppConfigGracePeriod();
        $appConfigUpdatedAt = $gracePeriod > 0
            ? (new DateTime())->modify(sprintf('-%s seconds', $gracePeriod - 5))->format('Y-m-d H:i:s')
            : null;

        $messageChannels = ['foo', 'bar'];
        $this->tester->emptyAppConfigTable();
        $appConfigTransfer = $this->tester->haveAppConfigPersisted([
            AppConfigTransfer::IS_ACTIVE => false,
            AppConfigTransfer::APP_IDENTIFIER => 'app-identifier-1',
            AppConfigTransfer::MESSAGE_CHANNELS => ['foo'],
        ], $appConfigUpdatedAt);

        $expectedFilteredMessageChannels = array_intersect($messageChannels, $appConfigTransfer->getMessageChannels());

        // Act
        $filteredMessageChannels = $kernelAppFacade->filterMessageChannels($messageChannels);

        // Assert
        $this->assertSame($expectedFilteredMessageChannels, $filteredMessageChannels);
    }

    /**
     * @return void
     */
    public function testFilterMessageChannelsReturnsNoGivenMessageChannelsWhenAppIsNotActiveAndGracePeriodHasAlreadyBeenExpired(): void
    {
        // Arrange
        $kernelAppFacade = $this->tester->getFacade();
        $gracePeriod = $this->tester->getModuleConfig()->getAppConfigGracePeriod();
        $messageChannels = ['foo', 'bar'];
        $this->tester->emptyAppConfigTable();
        $this->tester->haveAppConfigPersisted([
            AppConfigTransfer::IS_ACTIVE => false,
            AppConfigTransfer::APP_IDENTIFIER => 'app-identifier-1',
            AppConfigTransfer::MESSAGE_CHANNELS => ['foo'],
        ], (new DateTime())->modify(sprintf('-%s seconds', $gracePeriod + 1))->format('Y-m-d H:i:s'));

        // Act
        $filteredMessageChannels = $kernelAppFacade->filterMessageChannels($messageChannels);

        // Assert
        $this->assertSame([], $filteredMessageChannels);
    }

    /**
     * @return void
     */
    public function testFilterMessageChannelsReturnsNoGivenMessageChannelsWhenThereAreNoAppSpecificMessageChannels(): void
    {
        // Arrange
        $kernelAppFacade = $this->tester->getFacade();
        $messageChannels = ['foo', 'bar'];

        // Act
        $filteredMessageChannels = $kernelAppFacade->filterMessageChannels($messageChannels);

        // Assert
        $this->assertSame([], $filteredMessageChannels);
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
