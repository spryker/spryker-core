<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfiguration\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ProductConfiguration\ProductConfigurationFactory;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfiguration
 * @group ProductConfigurationClientTest
 * Add your own group annotations below this line
 */
class ProductConfigurationClientTest extends Unit
{
    protected const TEST_PRODUCT_CONFIGURATOR_REQUEST_KEY = 'test_plugin_request_key';
    protected const TEST_PRODUCT_CONFIGURATOR_RESPONSE_KEY = 'test_plugin_response_key';
    protected const TEST_CONFIGURATOR_REDIRECT_URL = 'testUrl';

    /**
     * @var \PHPUnit\Framework\MockObject\Builder\InvocationMocker
     */
    public $productConfiguratorRequestPluginMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $productConfiguratorResponsePluginMock;

    /**
     * @var \Spryker\Client\ProductConfiguration\ProductConfigurationClient $productConfigurationClient
     */
    protected $productConfigurationClient;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfiguration\ProductConfigurationFactory
     */
    protected $configurationFactoryMock;

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

        $this->configurationFactoryMock = $this->getMockBuilder(ProductConfigurationFactory::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept([
                'createProductConfiguratorRedirectResolver',
                'createProductConfiguratorResponseProcessor',
                'createQuoteProductConfigurationChecker'
            ])
            ->getMock();

        $this->productConfigurationClient = $this->tester->getClient()->setFactory($this->configurationFactoryMock);

        $this->productConfiguratorRequestPluginMock = $this->getMockBuilder(
            ProductConfiguratorRequestPluginInterface::class
        )->onlyMethods(['resolveProductConfiguratorRedirect'])->getMockForAbstractClass();

        $this->productConfiguratorResponsePluginMock = $this->getMockBuilder(
            ProductConfiguratorResponsePluginInterface::class
        )->onlyMethods(['processProductConfiguratorResponse'])->getMockForAbstractClass();

        $this->productConfiguratorRequestPluginMock->method('resolveProductConfiguratorRedirect')
            ->willReturn(
                (new ProductConfiguratorRedirectTransfer)
                    ->setConfiguratorRedirectUrl(static::TEST_CONFIGURATOR_REDIRECT_URL)
                    ->setIsSuccessful(true)
            );

        $this->productConfiguratorResponsePluginMock->method('processProductConfiguratorResponse')
            ->willReturn(
                (new ProductConfiguratorResponseProcessorResponseTransfer)
                    ->setIsSuccessful(true)
            );
    }

    /**
     * @return void
     */
    public function testPrepareProductConfiguratorRedirectWithSuccessFlow(): void
    {
        //Arrange
        $this->configurationFactoryMock->method('getProductConfiguratorRequestPlugins')->willReturn([
            static::TEST_PRODUCT_CONFIGURATOR_REQUEST_KEY => $this->productConfiguratorRequestPluginMock
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
        $this->assertEquals(static::TEST_CONFIGURATOR_REDIRECT_URL, $productConfiguratorRedirect->getConfiguratorRedirectUrl());
    }

    /**
     * @return void
     */
    public function testPrepareProductConfiguratorRedirectDefaultPlugin()
    {
        //Arrange
        $this->configurationFactoryMock->method('getProductConfiguratorRequestPlugins')->willReturn([]);
        $this->configurationFactoryMock->method('getDefaultProductConfiguratorRequestPlugin')
            ->willReturn( $this->productConfiguratorRequestPluginMock);

        $productConfigurationRequestTransfer = (new ProductConfiguratorRequestTransfer())
            ->setProductConfiguratorRequestData(
                (new ProductConfiguratorRequestDataTransfer())
                    ->setConfiguratorKey(static::TEST_PRODUCT_CONFIGURATOR_REQUEST_KEY)
            );

        //Act
        $productConfiguratorRedirect = $this->productConfigurationClient
            ->prepareProductConfiguratorRedirect($productConfigurationRequestTransfer);

        //Assert
        $this->assertEquals(static::TEST_CONFIGURATOR_REDIRECT_URL, $productConfiguratorRedirect->getConfiguratorRedirectUrl());
    }

    /**
     * @return void
     */
    public function testProcessProductConfiguratorResponseWithSuccessFlow()
    {
        //Arrange
        $this->configurationFactoryMock->method('getProductConfiguratorResponsePlugins')->willReturn([
            static::TEST_PRODUCT_CONFIGURATOR_RESPONSE_KEY => $this->productConfiguratorResponsePluginMock
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
    public function testProcessProductConfiguratorResponseDefaultPlugin()
    {
        //Arrange
        $this->configurationFactoryMock->method('getProductConfiguratorResponsePlugins')->willReturn([]);
        $this->configurationFactoryMock->method('getDefaultProductConfiguratorResponsePlugin')
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
    public function testIsQuoteProductConfigurationValidWithSuccessFlow()
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
    public function testIsQuoteProductConfigurationValidFalseWithNotCompletedProductConfiguration()
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
    public function testIsQuoteProductConfigurationValidEmptyQuoteDoNothing()
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
    public function testIsQuoteProductConfigurationValidItemsWithoutConfiguration()
    {
        $itemTransfer = (new ItemBuilder())->build();

        //Act
        $isQuoteProductConfigurationValid = $this->productConfigurationClient->isQuoteProductConfigurationValid(
            (new QuoteTransfer())->addItem($itemTransfer)
        );

        //Assert
        $this->assertTrue($isQuoteProductConfigurationValid);
    }
}
