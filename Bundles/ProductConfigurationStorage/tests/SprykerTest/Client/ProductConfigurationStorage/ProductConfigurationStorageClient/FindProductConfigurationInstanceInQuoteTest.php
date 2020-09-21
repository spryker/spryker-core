<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClient;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToCartClientBridge;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToCartClientInterface;
use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationStorage
 * @group ProductConfigurationStorageClient
 * @group FindProductConfigurationInstanceInQuoteTest
 * Add your own group annotations below this line
 */
class FindProductConfigurationInstanceInQuoteTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindProductConfigurationInstanceInQuoteFindsProductConfigurationInstanceInQuote(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder())->build();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods(['getCartClient'])
            ->getMock();

        $productConfigurationStorageFactoryMock
            ->method('getCartClient')
            ->willReturn($this->getProductConfigurationStorageToCartClientBridgeMock());

        // Act
        $productConfigurationInstanceTransfer = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->findProductConfigurationInstanceInQuote(
                $itemTransfer->getGroupKey(),
                $itemTransfer->getSku(),
                new QuoteTransfer()
            );

        // Assert
        $this->assertNotNull($productConfigurationInstanceTransfer);
        $this->assertInstanceOf(ProductConfigurationInstanceTransfer::class, $productConfigurationInstanceTransfer);
    }

    /**
     * @return void
     */
    public function testFindProductConfigurationInstanceInQuoteCartClientReturnsNull(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder())->build();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->getMock();

        // Act
        $productConfigurationInstanceTransfer = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->findProductConfigurationInstanceInQuote(
                $itemTransfer->getGroupKey(),
                $itemTransfer->getSku(),
                new QuoteTransfer()
            );

        // Assert
        $this->assertNull($productConfigurationInstanceTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToCartClientInterface
     */
    protected function getProductConfigurationStorageToCartClientBridgeMock(): ProductConfigurationStorageToCartClientInterface
    {
        $productConfigurationStorageToCartClientBridgeMock = $this->getMockBuilder(ProductConfigurationStorageToCartClientBridge::class)
            ->onlyMethods(['findQuoteItem'])
            ->disableOriginalConstructor()
            ->getMock();

        $productConfigurationStorageToCartClientBridgeMock
            ->method('findQuoteItem')
            ->willReturn((new ItemTransfer())->setProductConfigurationInstance(new ProductConfigurationInstanceTransfer()));

        return $productConfigurationStorageToCartClientBridgeMock;
    }
}
