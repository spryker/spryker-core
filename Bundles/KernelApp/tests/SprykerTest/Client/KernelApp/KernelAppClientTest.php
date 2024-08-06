<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\KernelApp;

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
use Spryker\Client\KernelApp\KernelAppClient;
use Spryker\Client\KernelApp\KernelAppDependencyProvider;
use Spryker\Shared\KernelApp\KernelAppConstants;
use Spryker\Shared\KernelAppExtension\RequestExpanderPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group KernelApp
 * @group KernelAppClientTest
 * Add your own group annotations below this line
 */
class KernelAppClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\KernelApp\KernelAppClientTester
     */
    protected KernelAppClientTester $tester;

    /**
     * @return void
     */
    public function testRequestIsExpendedWithHeaderFromExpanderPluginBeforeRequestIsSend(): void
    {
        // Arrange
        $kernelAppClient = new KernelAppClient();
        $expectedXMas = Uuid::uuid4()->toString();

        $this->tester->mockEnvironmentConfig(KernelAppConstants::TENANT_IDENTIFIER, 'tenant-identifier');

        // Mock GuzzleClient to be able to introspect the RequestInterface and manipulate the returned response
        $clientMock = Stub::make(Client::class, [
            'send' => function (RequestInterface $request) use ($expectedXMas): ResponseInterface {
                $this->assertTrue($request->hasHeader('x-mas'));
                $xMas = $request->getHeader('x-mas')[0];
                $this->assertSame($expectedXMas, $xMas);

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
            },
        ]);

        $this->tester->setDependency(KernelAppDependencyProvider::CLIENT_HTTP, new KernelAppToGuzzleHttpClientAdapter($clientMock));
        $this->tester->setDependency(KernelAppDependencyProvider::REQUEST_EXPANDER_PLUGINS, [
            new class ($expectedXMas) implements RequestExpanderPluginInterface {
                /**
                 * @var string
                 */
                protected string $xMas;

                /**
                 * @param string $xMas
                 */
                public function __construct(string $xMas)
                {
                    $this->xMas = $xMas;
                }

                /**
                 * @param \Psr\Http\Message\RequestInterface $acpHttpRequestTransfer
                 *
                 * @return \Psr\Http\Message\RequestInterface
                 */
                public function expandRequest(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpRequestTransfer
                {
                    return $acpHttpRequestTransfer->addHeader('x-mas', $this->xMas);
                }
            },
        ]);

        // Act
        $acpHttpRequestTransfer = new AcpHttpRequestTransfer();
        $acpHttpRequestTransfer
            ->setMethod('POST')
            ->setUri('www.example.com')
            ->setBody('{"foo": "bar"}');

        $acpHttpResponseTransfer = $kernelAppClient->request($acpHttpRequestTransfer);

        // Assert
        $this->assertInstanceOf(AcpHttpResponseTransfer::class, $acpHttpResponseTransfer);
    }
}
