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
 * @group IsProductHasProductConfigurationInstanceTest
 * Add your own group annotations below this line
 */
class IsProductHasProductConfigurationInstanceTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsProductHasProductConfigurationInstanceTakesInstanceFromSession(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder())->build();

        $this->tester->getClient()->storeProductConfigurationInstanceBySku($productConcreteTransfer->getSku(), $productConfigurationInstanceTransfer);

        $productViewTransfer = (new ProductViewTransfer())
            ->setIdProductConcrete($productConcreteTransfer->getIdProductConcrete())
            ->setSku($productConcreteTransfer->getSku());

        // Act
        $hasProductConfigurationInstance = $this->tester
            ->getClient()
            ->isProductHasProductConfigurationInstance($productViewTransfer);

        // Assert
        $this->assertTrue(
            $hasProductConfigurationInstance,
            'Expects that product will have product configuration instance in session.'
        );
    }

    /**
     * @return void
     */
    public function testIsProductHasProductConfigurationInstanceTakesInstanceFromStorage(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods(['getStorageClient'])
            ->getMock();

        $productConfigurationStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getProductConfigurationStorageToStorageClientBridgeMock());

        $productViewTransfer = (new ProductViewTransfer())
            ->setIdProductConcrete($productConcreteTransfer->getIdProductConcrete())
            ->setSku($productConcreteTransfer->getSku());

        // Act
        $hasProductConfigurationInstance = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->isProductHasProductConfigurationInstance($productViewTransfer);

        // Assert
        $this->assertTrue(
            $hasProductConfigurationInstance,
            'Expects that product will have product configuration instance in storage.'
        );
    }

    /**
     * @return void
     */
    public function testIsProductHasProductConfigurationInstanceWithoutInstance(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();

        $productViewTransfer = (new ProductViewTransfer())
            ->setIdProductConcrete($productConcreteTransfer->getIdProductConcrete())
            ->setSku($productConcreteTransfer->getSku());

        // Act
        $hasProductConfigurationInstance = $this->tester
            ->getClient()
            ->isProductHasProductConfigurationInstance($productViewTransfer);

        // Assert
        $this->assertFalse(
            $hasProductConfigurationInstance,
            'Expects that product wont have product configuration instance.'
        );
    }

    /**
     * @return void
     */
    public function testIsProductHasProductConfigurationInstanceTakesFromProductViewTransfer(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder())->build();

        $productViewTransfer = (new ProductViewTransfer())
            ->setIdProductConcrete($productConcreteTransfer->getIdProductConcrete())
            ->setSku($productConcreteTransfer->getSku())
            ->setProductConfigurationInstance($productConfigurationInstanceTransfer);

        // Act
        $hasProductConfigurationInstance = $this->tester
            ->getClient()
            ->isProductHasProductConfigurationInstance($productViewTransfer);

        // Assert
        $this->assertTrue(
            $hasProductConfigurationInstance,
            'Expects that product will have product configuration instance in product view transfer.'
        );
    }

    /**
     * @return void
     */
    public function testIsProductHasProductConfigurationInstanceForNonConcreteProduct(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder())->build();

        $productViewTransfer = (new ProductViewTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setProductConfigurationInstance($productConfigurationInstanceTransfer);

        // Act
        $hasProductConfigurationInstance = $this->tester
            ->getClient()
            ->isProductHasProductConfigurationInstance($productViewTransfer);

        // Assert
        $this->assertFalse(
            $hasProductConfigurationInstance,
            'Expects that product wont have product configuration instance when product is not concrete product.'
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
