<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClient;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToProductStorageClientBridge;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToProductStorageClientInterface;
use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Shared\Storage\StorageConstants;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationStorage
 * @group ProductConfigurationStorageClient
 * @group FindProductConcretePricesByIdProductConcreteTest
 * Add your own group annotations below this line
 */
class FindProductConcretePricesByIdProductConcreteTest extends Unit
{
    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_PROTOCOL
     */
    protected const REDIS_PROTOCOL = 'STORAGE_REDIS:STORAGE_REDIS_PROTOCOL';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_HOST
     */
    protected const REDIS_HOST = 'STORAGE_REDIS:STORAGE_REDIS_HOST';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_PORT
     */
    protected const REDIS_PORT = 'STORAGE_REDIS:STORAGE_REDIS_PORT';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_DATABASE
     */
    protected const REDIS_DATABASE = 'STORAGE_REDIS:STORAGE_REDIS_DATABASE';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_PASSWORD
     */
    protected const REDIS_PASSWORD = 'STORAGE_REDIS:STORAGE_REDIS_PASSWORD';

    /**
     * @var \SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setupStorageRedisConfig();
    }

    /**
     * @return void
     */
    public function testFindProductConcretePricesByIdProductConcreteWithStoredInstanceInStorage(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder())->build()
            ->addPrice(new PriceProductTransfer())
            ->addPrice(new PriceProductTransfer());

        $this->tester->getClient()->storeProductConfigurationInstanceBySku($productConcreteTransfer->getSku(), $productConfigurationInstanceTransfer);

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods(['getProductStorageClient'])
            ->getMock();

        $productConfigurationStorageFactoryMock
            ->method('getProductStorageClient')
            ->willReturn($this->getProductConfigurationStorageToProductStorageClientBridgeMock($productConcreteTransfer));

        // Act
        $priceProductTransfers = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->findProductConcretePricesByIdProductConcrete($productConcreteTransfer->getIdProductConcrete());

        // Assert
        $this->assertEquals($productConfigurationInstanceTransfer->getPrices()->getArrayCopy(), $priceProductTransfers);
    }

    /**
     * @return void
     */
    public function testFindProductConcretePricesByIdProductConcreteWithEmptyInstancePrices(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder())->build()
            ->setPrices(new ArrayObject());

        $this->tester->getClient()->storeProductConfigurationInstanceBySku($productConcreteTransfer->getSku(), $productConfigurationInstanceTransfer);

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods(['getProductStorageClient'])
            ->getMock();

        $productConfigurationStorageFactoryMock
            ->method('getProductStorageClient')
            ->willReturn($this->getProductConfigurationStorageToProductStorageClientBridgeMock($productConcreteTransfer));

        // Act
        $priceProductTransfers = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->findProductConcretePricesByIdProductConcrete($productConcreteTransfer->getIdProductConcrete());

        // Assert
        $this->assertEmpty($priceProductTransfers);
    }

    /**
     * @return void
     */
    public function testFindProductConcretePricesByIdProductConcreteWithoutStoredInstanceInStorage(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods(['getProductStorageClient'])
            ->getMock();

        $productConfigurationStorageFactoryMock
            ->method('getProductStorageClient')
            ->willReturn($this->getProductConfigurationStorageToProductStorageClientBridgeMock($productConcreteTransfer));

        // Act
        $priceProductTransfers = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->findProductConcretePricesByIdProductConcrete($productConcreteTransfer->getIdProductConcrete());

        // Assert
        $this->assertEmpty($priceProductTransfers);
    }

    /**
     * @return void
     */
    public function testFindProductConcretePricesByIdProductConcreteWithoutStoredSkuInProductConcrete(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct()->setSku(null);

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods(['getProductStorageClient'])
            ->getMock();

        $productConfigurationStorageFactoryMock
            ->method('getProductStorageClient')
            ->willReturn($this->getProductConfigurationStorageToProductStorageClientBridgeMock($productConcreteTransfer));

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->findProductConcretePricesByIdProductConcrete($productConcreteTransfer->getIdProductConcrete());
    }

    /**
     * @return void
     */
    public function testFindProductConcretePricesByIdProductConcreteWithoutStoredConcreteProduct(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();

        // Act
        $priceProductTransfers = $this->tester
            ->getClient()
            ->findProductConcretePricesByIdProductConcrete($productConcreteTransfer->getIdProductConcrete());

        // Assert
        $this->assertEmpty($priceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToProductStorageClientInterface
     */
    protected function getProductConfigurationStorageToProductStorageClientBridgeMock(
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConfigurationStorageToProductStorageClientInterface {
        $productConfigurationStorageToProductStorageClientBridgeMock = $this->getMockBuilder(ProductConfigurationStorageToProductStorageClientBridge::class)
            ->onlyMethods(['findProductConcreteStorageData'])
            ->disableOriginalConstructor()
            ->getMock();

        $productConfigurationStorageToProductStorageClientBridgeMock
            ->method('findProductConcreteStorageData')
            ->willReturn($productConcreteTransfer->toArray());

        return $productConfigurationStorageToProductStorageClientBridgeMock;
    }
}
