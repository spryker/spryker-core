<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClient;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
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
 * @group ExpandCartChangeWithProductConfigurationInstanceTest
 * Add your own group annotations below this line
 */
class ExpandCartChangeWithProductConfigurationInstanceTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandCartChangeWithProductConfigurationInstanceExpandsItemWithProductConfigurationInstance(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder([
            ProductConfigurationInstanceTransfer::PRICES => new ArrayObject(),
        ]))->build();

        $this->tester->getClient()->storeProductConfigurationInstanceBySku($productConcreteTransfer->getSku(), $productConfigurationInstanceTransfer);

        $itemTransfer = (new ItemBuilder())->build()->setSku($productConcreteTransfer->getSku());
        $cartChangeTransfer = (new CartChangeTransfer())->addItem($itemTransfer);

        // Act
        $cartChangeTransfer = $this->tester
            ->getClient()
            ->expandCartChangeWithProductConfigurationInstance($cartChangeTransfer, []);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $cartChangeTransfer->getItems()->offsetGet(0);

        $this->assertNotNull(
            $itemTransfer->getProductConfigurationInstance(),
            'Expects that item will be expanded with product configuration instance.'
        );
        $this->assertEquals(
            $productConfigurationInstanceTransfer,
            $itemTransfer->getProductConfigurationInstance(),
            'Expects that item will be expanded with product configuration instance.'
        );
    }

    /**
     * @return void
     */
    public function testExpandCartChangeWithProductConfigurationInstanceWithoutItems(): void
    {
        // Arrange
        $cartChangeTransfer = new CartChangeTransfer();

        // Act
        $cartChangeTransfer = $this->tester
            ->getClient()
            ->expandCartChangeWithProductConfigurationInstance($cartChangeTransfer, []);

        // Assert
        $this->assertEmpty(
            $cartChangeTransfer->getItems(),
            'Expects no items in cart change transfer when call expander with empty cart change transfer.'
        );
    }

    /**
     * @return void
     */
    public function testExpandCartChangeWithProductConfigurationInstanceExpandsItemWithInstanceFromStorage(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();

        $itemTransfer = (new ItemBuilder())->build()->setSku($productConcreteTransfer->getSku());
        $cartChangeTransfer = (new CartChangeTransfer())->addItem($itemTransfer);

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods(['getStorageClient'])
            ->getMock();

        $productConfigurationStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getProductConfigurationStorageToStorageClientBridgeMock());

        // Act
        $cartChangeTransfer = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->expandCartChangeWithProductConfigurationInstance($cartChangeTransfer, []);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $cartChangeTransfer->getItems()->offsetGet(0);

        $this->assertNotNull(
            $itemTransfer->getProductConfigurationInstance(),
            'Expects item will be expanded with product configuration from storage.'
        );
    }

    /**
     * @return void
     */
    public function testExpandCartChangeWithProductConfigurationInstanceExpandsItemWithEmptyInstance(): void
    {
        // Arrange
        $this->tester->setupStorageRedisConfig();
        $productConcreteTransfer = $this->tester->haveProduct();

        $itemTransfer = (new ItemBuilder())->build()->setSku($productConcreteTransfer->getSku());
        $cartChangeTransfer = (new CartChangeTransfer())->addItem($itemTransfer);

        // Act
        $cartChangeTransfer = $this->tester
            ->getClient()
            ->expandCartChangeWithProductConfigurationInstance($cartChangeTransfer, []);

        // Assert
        $this->assertNull(
            $cartChangeTransfer->getItems()->offsetGet(0)->getProductConfigurationInstance(),
            'Expects item without product configuration when no product configuration.'
        );
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
