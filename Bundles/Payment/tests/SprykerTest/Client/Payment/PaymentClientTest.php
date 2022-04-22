<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Payment;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer;
use GuzzleHttp\Psr7\Response as GuzzleHttpResponse;
use Psr\Http\Message\StreamInterface;
use Spryker\Client\Payment\Dependency\External\PaymentToHttpClientAdapterInterface;
use Spryker\Client\Payment\PaymentDependencyProvider;
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
class PaymentClientTest extends Test
{
    /**
     * @var \SprykerTest\Client\Payment\PaymentClientTester
     */
    protected $tester;

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
     * @return \Spryker\Client\Payment\Dependency\External\PaymentToHttpClientAdapterInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getHttpClientMock(): PaymentToHttpClientAdapterInterface
    {
        $httpClientMock = $this->createMock(PaymentToHttpClientAdapterInterface::class);

        $this->tester->setDependency(
            PaymentDependencyProvider::CLIENT_HTTP,
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
            ->setRequestUrl('url-value');
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
