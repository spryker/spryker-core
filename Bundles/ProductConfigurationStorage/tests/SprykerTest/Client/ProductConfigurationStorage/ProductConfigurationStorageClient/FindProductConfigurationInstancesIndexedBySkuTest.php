<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClient;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Client\ProductConfigurationStorage\Builder\ProductConfigurationSessionKeyBuilder;
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
 * @group FindProductConfigurationInstancesIndexedBySkuTest
 * Add your own group annotations below this line
 */
class FindProductConfigurationInstancesIndexedBySkuTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SKU_1 = 'test-sku-1';

    /**
     * @var string
     */
    protected const TEST_SKU_2 = 'test-sku-2';

    /**
     * @var \SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindProductConfigurationInstancesIndexedBySkuFromSession(): void
    {
        // Arrange
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods(['getSessionClient', 'createProductConfigurationSessionKeyBuilder'])
            ->getMock();

        $productConfigurationStorageFactoryMock
            ->method('getSessionClient')
            ->willReturn($this->getProductConfigurationStorageToSessionClientBridgeMock());

        $productConfigurationStorageFactoryMock
            ->method('createProductConfigurationSessionKeyBuilder')
            ->willReturn($this->getProductConfigurationSessionKeyBuilder());

        $skus = [static::TEST_SKU_1, static::TEST_SKU_2];

        // Act
        $productConfigurationInstanceTransfers = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->findProductConfigurationInstancesIndexedBySku($skus);

        // Assert
        $this->assertNotNull(
            $productConfigurationInstanceTransfers,
            'Expects that product configuration instances will be found from session.'
        );
        $this->assertIsArray(
            $productConfigurationInstanceTransfers,
            'Expects that product configuration instances will be found from session.'
        );

        $this->assertEquals(2, count($productConfigurationInstanceTransfers));
        foreach ($productConfigurationInstanceTransfers as $transfer) {
            $this->assertInstanceOf(
                ProductConfigurationInstanceTransfer::class,
                $transfer,
                'Expects that product configuration instances will be found from session.'
            );
        }
    }

    /**
     * @return void
     */
    public function testFindProductConfigurationInstancesIndexedBySkuFromStorage(): void
    {
        // Arrange
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods(['getStorageClient'])
            ->getMock();

        $productConfigurationStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getProductConfigurationStorageToStorageClientBridgeMock());
        $skus = [static::TEST_SKU_1, static::TEST_SKU_2];

        // Act
        $productConfigurationInstanceTransfers = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->findProductConfigurationInstancesIndexedBySku($skus);

        // Assert
        $this->assertNotNull(
            $productConfigurationInstanceTransfers,
            'Expects that product configuration instances will be found from session.'
        );
        $this->assertIsArray(
            $productConfigurationInstanceTransfers,
            'Expects that product configuration instances will be found from session.'
        );

        $this->assertEquals(2, count($productConfigurationInstanceTransfers));
        foreach ($productConfigurationInstanceTransfers as $transfer) {
            $this->assertInstanceOf(
                ProductConfigurationInstanceTransfer::class,
                $transfer,
                'Expects that product configuration instances will be found from session.'
            );
        }
    }

    /**
     * @return void
     */
    public function testFindProductConfigurationInstancesIndexedBySkuFromSessionAndStorageClientsReturnsEmpty(): void
    {
        // Arrange
        $skus = [static::TEST_SKU_1, static::TEST_SKU_2];

        // Act
        $productConfigurationInstanceTransfers = $this->tester
            ->getClient()
            ->findProductConfigurationInstancesIndexedBySku($skus);

        // Assert
        $this->assertEmpty(
            $productConfigurationInstanceTransfers,
            'Expects that product configuration instances wont be found.'
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientInterface
     */
    protected function getProductConfigurationStorageToSessionClientBridgeMock(): ProductConfigurationStorageToSessionClientInterface
    {
        $productConfigurationStorageToSessionClientBridgeMock = $this->getMockBuilder(ProductConfigurationStorageToSessionClientBridge::class)
            ->onlyMethods(['all'])
            ->disableOriginalConstructor()
            ->getMock();

        $productConfigurationStorageToSessionClientBridgeMock
            ->method('all')
            ->willReturn([
                static::TEST_SKU_1 => (new ProductConfigurationInstanceBuilder())->build(),
                static::TEST_SKU_2 => (new ProductConfigurationInstanceBuilder())->build(),
            ]);

        return $productConfigurationStorageToSessionClientBridgeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\Builder\ProductConfigurationSessionKeyBuilder
     */
    protected function getProductConfigurationSessionKeyBuilder(): ProductConfigurationSessionKeyBuilder
    {
        $productConfigurationSessionKeyBuilder = $this->getMockBuilder(ProductConfigurationSessionKeyBuilder::class)
            ->onlyMethods(['getProductConfigurationSessionKey'])
            ->disableOriginalConstructor()
            ->getMock();
        $productConfigurationSessionKeyBuilder
            ->method('getProductConfigurationSessionKey')
            ->willReturnCallback(function ($key) {
                return $key;
            });

        return $productConfigurationSessionKeyBuilder;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToStorageClientInterface
     */
    protected function getProductConfigurationStorageToStorageClientBridgeMock(): ProductConfigurationStorageToStorageClientInterface
    {
        $productConfigurationStorageToStorageClientBridgeMock = $this->getMockBuilder(ProductConfigurationStorageToStorageClientBridge::class)
            ->onlyMethods(['getMulti'])
            ->disableOriginalConstructor()
            ->getMock();

        $productConfigurationStorageToStorageClientBridgeMock
            ->method('getMulti')
            ->willReturn([
                static::TEST_SKU_1 => json_encode(array_merge((new ProductConfigurationInstanceBuilder())->build()->toArray(), ['sku' => static::TEST_SKU_1])),
                static::TEST_SKU_2 => json_encode(array_merge((new ProductConfigurationInstanceBuilder())->build()->toArray(), ['sku' => static::TEST_SKU_2])),
            ]);

        return $productConfigurationStorageToStorageClientBridgeMock;
    }
}
