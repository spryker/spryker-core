<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductResourceAliasStorage;

use Codeception\Test\Unit;
use Spryker\Client\ProductResourceAliasStorage\Dependency\Client\ProductResourceAliasStorageToStorageClientInterface;
use Spryker\Client\ProductResourceAliasStorage\Dependency\Service\ProductResourceAliasStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductResourceAliasStorage\ProductResourceAliasStorageClientInterface;
use Spryker\Client\ProductResourceAliasStorage\ProductResourceAliasStorageDependencyProvider;
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductResourceAliasStorage
 * @group ProductResourceAliasStorageClientTest
 * Add your own group annotations below this line
 */
class ProductResourceAliasStorageClientTest extends Unit
{
    /**
     * @var string
     */
    protected const GENERATED_KEY = 'generated-key';

    /**
     * @var string
     */
    protected const SKU = 'test-sku';

    /**
     * @var string
     */
    protected const LOCALE = 'en_US';

    /**
     * @var \SprykerTest\Client\ProductResourceAliasStorage\ProductResourceAliasStorageClientTester
     */
    protected ProductResourceAliasStorageClientTester $tester;

    /**
     * @var \Spryker\Client\ProductResourceAliasStorage\ProductResourceAliasStorageClientInterface
     */
    protected ProductResourceAliasStorageClientInterface $productResourceAliasStorageClient;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->productResourceAliasStorageClient = $this->tester->getLocator()->productResourceAliasStorage()->client();
        $this->mockSynchronizationService();
    }

    /**
     * @return void
     */
    public function testGetProductConcreteStorageDataBySkuReturnsProductConcreteStorageDataWhenSkuAndLocaleExist(): void
    {
        // Arrange
        $storageClientMock = $this->createMock(ProductResourceAliasStorageToStorageClientInterface::class);
        $mappingResource = ['id' => 123];
        $productConcreteStorageData = ['data' => 'product-data'];
        $storageClientMock->method('get')
            ->withConsecutive([static::GENERATED_KEY], [static::GENERATED_KEY])
            ->willReturnOnConsecutiveCalls($mappingResource, $productConcreteStorageData);
        $this->tester->setDependency(ProductResourceAliasStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        // Act
        $result = $this->productResourceAliasStorageClient->getProductConcreteStorageDataBySku(static::SKU, static::LOCALE);

        // Assert
        $this->assertEquals($productConcreteStorageData, $result);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteStorageDataBySkuReturnsNullWhenMappingResourceIsNotFound(): void
    {
        // Arrange
        $storageClientMock = $this->createMock(ProductResourceAliasStorageToStorageClientInterface::class);
        $storageClientMock->method('get')
            ->with(static::GENERATED_KEY)
            ->willReturn(null);
        $this->tester->setDependency(ProductResourceAliasStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        // Act
        $result = $this->productResourceAliasStorageClient->getProductConcreteStorageDataBySku(static::SKU, static::LOCALE);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteStorageDataBySkuReturnsNullWhenProductConcreteIdIsMissingInMappingResource(): void
    {
        // Arrange
        $storageClientMock = $this->createMock(ProductResourceAliasStorageToStorageClientInterface::class);
        $mappingResource = [];
        $storageClientMock->method('get')
            ->with(static::GENERATED_KEY)
            ->willReturn($mappingResource);
        $this->tester->setDependency(ProductResourceAliasStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        // Act
        $result = $this->productResourceAliasStorageClient->getProductConcreteStorageDataBySku(static::SKU, static::LOCALE);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @return void
     */
    protected function mockSynchronizationService(): void
    {
        $synchronizationServiceMock = $this->createMock(ProductResourceAliasStorageToSynchronizationServiceInterface::class);
        $synchronizationKeyGeneratorPluginMock = $this->createMock(SynchronizationKeyGeneratorPluginInterface::class);
        $synchronizationKeyGeneratorPluginMock->method('generateKey')
            ->willReturn(static::GENERATED_KEY);
        $synchronizationServiceMock->method('getStorageKeyBuilder')
            ->willReturn($synchronizationKeyGeneratorPluginMock);
        $this->tester->setDependency(ProductResourceAliasStorageDependencyProvider::SERVICE_SYNCHRONIZATION, $synchronizationServiceMock);
    }
}
