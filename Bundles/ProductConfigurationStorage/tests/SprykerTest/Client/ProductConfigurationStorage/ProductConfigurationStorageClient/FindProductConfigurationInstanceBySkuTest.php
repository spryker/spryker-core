<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClient;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientBridge;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientInterface;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToStorageClientBridge;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToStorageClientInterface;
use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationStorage
 * @group ProductConfigurationStorageClient
 * @group FindProductConfigurationInstanceBySkuTest
 * Add your own group annotations below this line
 */
class FindProductConfigurationInstanceBySkuTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindProductConfigurationInstanceBySkuFindsProductConfigurationFromSession(): void
    {
        // Arrange
        $productConcreteTransfer = (new ProductConcreteBuilder())->build();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods(['getSessionClient'])
            ->getMock();

        $productConfigurationStorageFactoryMock
            ->method('getSessionClient')
            ->willReturn($this->getProductConfigurationStorageToSessionClientBridgeMock());

        // Act
        $productConfigurationInstanceTransfer = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->findProductConfigurationInstanceBySku($productConcreteTransfer->getSku());

        // Assert
        $this->assertNotNull($productConfigurationInstanceTransfer);
        $this->assertInstanceOf(ProductConfigurationInstanceTransfer::class, $productConfigurationInstanceTransfer);
    }

    /**
     * @return void
     */
    public function testFindProductConfigurationInstanceBySkuFindsProductConfigurationFromStorage(): void
    {
        // Arrange
        $productConcreteTransfer = (new ProductConcreteBuilder())->build();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods(['getStorageClient'])
            ->getMock();

        $productConfigurationStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getProductConfigurationStorageToStorageClientBridgeMock());

        // Act
        $productConfigurationInstanceTransfer = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->findProductConfigurationInstanceBySku($productConcreteTransfer->getSku());

        // Assert
        $this->assertNotNull($productConfigurationInstanceTransfer);
        $this->assertInstanceOf(ProductConfigurationInstanceTransfer::class, $productConfigurationInstanceTransfer);
    }

    /**
     * @return void
     */
    public function testFindProductConfigurationInstanceBySkuSessionAndStorageClientsReturnsNull(): void
    {
        // Arrange
        $productConcreteTransfer = (new ProductConcreteBuilder())->build();

        // Act
        $productConfigurationInstanceTransfer = $this->tester
            ->getClient()
            ->findProductConfigurationInstanceBySku($productConcreteTransfer->getSku());

        // Assert
        $this->assertNull($productConfigurationInstanceTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientInterface
     */
    protected function getProductConfigurationStorageToSessionClientBridgeMock(): ProductConfigurationStorageToSessionClientInterface
    {
        $productConfigurationStorageToSessionClientBridgeMock = $this->getMockBuilder(ProductConfigurationStorageToSessionClientBridge::class)
            ->onlyMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();

        $productConfigurationStorageToSessionClientBridgeMock
            ->method('get')
            ->willReturn(new ProductConfigurationInstanceTransfer());

        return $productConfigurationStorageToSessionClientBridgeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToStorageClientInterface
     */
    protected function getProductConfigurationStorageToStorageClientBridgeMock(): ProductConfigurationStorageToStorageClientInterface
    {
        $productConfigurationStorageToStorageClientBridgeMock = $this->getMockBuilder(ProductConfigurationStorageToStorageClientBridge::class)
            ->onlyMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();

        $productConfigurationStorageToStorageClientBridgeMock
            ->method('get')
            ->willReturn((new ProductConfigurationInstanceTransfer())->toArray());

        return $productConfigurationStorageToStorageClientBridgeMock;
    }
}
