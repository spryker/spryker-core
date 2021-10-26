<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfiguration\Client\Sender;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToHttpClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceBridge;
use Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface;
use Spryker\Client\ProductConfiguration\Http\Exception\ProductConfigurationHttpRequestException;
use Spryker\Client\ProductConfiguration\Sender\ProductConfiguratorRequestSender;
use Spryker\Client\ProductConfiguration\Sender\ProductConfiguratorRequestSenderInterface;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfiguration
 * @group Client
 * @group Sender
 * @group ProductConfiguratorRequestSenderTest
 * Add your own group annotations below this line
 */
class ProductConfiguratorRequestSenderTest extends Unit
{
    /**
     * @uses \Spryker\Client\ProductConfiguration\Sender\ProductConfiguratorRequestSender::GLOSSARY_KEY_PRODUCT_CONFIGURATION_CAN_NOT_OBTAIN_ACCESS_TOKEN
     *
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_CAN_NOT_OBTAIN_ACCESS_TOKEN = 'product_configuration.access_token.request.error.can_not_obtain_access_token';

    /**
     * @var string
     */
    protected const DATE_TIME_CONFIGURATOR_KEY = 'DATE_TIME_CONFIGURATOR';

    /**
     * @var \SprykerTest\Client\ProductConfiguration\ProductConfigurationClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSendsRequestAndReturnsRedirectUrl(): void
    {
        // Arrange
        $productConfiguratorRequestTransfer = (new ProductConfiguratorRequestTransfer())->setProductConfiguratorRequestData(
            (new ProductConfiguratorRequestDataTransfer())->setConfiguratorKey(static::DATE_TIME_CONFIGURATOR_KEY),
        )->setAccessTokenRequestUrl(getenv('SPRYKER_PRODUCT_CONFIGURATOR_HOST'));

        $httpClientMock = $this->getHttpClientMock();

        $httpClientMock->method('request')->willReturn(
            $this->createConfiguratorResponse(),
        );

        $productConfiguratorRequestSenderMock = $this->getProductConfiguratorRequestSenderMock(
            $httpClientMock,
            $this->createProductConfigurationToUtilEncodingServiceBridge(),
        );

        // Act
        $productConfiguratorRedirectTransfer = $productConfiguratorRequestSenderMock->sendProductConfiguratorAccessTokenRequest($productConfiguratorRequestTransfer);

        // Assert
        $this->assertTrue($productConfiguratorRedirectTransfer->getIsSuccessful(), 'Expects isSuccessful to be true.');
        $this->assertEmpty($productConfiguratorRedirectTransfer->getMessages(), 'Expects error messages to be empty.');
        $this->assertNotNull($productConfiguratorRedirectTransfer->getConfiguratorRedirectUrl(), 'Expects redirect url to be provided.');
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenAccessTokenRequestUrlIsNotProvided(): void
    {
        // Arrange
        $productConfiguratorRequestTransfer = (new ProductConfiguratorRequestTransfer())->setProductConfiguratorRequestData(
            (new ProductConfiguratorRequestDataTransfer())->setConfiguratorKey(static::DATE_TIME_CONFIGURATOR_KEY),
        );

        $httpClientMock = $this->getHttpClientMock();

        $httpClientMock->method('request')->willReturn(
            $this->createConfiguratorResponse(),
        );

        $productConfiguratorRequestSenderMock = $this->getProductConfiguratorRequestSenderMock(
            $httpClientMock,
            $this->createProductConfigurationToUtilEncodingServiceBridge(),
        );

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $productConfiguratorRequestSenderMock->sendProductConfiguratorAccessTokenRequest($productConfiguratorRequestTransfer);
    }

    /**
     * @return void
     */
    public function testReturnsNotSuccessfulResponseWhenRequestFails(): void
    {
        // Arrange
        $productConfiguratorRequestTransfer = (new ProductConfiguratorRequestTransfer())->setProductConfiguratorRequestData(
            (new ProductConfiguratorRequestDataTransfer())->setConfiguratorKey(static::DATE_TIME_CONFIGURATOR_KEY),
        )->setAccessTokenRequestUrl(getenv('SPRYKER_PRODUCT_CONFIGURATOR_HOST'));

        $httpClientMock = $this->getHttpClientMock();

        $httpClientMock->method('request')->willThrowException(
            new ProductConfigurationHttpRequestException(),
        );

        $productConfiguratorRequestSenderMock = $this->getProductConfiguratorRequestSenderMock(
            $httpClientMock,
            $this->createProductConfigurationToUtilEncodingServiceBridge(),
        );

        // Act
        $productConfiguratorRedirectTransfer = $productConfiguratorRequestSenderMock->sendProductConfiguratorAccessTokenRequest($productConfiguratorRequestTransfer);

        // Assert
        $this->assertFalse($productConfiguratorRedirectTransfer->getIsSuccessful(), 'Expects isSuccessful to be false.');
        $this->assertNull($productConfiguratorRedirectTransfer->getConfiguratorRedirectUrl(), 'Expects redirect url to be empty.');
        $this->assertSame(
            $productConfiguratorRedirectTransfer->getMessages()->getIterator()->current()->getValue(),
            static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_CAN_NOT_OBTAIN_ACCESS_TOKEN,
            'Expects error message to be provided.',
        );
    }

    /**
     * @param string|null $errorMessage
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function createConfiguratorResponse(?string $errorMessage = null): ResponseInterface
    {
        $responseBody = json_encode([
            'is_successful' => $errorMessage === null,
            'configurator_redirect_url' => $errorMessage ? null : 'some.url',
            'message' => $errorMessage,
        ]);

        return new Response(
            SymfonyResponse::HTTP_OK,
            [],
            $responseBody,
        );
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToHttpClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getHttpClientMock(): ProductConfigurationToHttpClientInterface
    {
        return $this->getMockBuilder(ProductConfigurationToHttpClientInterface::class)
            ->onlyMethods(['request'])
            ->getMock();
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface
     */
    protected function createProductConfigurationToUtilEncodingServiceBridge(): ProductConfigurationToUtilEncodingServiceInterface
    {
        return new ProductConfigurationToUtilEncodingServiceBridge(
            $this->tester->getLocator()->utilEncoding()->service(),
        );
    }

    /**
     * @param \Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToHttpClientInterface $httpClient
     * @param \Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface $utilEncodingService
     *
     * @return \Spryker\Client\ProductConfiguration\Sender\ProductConfiguratorRequestSenderInterface
     */
    protected function getProductConfiguratorRequestSenderMock(
        ProductConfigurationToHttpClientInterface $httpClient,
        ProductConfigurationToUtilEncodingServiceInterface $utilEncodingService
    ): ProductConfiguratorRequestSenderInterface {
        return $this->getMockBuilder(ProductConfiguratorRequestSender::class)
            ->setConstructorArgs([
                $httpClient,
                $utilEncodingService,
            ])
            ->onlyMethods([])
            ->getMock();
    }
}
