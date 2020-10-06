<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClient;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConfiguratorResponseBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToCartClientBridge;
use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory;
use Spryker\Client\ProductConfigurationStorage\Validator\ProductConfiguratorResponseValidatorInterface;
use Spryker\Client\ProductConfigurationStorage\Writer\ProductConfigurationInstanceWriter;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationStorage
 * @group ProductConfigurationStorageClient
 * @group ProcessProductConfiguratorCheckSumResponseTest
 * Add your own group annotations below this line
 */
class ProcessProductConfiguratorCheckSumResponseTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProcessProductConfiguratorCheckSumResponseWillSaveToSessionWhenSourcePdp(): void
    {
        $cartClientMock = $this->getMockBuilder(ProductConfigurationStorageToCartClientBridge::class)
            ->onlyMethods(['findQuoteItem', 'getQuote', 'replaceItem'])
            ->disableOriginalConstructor()->getMock();

        $productConfigurationInstanceWriterMock = $this->getMockBuilder(ProductConfigurationInstanceWriter::class)
            ->onlyMethods(['storeProductConfigurationInstanceBySku'])
            ->disableOriginalConstructor()->getMock();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods([
                'getCartClient',
                'createProductConfigurationInstanceWriter',
                'createProductConfiguratorCheckSumResponseValidators',
            ])->getMock();

        $productConfigurationStorageFactoryMock
            ->method('getCartClient')
            ->willReturn($cartClientMock);

        $productConfigurationStorageFactoryMock
            ->method('createProductConfigurationInstanceWriter')
            ->willReturn($productConfigurationInstanceWriterMock);

        $productConfigurationStorageFactoryMock
            ->method('createProductConfiguratorCheckSumResponseValidators')
            ->willReturn([]);

        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseBuilder())
            ->withProductConfigurationInstance()
            ->build();

        $productConfigurationInstanceWriterMock->expects($this->once())
            ->method('storeProductConfigurationInstanceBySku');

        // Act
        $productConfiguratorResponseProcessorResponseTransfer = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->processProductConfiguratorCheckSumResponse($productConfiguratorResponseTransfer, []);

        // Assert
        $this->assertTrue($productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testProcessProductConfiguratorCheckSumResponseWillReplaceItemInQuoteWhenSourceCart(): void
    {
        $cartClientMock = $this->getMockBuilder(ProductConfigurationStorageToCartClientBridge::class)
            ->onlyMethods(['findQuoteItem', 'getQuote', 'replaceItem'])
            ->disableOriginalConstructor()->getMock();

        $cartClientMock->method('getQuote')->willReturn(new QuoteTransfer());
        $cartClientMock->method('findQuoteItem')->willReturn(new ItemTransfer());
        $cartClientMock->method('replaceItem')->willReturn(
            (new QuoteResponseTransfer())->setIsSuccessful(true)
        );

        $productConfigurationInstanceWriterMock = $this->getMockBuilder(ProductConfigurationInstanceWriter::class)
            ->onlyMethods(['storeProductConfigurationInstanceBySku'])
            ->disableOriginalConstructor()->getMock();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods([
                'getCartClient',
                'createProductConfigurationInstanceWriter',
                'createProductConfiguratorCheckSumResponseValidators',
            ])->getMock();

        $productConfigurationStorageFactoryMock
            ->method('getCartClient')
            ->willReturn($cartClientMock);

        $productConfigurationStorageFactoryMock
            ->method('createProductConfigurationInstanceWriter')
            ->willReturn($productConfigurationInstanceWriterMock);

        $productConfigurationStorageFactoryMock
            ->method('createProductConfiguratorCheckSumResponseValidators')
            ->willReturn([]);

        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseBuilder([
            ProductConfiguratorResponseTransfer::SOURCE_TYPE => 'SOURCE_TYPE_CART',
            ]))
            ->withProductConfigurationInstance()
            ->build();

        $productConfigurationInstanceWriterMock->expects($this->never())
            ->method('storeProductConfigurationInstanceBySku');

        // Act
        $productConfiguratorResponseProcessorResponseTransfer = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->processProductConfiguratorCheckSumResponse($productConfiguratorResponseTransfer, []);

        // Assert
        $this->assertTrue($productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testProcessProductConfiguratorCheckSumResponseWillSetProcessorResponseResultToFalseWhenNotValidResponseData(): void
    {
        $productConfiguratorResponseValidatorMock = $this->getMockBuilder(
            ProductConfiguratorResponseValidatorInterface::class
        )->onlyMethods(['validate'])->getMock();

        $productConfiguratorResponseValidatorMock->expects($this->once())->method('validate')
            ->willReturn((new ProductConfiguratorResponseProcessorResponseTransfer())->setIsSuccessful(false));

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods([
                'createProductConfiguratorCheckSumResponseValidators',
            ])->getMock();

        $productConfigurationStorageFactoryMock
            ->method('createProductConfiguratorCheckSumResponseValidators')
            ->willReturn([
                $productConfiguratorResponseValidatorMock,
            ]);

        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseBuilder())
            ->withProductConfigurationInstance()
            ->build();

        // Act
        $productConfiguratorResponseProcessorResponseTransfer = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->processProductConfiguratorCheckSumResponse($productConfiguratorResponseTransfer, []);

        // Assert
        $this->assertFalse($productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testProcessProductConfiguratorCheckSumResponseWillSetProcessorResponseResultToFalseOnCartReplaceFail(): void
    {
        $cartClientMock = $this->getMockBuilder(ProductConfigurationStorageToCartClientBridge::class)
            ->onlyMethods(['findQuoteItem', 'getQuote', 'replaceItem'])
            ->disableOriginalConstructor()->getMock();

        $cartClientMock->method('getQuote')->willReturn(new QuoteTransfer());
        $cartClientMock->method('findQuoteItem')->willReturn(new ItemTransfer());
        $cartClientMock->method('replaceItem')->willReturn(
            (new QuoteResponseTransfer())->setIsSuccessful(false)
        );

        $productConfigurationInstanceWriterMock = $this->getMockBuilder(ProductConfigurationInstanceWriter::class)
            ->onlyMethods(['storeProductConfigurationInstanceBySku'])
            ->disableOriginalConstructor()->getMock();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods([
                'getCartClient',
                'createProductConfigurationInstanceWriter',
                'createProductConfiguratorCheckSumResponseValidators',
            ])->getMock();

        $productConfigurationStorageFactoryMock
            ->method('getCartClient')
            ->willReturn($cartClientMock);

        $productConfigurationStorageFactoryMock
            ->method('createProductConfigurationInstanceWriter')
            ->willReturn($productConfigurationInstanceWriterMock);

        $productConfigurationStorageFactoryMock
            ->method('createProductConfiguratorCheckSumResponseValidators')
            ->willReturn([]);

        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseBuilder([
            ProductConfiguratorResponseTransfer::SOURCE_TYPE => 'SOURCE_TYPE_CART',
        ]))
            ->withProductConfigurationInstance()
            ->build();

        $productConfigurationInstanceWriterMock->expects($this->never())
            ->method('storeProductConfigurationInstanceBySku');

        // Act
        $productConfiguratorResponseProcessorResponseTransfer = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->processProductConfiguratorCheckSumResponse($productConfiguratorResponseTransfer, []);

        // Assert
        $this->assertFalse($productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful());
    }
}
