<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductStorage;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductAbstractStorageBuilder;
use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface;
use Spryker\Client\ProductStorage\ProductStorageDependencyProvider;
use Spryker\Client\ProductStorage\ProductStorageFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductStorage
 * @group ProductStorageClientTest
 * Add your own group annotations below this line
 */
class ProductStorageClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductStorage\ProductStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetBulkProductAbstractStorageDataByProductAbstractIdsAndLocaleNameReturnsCorrectData(): void
    {
        // Arrange
        $productAbstractStorageTransfer = $this->getProductAbstractStorage();
        $idProductAbstract = $productAbstractStorageTransfer->getIdProductAbstract();
        $localeName = 'DE';

        $this->getStorageClientMock()
            ->expects($this->once())
            ->method('getMulti')
            ->willReturn([
                json_encode($productAbstractStorageTransfer->toArray()),
            ]);

        // Act
        $productAbstractStorageData = $this->tester
            ->getProductStorageClient()
            ->getBulkProductAbstractStorageDataByProductAbstractIdsAndLocaleName([$idProductAbstract], $localeName);

        // Assert
        $this->assertCount(1, $productAbstractStorageData);
        $this->assertArrayHasKey($idProductAbstract, $productAbstractStorageData);
        $this->assertSame($productAbstractStorageTransfer->toArray(), $productAbstractStorageData[$idProductAbstract]);
    }

    /**
     * @return void
     */
    public function testExpandProductVariantDataWillNotSetFalseToNull(): void
    {
        // Arrange
        $this->getStorageClientMock()
            ->expects($this->once())
            ->method('get')
            ->willReturn(['available' => false]);

        // Act
        $productViewTransfer = $this->tester
            ->getProductStorageClient()
            ->expandProductVariantData($this->tester->createProductViewTransfer(), 'DE');

        // Assert
        $this->assertFalse($productViewTransfer->getAvailable());

    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface
     */
    protected function getStorageClientMock(): ProductStorageToStorageClientInterface
    {
        $storageClientMock = $this->getMockBuilder(ProductStorageToStorageClientInterface::class)->getMock();
        $this->tester->setDependency(
            ProductStorageDependencyProvider::CLIENT_STORAGE,
            $storageClientMock,
            ProductStorageFactory::class
        );

        return $storageClientMock;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    protected function getProductAbstractStorage(array $seedData = []): ProductAbstractStorageTransfer
    {
        return (new ProductAbstractStorageBuilder($seedData))->build();
    }
}
