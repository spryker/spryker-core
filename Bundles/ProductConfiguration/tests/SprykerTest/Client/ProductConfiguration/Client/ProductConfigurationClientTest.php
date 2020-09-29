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
    protected const TEST_CONFIGURATOR_REDIRECT_URL = 'testUrl';

    /**
     * @var \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productConfiguratorRequestPluginMock;

    /**
     * @var \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productConfiguratorResponsePluginMock;

    /**
     * @var \Spryker\Client\ProductConfiguration\ProductConfigurationClient $productConfigurationClient
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
            ])
            ->getMock();

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
                    ->setConfiguratorRedirectUrl(static::TEST_CONFIGURATOR_REDIRECT_URL)
                    ->setIsSuccessful(true)
            );

        $this->productConfiguratorResponsePluginMock->method('processProductConfiguratorResponse')
            ->willReturn(
                (new ProductConfiguratorResponseProcessorResponseTransfer())
                    ->setIsSuccessful(true)
            );
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
        $this->assertSame(static::TEST_CONFIGURATOR_REDIRECT_URL, $productConfiguratorRedirect->getConfiguratorRedirectUrl());
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
        $this->assertSame(static::TEST_CONFIGURATOR_REDIRECT_URL, $productConfiguratorRedirect->getConfiguratorRedirectUrl());
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
}
