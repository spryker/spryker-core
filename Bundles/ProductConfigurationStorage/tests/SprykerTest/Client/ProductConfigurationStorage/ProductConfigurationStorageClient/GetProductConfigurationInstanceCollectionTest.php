<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClient;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceCriteriaBuilder;
use Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceConditionsTransfer;
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
 * @group GetProductConfigurationInstanceCollectionTest
 * Add your own group annotations below this line
 */
class GetProductConfigurationInstanceCollectionTest extends Unit
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
     * @var int
     */
    protected const TEST_SKU_3 = 888;

    /**
     * @var \SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetProductConfigurationInstanceCollectionFromStorageWhileSkuCriteriaMatched(): void
    {
        // Arrange
        $skus = [static::TEST_SKU_1, static::TEST_SKU_2, static::TEST_SKU_3];
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock */
        $productConfigurationStorageFactoryMock = $this->getMockBuilder(ProductConfigurationStorageFactory::class)
            ->onlyMethods(['getStorageClient'])
            ->getMock();

        $productConfigurationStorageFactoryMock
            ->method('getStorageClient')
            ->willReturn($this->getProductConfigurationStorageToStorageClientBridgeMock());

        $productConfigurationInstanceCriteriaTransfer = (new ProductConfigurationInstanceCriteriaBuilder())
            ->withProductConfigurationInstanceConditions([
                ProductConfigurationInstanceConditionsTransfer::SKUS => $skus,
            ])->build();

        // Act
        $productConfigurationInstanceCollectionTransfer = $this->tester
            ->getClientMock($productConfigurationStorageFactoryMock)
            ->getProductConfigurationInstanceCollection($productConfigurationInstanceCriteriaTransfer);

        // Assert
        $this->assertSame(
            3,
            $productConfigurationInstanceCollectionTransfer->getProductConfigurationInstances()->count(),
            'Expects that product instance collection has two product configuration instances found from storage.',
        );
        $this->assertProductConfigurationInstanceTransfersIndexedBySku(
            $productConfigurationInstanceCollectionTransfer,
            $skus,
        );
    }

    /**
     * @return void
     */
    public function testGetProductConfigurationInstanceCollectionFromSessionAndStorageReturnsEmptyCollectionWhileNoCriteriaMatched(): void
    {
        // Arrange
        $skus = [static::TEST_SKU_1, static::TEST_SKU_2, static::TEST_SKU_3];
        $productConfigurationInstanceCriteriaTransfer = (new ProductConfigurationInstanceCriteriaBuilder())
            ->withProductConfigurationInstanceConditions([
                ProductConfigurationInstanceConditionsTransfer::SKUS => $skus,
            ])->build();

        // Act
        $productConfigurationInstanceCollectionTransfer = $this->tester
            ->getClient()
            ->getProductConfigurationInstanceCollection($productConfigurationInstanceCriteriaTransfer);

        // Assert
        $this->assertEmpty(
            $productConfigurationInstanceCollectionTransfer->getProductConfigurationInstances(),
            'Expects that product configuration instance collection is empty.',
        );
    }

    /**
     * @return void
     */
    public function testGetProductConfigurationInstanceCollectionReturnsEmptyCollectionWhileNoCriteriaSpecified(): void
    {
        // Arrange
        $productConfigurationInstanceCriteriaTransfer = (new ProductConfigurationInstanceCriteriaBuilder())->build();

        // Act
        $productConfigurationInstanceCollectionTransfer = $this->tester
            ->getClient()
            ->getProductConfigurationInstanceCollection($productConfigurationInstanceCriteriaTransfer);

        // Assert
        $this->assertEmpty(
            $productConfigurationInstanceCollectionTransfer->getProductConfigurationInstances(),
            'Expects that product configuration instance collection is empty.',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer $productConfigurationInstanceCollectionTransfer
     * @param array $skus
     *
     * @return void
     */
    protected function assertProductConfigurationInstanceTransfersIndexedBySku(
        ProductConfigurationInstanceCollectionTransfer $productConfigurationInstanceCollectionTransfer,
        array $skus
    ) {
        $productConfigurationInstanceTransfers = $productConfigurationInstanceCollectionTransfer->getProductConfigurationInstances();

        foreach ($productConfigurationInstanceTransfers as $sku => $productConfigurationInstanceTransfer) {
            $this->assertInstanceOf(
                ProductConfigurationInstanceTransfer::class,
                $productConfigurationInstanceTransfer,
                'Expects that product configuration instances will be found from session.',
            );
            $this->assertTrue(in_array($sku, $skus, true), 'Expects that collection will be indexed by SKU');
        }
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
                static::TEST_SKU_3 => (new ProductConfigurationInstanceBuilder())->build(),
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
                static::TEST_SKU_3 => json_encode(array_merge((new ProductConfigurationInstanceBuilder())->build()->toArray(), ['sku' => static::TEST_SKU_3])),
            ]);

        return $productConfigurationStorageToStorageClientBridgeMock;
    }
}
