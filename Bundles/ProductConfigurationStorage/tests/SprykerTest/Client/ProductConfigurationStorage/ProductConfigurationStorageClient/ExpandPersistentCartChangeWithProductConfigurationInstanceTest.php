<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClient;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;
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
 * @group ExpandPersistentCartChangeWithProductConfigurationInstanceTest
 * Add your own group annotations below this line
 */
class ExpandPersistentCartChangeWithProductConfigurationInstanceTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandPersistentCartChangeWithProductConfigurationInstanceExpandsItemWithProductConfigurationInstance(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder([
            ProductConfigurationInstanceTransfer::PRICES => new \ArrayObject()
        ]))->build();

        $this->tester->getClient()->storeProductConfigurationInstanceBySku($productConcreteTransfer->getSku(), $productConfigurationInstanceTransfer);

        $itemTransfer = (new ItemBuilder())->build()->setSku($productConcreteTransfer->getSku());
        $persistentCartChangeTransfer = (new PersistentCartChangeTransfer())->addItem($itemTransfer);

        // Act
        $persistentCartChangeTransfer = $this->tester
            ->getClient()
            ->expandPersistentCartChangeWithProductConfigurationInstance($persistentCartChangeTransfer, []);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $persistentCartChangeTransfer->getItems()->offsetGet(0);

        $this->assertNotNull($itemTransfer->getProductConfigurationInstance());
        $this->assertEquals($productConfigurationInstanceTransfer, $itemTransfer->getProductConfigurationInstance());
    }

    /**
     * @return void
     */
    public function testExpandPersistentCartChangeWithProductConfigurationInstanceWithoutItems(): void
    {
        // Arrange
        $persistentCartChangeTransfer = new PersistentCartChangeTransfer();

        // Act
        $persistentCartChangeTransfer = $this->tester
            ->getClient()
            ->expandPersistentCartChangeWithProductConfigurationInstance($persistentCartChangeTransfer, []);

        // Assert
        $this->assertEmpty($persistentCartChangeTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testExpandPersistentCartChangeWithProductConfigurationInstanceExpandsItemWithInstanceFromStorage(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();

        $itemTransfer = (new ItemBuilder())->build()->setSku($productConcreteTransfer->getSku());
        $persistentCartChangeTransfer = (new PersistentCartChangeTransfer())->addItem($itemTransfer);

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods(['getStorageClient'])
            ->getMock();

        $productConfigurationStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getProductConfigurationStorageToStorageClientBridgeMock());

        // Act
        $persistentCartChangeTransfer = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->expandPersistentCartChangeWithProductConfigurationInstance($persistentCartChangeTransfer, []);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $persistentCartChangeTransfer->getItems()->offsetGet(0);

        $this->assertNotNull($itemTransfer->getProductConfigurationInstance());
    }

    /**
     * @return void
     */
    public function testExpandPersistentCartChangeWithProductConfigurationInstanceExpandsItemWithEmptyInstance(): void
    {
        // Arrange
        $this->tester->setupStorageRedisConfig();
        $productConcreteTransfer = $this->tester->haveProduct();

        $itemTransfer = (new ItemBuilder())->build()->setSku($productConcreteTransfer->getSku());
        $persistentCartChangeTransfer = (new PersistentCartChangeTransfer())->addItem($itemTransfer);

        // Act
        $persistentCartChangeTransfer = $this->tester
            ->getClient()
            ->expandPersistentCartChangeWithProductConfigurationInstance($persistentCartChangeTransfer, []);

        // Assert
        $this->assertNull($persistentCartChangeTransfer->getItems()->offsetGet(0)->getProductConfigurationInstance());
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
