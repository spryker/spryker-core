<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClient;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
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
     * @var string
     */
    protected const TEST_LOCALE_NAME = 'test_locale_name';

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
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder([
            ProductConfigurationInstanceTransfer::PRICES => new ArrayObject(),
        ]))->build();

        $this->tester->getClient()->storeProductConfigurationInstanceBySku($productConcreteTransfer->getSku(), $productConfigurationInstanceTransfer);

        $productViewTransfer = (new ProductViewTransfer())
            ->setIdProductConcrete($productConcreteTransfer->getIdProductConcrete())
            ->setSku($productConcreteTransfer->getSku());

        // Act
        $productViewTransfer = $this->tester
            ->getClient()
            ->expandProductViewWithProductConfigurationInstance($productViewTransfer, [], static::TEST_LOCALE_NAME);

        // Assert
        $this->assertNotNull(
            $productViewTransfer->getProductConfigurationInstance(),
            'Expects that product view will be expanded with product configuration.',
        );
        $this->assertEquals(
            $productConfigurationInstanceTransfer,
            $productViewTransfer->getProductConfigurationInstance(),
            'Expects that product view will be expanded with same product configuration.',
        );
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
            ->expandProductViewWithProductConfigurationInstance($productViewTransfer, [], static::TEST_LOCALE_NAME);

        // Assert
        $this->assertNull(
            $productViewTransfer->getProductConfigurationInstance(),
            'Expects that product view will not be expanded with product configuration when no product configuration.',
        );
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
            ->expandProductViewWithProductConfigurationInstance($productViewTransfer, [], static::TEST_LOCALE_NAME);

        // Assert
        $this->assertNull(
            $productViewTransfer->getProductConfigurationInstance(),
            'Expects that product view will not be expanded with product configuration for non create product.',
        );
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
            ->expandProductViewWithProductConfigurationInstance($productViewTransfer, [], static::TEST_LOCALE_NAME);

        // Assert
        $this->assertNotNull(
            $productViewTransfer->getProductConfigurationInstance(),
            'Expects that product view will be expanded with product configuration from storage.',
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
