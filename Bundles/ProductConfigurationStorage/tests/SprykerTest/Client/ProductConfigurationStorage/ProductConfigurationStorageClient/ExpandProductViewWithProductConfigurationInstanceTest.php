<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClient;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
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
 * @group ExpandProductViewWithProductConfigurationInstanceTest
 * Add your own group annotations below this line
 */
class ExpandProductViewWithProductConfigurationInstanceTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandProductViewWithProductConfigurationInstanceExpandsProductViewWithProductConfigurationInstance(): void
    {
        // Arrange
        $this->tester->setupStorageRedisConfig();
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder())->build();

        $this->tester->getClient()->storeProductConfigurationInstanceBySku($productConcreteTransfer->getSku(), $productConfigurationInstanceTransfer);

        $productViewTransfer = (new ProductViewTransfer())
            ->setIdProductConcrete($productConcreteTransfer->getIdProductConcrete())
            ->setSku($productConcreteTransfer->getSku());

        // Act
        $productViewTransfer = $this->tester
            ->getClient()
            ->expandProductViewWithProductConfigurationInstance($productViewTransfer);

        // Assert
        $this->assertNotNull($productViewTransfer->getProductConfigurationInstance());
        $this->assertEquals($productConfigurationInstanceTransfer, $productViewTransfer->getProductConfigurationInstance());
    }

    /**
     * @return void
     */
    public function testExpandProductViewWithProductConfigurationInstanceExpandsProductViewWithEmptyInstance(): void
    {
        // Arrange
        $this->tester->setupStorageRedisConfig();
        $productConcreteTransfer = $this->tester->haveProduct();

        $productViewTransfer = (new ProductViewTransfer())
            ->setIdProductConcrete($productConcreteTransfer->getIdProductConcrete())
            ->setSku($productConcreteTransfer->getSku());

        // Act
        $productViewTransfer = $this->tester
            ->getClient()
            ->expandProductViewWithProductConfigurationInstance($productViewTransfer);

        // Assert
        $this->assertNull($productViewTransfer->getProductConfigurationInstance());
    }

    /**
     * @return void
     */
    public function testExpandProductViewWithProductConfigurationInstanceExpandsProductViewForNonConcreteProduct(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder())->build();

        $this->tester->getClient()->storeProductConfigurationInstanceBySku($productConcreteTransfer->getSku(), $productConfigurationInstanceTransfer);

        $productViewTransfer = (new ProductViewTransfer())
            ->setSku($productConcreteTransfer->getSku());

        // Act
        $productViewTransfer = $this->tester
            ->getClient()
            ->expandProductViewWithProductConfigurationInstance($productViewTransfer);

        // Assert
        $this->assertNull($productViewTransfer->getProductConfigurationInstance());
    }

    /**
     * @return void
     */
    public function testExpandProductViewWithProductConfigurationInstanceExpandsProductViewWithInstanceFromStorage(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();

        $productViewTransfer = (new ProductViewTransfer())
            ->setIdProductConcrete($productConcreteTransfer->getIdProductConcrete())
            ->setSku($productConcreteTransfer->getSku());

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods(['getStorageClient'])
            ->getMock();

        $productConfigurationStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getProductConfigurationStorageToStorageClientBridgeMock());

        // Act
        $productViewTransfer = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->expandProductViewWithProductConfigurationInstance($productViewTransfer);

        // Assert
        $this->assertNotNull($productViewTransfer->getProductConfigurationInstance());
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
            ->willReturn((new ProductConfigurationStorageTransfer())->toArray());

        return $productConfigurationStorageToStorageClientBridgeMock;
    }
}
