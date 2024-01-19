<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Payment;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response as GuzzleHttpResponse;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\StreamInterface;
use Spryker\Client\Payment\Dependency\External\PaymentToGuzzleHttpClientAdapter;
use Spryker\Client\Payment\PaymentDependencyProvider;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Kernel\KernelConstants;
use SprykerTest\Client\Testify\Helper\ConfigHelperTrait;
use Symfony\Component\HttpFoundation\Request as SymfonyHttpRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyHttpResponse;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Payment
 * @group PaymentClientTest
 * Add your own group annotations below this line
 */
class PaymentClientTest extends Unit
{
    use ConfigHelperTrait;

    /**
     * @var \SprykerTest\Client\Payment\PaymentClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->getConfigHelper()->setConfig(
            KernelConstants::ENABLE_CONTAINER_OVERRIDING,
            true,
        );
    }

    /**
     * @return void
     */
    public function testAuthorizePaymentReturnsCorrectResponseIfRequestSuccessful(): void
    {
        // Arrange
        $httpClientMock = $this->getHttpClientMock();
        $responseMock = $this->getResponseMock('successful_request.json', SymfonyHttpResponse::HTTP_OK);
        $httpClientMock->method('request')->willReturn($responseMock);

        // Act
        $paymentAuthorizeResponseTransfer = $this->tester->getClient()
            ->authorizeForeignPayment($this->getPaymentAuthorizeRequestTransfer());

        // Assert
        $this->assertTrue($paymentAuthorizeResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testAuthorizePaymentReturnsCorrectResponseIfRequestUnsuccessful(): void
    {
        // Arrange
        $httpClientMock = $this->getHttpClientMock();
        $responseMock = $this->getResponseMock('unsuccessful_request.json', SymfonyHttpResponse::HTTP_OK);
        $httpClientMock->method('request')->willReturn($responseMock);

        // Act
        $paymentAuthorizeResponseTransfer = $this->tester->getClient()
            ->authorizeForeignPayment($this->getPaymentAuthorizeRequestTransfer());

        // Assert
        $this->assertFalse($paymentAuthorizeResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testAuthorizePaymentReturnsCorrectResponseIfRequestFailed(): void
    {
        // Arrange
        $httpClientMock = $this->getHttpClientMock();
        $responseMock = $this->getResponseMock('successful_request.json', SymfonyHttpResponse::HTTP_BAD_REQUEST);
        $httpClientMock->method('request')->willReturn($responseMock);

        // Act
        $paymentAuthorizeResponseTransfer = $this->tester->getClient()
            ->authorizeForeignPayment($this->getPaymentAuthorizeRequestTransfer());

        // Assert
        $this->assertFalse($paymentAuthorizeResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testAuthorizePaymentUsesContentOfResponseInErrorMessageWhenDebugingIsEnabled(): void
    {
        // Arrange
        $httpClientMock = $this->getHttpClientMock();
        $this->getConfigHelper()->setConfig(ApplicationConstants::ENABLE_APPLICATION_DEBUG, true);

        $responseMock = $this->getResponseMock('error_response.json', SymfonyHttpResponse::HTTP_BAD_REQUEST);
        $requestException = new RequestException('something went wrong', new Request('POST', 'url'), $responseMock);
        $httpClientMock->method('request')->willThrowException($requestException);

        // Act
        $paymentAuthorizeResponseTransfer = $this->tester->getClient()
            ->authorizeForeignPayment($this->getPaymentAuthorizeRequestTransfer());

        // Assert
        $this->assertFalse($paymentAuthorizeResponseTransfer->getIsSuccessful());

        $this->assertSame($this->getFixture('error_response.json'), $paymentAuthorizeResponseTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testAuthorizePaymentReturnsCorrectResponseWhenPaymentAuthorizeRequestTransferHasTenantIdentifier(): void
    {
        // Arrange
        $httpClientMock = $this->getHttpClientMock();
        $responseMock = $this->getResponseMock('successful_request.json', SymfonyHttpResponse::HTTP_OK);
        $paymentAuthorizeRequestTransfer = $this->getPaymentAuthorizeRequestTransfer();

        $httpClientMock
            ->expects($this->once())
            ->method('request')
            ->with(
                SymfonyHttpRequest::METHOD_POST,
                $paymentAuthorizeRequestTransfer->getRequestUrl(),
                [
                    RequestOptions::FORM_PARAMS => $paymentAuthorizeRequestTransfer->getPostData(),
                    RequestOptions::HEADERS => [
                        'X-Tenant-Identifier' => $paymentAuthorizeRequestTransfer->getTenantIdentifier(),
                        'X-Store-Reference' => $paymentAuthorizeRequestTransfer->getTenantIdentifier(),
                        'Accept' => 'application/json',
                    ],
                ],
            )->willReturn($responseMock);

        // Act
        $paymentAuthorizeResponseTransfer = $this->tester->getClient()
            ->authorizeForeignPayment($paymentAuthorizeRequestTransfer);

        // Assert
        $this->assertTrue($paymentAuthorizeResponseTransfer->getIsSuccessful());
    }

    /**
     * @return \GuzzleHttp\Client|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getHttpClientMock(): Client
    {
        $httpClientMock = $this->createMock(Client::class);
        $paymentToGuzzleHttpClientAdapter = new PaymentToGuzzleHttpClientAdapter(
            $httpClientMock,
        );

        $this->tester->setDependency(
            PaymentDependencyProvider::CLIENT_HTTP,
            $paymentToGuzzleHttpClientAdapter,
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
        $responseBody = $this->getFixture($responseFileName);
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

    /**
     * @return \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer
     */
    protected function getPaymentAuthorizeRequestTransfer(): PaymentAuthorizeRequestTransfer
    {
        return (new PaymentAuthorizeRequestTransfer())
            ->setRequestUrl('url-value')
            ->setTenantIdentifier('test');
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    protected function getFixture(string $fileName): string
    {
        return file_get_contents(codecept_data_dir('Fixtures/' . $fileName));
    }
}
