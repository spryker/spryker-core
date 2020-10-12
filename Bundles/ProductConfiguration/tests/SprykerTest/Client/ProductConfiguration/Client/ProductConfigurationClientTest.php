<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfiguration\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Psr\Http\Message\ResponseInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCurrencyClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToStoreClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToHttpClientAdapter;
use Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingInterface;
use Spryker\Client\ProductConfiguration\Http\Exception\ProductConfigurationHttpRequestException;
use Spryker\Client\ProductConfiguration\ProductConfigurationFactory;
use Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorResponseValidatorInterface;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestExpanderInterface;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfiguration
 * @group Business
 * @group ProductConfigurationClientTest
 * Add your own group annotations below this line
 */
class ProductConfigurationClientTest extends Unit
{
    protected const TEST_PRODUCT_CONFIGURATOR_REQUEST_KEY = 'test_plugin_request_key';
    protected const TEST_PRODUCT_CONFIGURATOR_RESPONSE_KEY = 'test_plugin_response_key';
    protected const TEST_CONFIGURATOR_REDIRECT_RESPONSE = '{"isSuccessful":true,"configuratorRedirectUrl":"testUrl","messages":[]}';
    protected const TEST_CONFIGURATOR_REDIRECT_RESPONSE_DATA = [
        'isSuccessful' => true,
        'configuratorRedirectUrl' => 'testUrl',
        'messages' => [],
    ];
    protected const TEST_CONFIGURATOR_ACCESS_TOKEN_URL = 'test_access_token_url';

    /**
     * @var \Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToHttpClientAdapter|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $httpClientMock;

    /**
     * @var \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productConfiguratorRequestPluginMock;

    /**
     * @var \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productConfiguratorResponsePluginMock;

    /**
     * @var \Spryker\Client\ProductConfiguration\ProductConfigurationClientInterface $productConfigurationClient
     */
    protected $productConfigurationClient;

    /**
     * @var \Spryker\Client\ProductConfiguration\ProductConfigurationFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productConfigurationFactoryMock;

    /**
     * @var \SprykerTest\Client\ProductConfiguration\ProductConfigurationClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->productConfigurationFactoryMock = $this->getMockBuilder(ProductConfigurationFactory::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept([
                'createProductConfiguratorRedirectResolver',
                'createProductConfiguratorResponseProcessor',
                'createQuoteProductConfigurationChecker',
                'createProductConfiguratorAccessTokenRedirectResolver',
                'createProductConfiguratorRequestDataExpander',
                'createProductConfiguratorCheckSumResponseValidatorComposite',
            ])
            ->getMock();

        $storeClientMock = $this->getMockBuilder(ProductConfigurationToStoreClientInterface::class)
            ->onlyMethods(['getCurrentStore'])->getMockForAbstractClass();

        $storeClientMock->method('getCurrentStore')->willReturn(new StoreTransfer());

        $this->productConfigurationFactoryMock->method('getStoreClient')
            ->willReturn($storeClientMock);

        $currencyClientMock = $this->getMockBuilder(ProductConfigurationToCurrencyClientInterface::class)
            ->onlyMethods(['getCurrent'])->getMockForAbstractClass();

        $currencyClientMock->method('getCurrent')->willReturn(new CurrencyTransfer());

        $this->productConfigurationFactoryMock->method('getCurrencyClient')
            ->willReturn($currencyClientMock);

        $this->productConfigurationClient = $this->tester->getClient()->setFactory($this->productConfigurationFactoryMock);

        $this->productConfiguratorRequestPluginMock = $this->getMockBuilder(
            ProductConfiguratorRequestPluginInterface::class
        )->onlyMethods(['resolveProductConfiguratorRedirect'])->getMockForAbstractClass();

        $this->productConfiguratorResponsePluginMock = $this->getMockBuilder(
            ProductConfiguratorResponsePluginInterface::class
        )->onlyMethods(['processProductConfiguratorResponse'])->getMockForAbstractClass();

        $this->productConfiguratorRequestPluginMock->method('resolveProductConfiguratorRedirect')
            ->willReturn(
                (new ProductConfiguratorRedirectTransfer())
                    ->setConfiguratorRedirectUrl(static::TEST_CONFIGURATOR_REDIRECT_RESPONSE)
                    ->setIsSuccessful(true)
            );

        $this->productConfiguratorResponsePluginMock->method('processProductConfiguratorResponse')
            ->willReturn(
                (new ProductConfiguratorResponseProcessorResponseTransfer())
                    ->setIsSuccessful(true)
            );

        $this->httpClientMock = $this->getMockBuilder(ProductConfigurationToHttpClientAdapter::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['request'])
            ->getMock();
    }

    /**
     * @return void
     */
    public function testPrepareProductConfiguratorRedirectWithSuccessFlow(): void
    {
        //Arrange
        $this->productConfigurationFactoryMock->method('getProductConfiguratorRequestPlugins')->willReturn([
            static::TEST_PRODUCT_CONFIGURATOR_REQUEST_KEY => $this->productConfiguratorRequestPluginMock,
        ]);

        $productConfigurationRequestTransfer = (new ProductConfiguratorRequestTransfer())
            ->setProductConfiguratorRequestData(
                (new ProductConfiguratorRequestDataTransfer())
                    ->setConfiguratorKey(static::TEST_PRODUCT_CONFIGURATOR_REQUEST_KEY)
            );

        //Act
        $productConfiguratorRedirect = $this->productConfigurationClient
            ->prepareProductConfiguratorRedirect($productConfigurationRequestTransfer);

        //Assert
        $this->assertSame(static::TEST_CONFIGURATOR_REDIRECT_RESPONSE, $productConfiguratorRedirect->getConfiguratorRedirectUrl());
    }

    /**
     * @return void
     */
    public function testPrepareProductConfiguratorRedirectWillUseDefaultPlugin(): void
    {
        //Arrange
        $this->productConfigurationFactoryMock->method('getProductConfiguratorRequestPlugins')->willReturn([]);
        $this->productConfigurationFactoryMock->method('getDefaultProductConfiguratorRequestPlugin')
            ->willReturn($this->productConfiguratorRequestPluginMock);

        $productConfigurationRequestTransfer = (new ProductConfiguratorRequestTransfer())
            ->setProductConfiguratorRequestData(
                (new ProductConfiguratorRequestDataTransfer())
                    ->setConfiguratorKey(static::TEST_PRODUCT_CONFIGURATOR_REQUEST_KEY)
            );

        //Act
        $productConfiguratorRedirect = $this->productConfigurationClient
            ->prepareProductConfiguratorRedirect($productConfigurationRequestTransfer);

        //Assert
        $this->assertSame(static::TEST_CONFIGURATOR_REDIRECT_RESPONSE, $productConfiguratorRedirect->getConfiguratorRedirectUrl());
    }

    /**
     * @return void
     */
    public function testProcessProductConfiguratorResponseWithSuccessFlow(): void
    {
        //Arrange
        $this->productConfigurationFactoryMock->method('getProductConfiguratorResponsePlugins')->willReturn([
            static::TEST_PRODUCT_CONFIGURATOR_RESPONSE_KEY => $this->productConfiguratorResponsePluginMock,
        ]);

        $productConfigurationResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setProductConfigurationInstance(
                (new ProductConfigurationInstanceTransfer())
                    ->setConfiguratorKey(static::TEST_PRODUCT_CONFIGURATOR_RESPONSE_KEY)
            );

        //Act
        $productConfiguratorResponseProcessorResponseTransfer = $this->productConfigurationClient
            ->processProductConfiguratorResponse($productConfigurationResponseTransfer, []);

        //Assert
        $this->assertTrue($productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testProcessProductConfiguratorResponseWillUseDefaultPlugin(): void
    {
        //Arrange
        $this->productConfigurationFactoryMock->method('getProductConfiguratorResponsePlugins')->willReturn([]);
        $this->productConfigurationFactoryMock->method('getDefaultProductConfiguratorResponsePlugin')
            ->willReturn($this->productConfiguratorResponsePluginMock);

        $productConfigurationResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setProductConfigurationInstance(
                (new ProductConfigurationInstanceTransfer())
                    ->setConfiguratorKey(static::TEST_PRODUCT_CONFIGURATOR_RESPONSE_KEY)
            );

        //Act
        $productConfiguratorResponseProcessorResponseTransfer = $this->productConfigurationClient
            ->processProductConfiguratorResponse($productConfigurationResponseTransfer, []);

        //Assert
        $this->assertTrue($productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testIsQuoteProductConfigurationValidWithSuccessFlow(): void
    {
        //Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceTransfer())
            ->setIsComplete(true);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => $productConfigurationInstanceTransfer,
        ]))->build();

        //Act
        $isQuoteProductConfigurationValid = $this->productConfigurationClient->isQuoteProductConfigurationValid(
            (new QuoteTransfer())->addItem($itemTransfer)
        );

        //Assert
        $this->assertTrue($isQuoteProductConfigurationValid);
    }

    /**
     * @return void
     */
    public function testIsQuoteProductConfigurationValidFalseWithNotCompletedProductConfiguration(): void
    {
        //Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceTransfer())
            ->setIsComplete(false);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => $productConfigurationInstanceTransfer,
        ]))->build();

        //Act
        $isQuoteProductConfigurationValid = $this->productConfigurationClient->isQuoteProductConfigurationValid(
            (new QuoteTransfer())->addItem($itemTransfer)
        );

        //Assert
        $this->assertFalse($isQuoteProductConfigurationValid);
    }

    /**
     * @return void
     */
    public function testIsQuoteProductConfigurationValidEmptyQuoteDoNothing(): void
    {
        //Act
        $isQuoteProductConfigurationValid = $this->productConfigurationClient->isQuoteProductConfigurationValid(
            (new QuoteTransfer())
        );

        //Assert
        $this->assertTrue($isQuoteProductConfigurationValid);
    }

    /**
     * @return void
     */
    public function testIsQuoteProductConfigurationValidItemsWithoutConfiguration(): void
    {
        $itemTransfer = (new ItemBuilder())->build();

        //Act
        $isQuoteProductConfigurationValid = $this->productConfigurationClient->isQuoteProductConfigurationValid(
            (new QuoteTransfer())->addItem($itemTransfer)
        );

        //Assert
        $this->assertTrue($isQuoteProductConfigurationValid);
    }

    /**
     * @return void
     */
    public function testResolveProductConfiguratorAccessTokenRedirectWillReturnRedirectUrl(): void
    {
        //Arrange
        $productConfigurationRequestTransfer = (new ProductConfiguratorRequestTransfer())
            ->setProductConfiguratorRequestData(
                (new ProductConfiguratorRequestDataTransfer())
                    ->setConfiguratorKey(static::TEST_PRODUCT_CONFIGURATOR_REQUEST_KEY)
            );

        $productConfiguratorRequestExpanderPluginMock = $this->getMockBuilder(
            ProductConfiguratorRequestExpanderInterface::class
        )->onlyMethods(['expand'])->getMockForAbstractClass();

        $productConfiguratorRequestExpanderPluginMock->expects($this->once())->method('expand')
            ->with($productConfigurationRequestTransfer)
            ->willReturn(
                $productConfigurationRequestTransfer->setAccessTokenRequestUrl(
                    static::TEST_CONFIGURATOR_ACCESS_TOKEN_URL
                )
            );

        $this->productConfigurationFactoryMock->method('getProductConfiguratorRequestExpanderPlugins')
            ->willReturn([
                static::TEST_PRODUCT_CONFIGURATOR_REQUEST_KEY => $productConfiguratorRequestExpanderPluginMock,
            ]);

        $utilEncodingMock = $this->getMockBuilder(ProductConfigurationToUtilEncodingInterface::class)
            ->onlyMethods(['decodeJson'])
            ->getMockForAbstractClass();

        $utilEncodingMock->method('decodeJson')->willReturn(static::TEST_CONFIGURATOR_REDIRECT_RESPONSE_DATA);

        $this->productConfigurationFactoryMock->method('getUtilEncodingService')
            ->willReturn($utilEncodingMock);

        $responseMock = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getBody'])
            ->getMockForAbstractClass();

        $responseMock->expects($this->once())->method('getBody')
            ->willReturn(static::TEST_CONFIGURATOR_REDIRECT_RESPONSE);

        $this->httpClientMock->expects($this->once())->method('request')->willReturn($responseMock);

        $this->productConfigurationFactoryMock->method('getProductConfigurationHttpClient')
            ->willReturn($this->httpClientMock);

        //Act
        $productConfiguratorRedirectTransfer = $this->productConfigurationClient
            ->resolveProductConfiguratorAccessTokenRedirect($productConfigurationRequestTransfer);

        //Assert
        $this->assertTrue($productConfiguratorRedirectTransfer->getIsSuccessful());
        $this->assertSame(
            'testUrl',
            $productConfiguratorRedirectTransfer->getConfiguratorRedirectUrl()
        );
    }

    /**
     * @return void
     */
    public function testResolveProductConfiguratorAccessTokenRedirectWillFailWithoutAccessTokenUrl(): void
    {
        //Arrange
        $productConfigurationRequestTransfer = (new ProductConfiguratorRequestTransfer())
            ->setProductConfiguratorRequestData(
                (new ProductConfiguratorRequestDataTransfer())
                    ->setConfiguratorKey(static::TEST_PRODUCT_CONFIGURATOR_REQUEST_KEY)
            );

        $this->productConfigurationFactoryMock->method('getProductConfiguratorRequestExpanderPlugins')
            ->willReturn([]);

        //Assert
        $this->expectException(RequiredTransferPropertyException::class);

        //Act
        $this->productConfigurationClient
            ->resolveProductConfiguratorAccessTokenRedirect($productConfigurationRequestTransfer);
    }

    /**
     * @return void
     */
    public function testResolveProductConfiguratorAccessTokenRedirectWillReturnIsSuccessfulFalseWhenRequestFail(): void
    {
        //Arrange
        $productConfigurationRequestTransfer = (new ProductConfiguratorRequestTransfer())
            ->setProductConfiguratorRequestData(
                (new ProductConfiguratorRequestDataTransfer())
                    ->setConfiguratorKey(static::TEST_PRODUCT_CONFIGURATOR_REQUEST_KEY)
            );

        $productConfiguratorRequestExpanderPluginMock = $this->getMockBuilder(
            ProductConfiguratorRequestExpanderInterface::class
        )->onlyMethods(['expand'])->getMock();

        $productConfiguratorRequestExpanderPluginMock->expects($this->once())->method('expand')
            ->with($productConfigurationRequestTransfer)
            ->willReturn(
                $productConfigurationRequestTransfer->setAccessTokenRequestUrl(
                    static::TEST_CONFIGURATOR_ACCESS_TOKEN_URL
                )
            );

        $this->productConfigurationFactoryMock->method('getProductConfiguratorRequestExpanderPlugins')
            ->willReturn([
                static::TEST_PRODUCT_CONFIGURATOR_REQUEST_KEY => $productConfiguratorRequestExpanderPluginMock,
            ]);

        $this->httpClientMock->expects($this->once())->method('request')
            ->willThrowException(new ProductConfigurationHttpRequestException('test_exception_throw'));

        $this->productConfigurationFactoryMock->method('getProductConfigurationHttpClient')
            ->willReturn($this->httpClientMock);

        //Act
        $productConfiguratorRedirectTransfer = $this->productConfigurationClient
            ->resolveProductConfiguratorAccessTokenRedirect($productConfigurationRequestTransfer);

        //Assert
        $this->assertFalse($productConfiguratorRedirectTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testValidateProductConfiguratorCheckSumResponseWillSetIsSuccessTrueWhenValidationPass(): void
    {
        //Arrange
        $this->productConfigurationFactoryMock->method('createProductConfiguratorCheckSumResponseValidators')
            ->willReturn([]);

        $productConfiguratorResponseProcessorResponseTransfer = (new ProductConfiguratorResponseProcessorResponseTransfer())
            ->setIsSuccessful(true);

        //Act
        $productConfiguratorResponseProcessorResponseTransferValidated = $this->productConfigurationClient
            ->validateProductConfiguratorCheckSumResponse($productConfiguratorResponseProcessorResponseTransfer, []);

        //Assert
        $this->assertTrue($productConfiguratorResponseProcessorResponseTransferValidated->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testValidateProductConfiguratorCheckSumResponseWillSetIsSuccessFalseWhenValidationFail(): void
    {
        //Arrange
        $validatorMock = $this->getMockBuilder(ProductConfiguratorResponseValidatorInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validateProductConfiguratorCheckSumResponse'])
            ->getMock();

        $validatorMock->method('validateProductConfiguratorCheckSumResponse')->willReturn(
            (new ProductConfiguratorResponseProcessorResponseTransfer())->setIsSuccessful(false)
        );

        $this->productConfigurationFactoryMock->method('createProductConfiguratorCheckSumResponseValidators')
            ->willReturn([
                $validatorMock,
            ]);

        $productConfiguratorResponseProcessorResponseTransfer = (new ProductConfiguratorResponseProcessorResponseTransfer())
            ->setIsSuccessful(true);

        //Act
        $productConfiguratorResponseProcessorResponseTransferValidated = $this->productConfigurationClient
            ->validateProductConfiguratorCheckSumResponse($productConfiguratorResponseProcessorResponseTransfer, []);

        //Assert
        $this->assertFalse($productConfiguratorResponseProcessorResponseTransferValidated->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testValidateProductConfiguratorCheckSumResponseWillSetIsSuccessFalseAndReturnOnFirstFailedValidator(): void
    {
        //Arrange
        $validatorMockOne = $this->getMockBuilder(ProductConfiguratorResponseValidatorInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validateProductConfiguratorCheckSumResponse'])
            ->getMock();

        $validatorMockOne->method('validateProductConfiguratorCheckSumResponse')->willReturn(
            (new ProductConfiguratorResponseProcessorResponseTransfer())->setIsSuccessful(false)
        );

        $validatorMockTwo = $this->getMockBuilder(ProductConfiguratorResponseValidatorInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validateProductConfiguratorCheckSumResponse'])
            ->getMock();

        $validatorMockTwo->expects($this->never())->method('validateProductConfiguratorCheckSumResponse');

        $this->productConfigurationFactoryMock->method('createProductConfiguratorCheckSumResponseValidators')
            ->willReturn([
                $validatorMockOne,
                $validatorMockTwo,
            ]);

        $productConfiguratorResponseProcessorResponseTransfer = (new ProductConfiguratorResponseProcessorResponseTransfer())
            ->setIsSuccessful(true);

        //Act
        $productConfiguratorResponseProcessorResponseTransferValidated = $this->productConfigurationClient
            ->validateProductConfiguratorCheckSumResponse($productConfiguratorResponseProcessorResponseTransfer, []);

        //Assert
        $this->assertFalse($productConfiguratorResponseProcessorResponseTransferValidated->getIsSuccessful());
    }
}
