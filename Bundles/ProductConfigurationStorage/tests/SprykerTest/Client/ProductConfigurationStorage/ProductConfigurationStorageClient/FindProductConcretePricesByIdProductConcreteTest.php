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
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

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
        $this->assertCount(
            2,
            $priceProductTransfers,
            'Expects that product concrete price will be found with product configuration instance in storage.'
        );
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
        $this->assertEmpty(
            $priceProductTransfers,
            'Expects that product concrete price wont be found when product configuration instance has empty prices.'
        );
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
        $this->assertEmpty(
            $priceProductTransfers,
            'Expects that product concrete price wont be found when no product configuration instance in storage.'
        );
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
        $this->assertEmpty(
            $priceProductTransfers,
            'Expects that product concrete price wont be found when no stored product concrete was found.'
        );
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
