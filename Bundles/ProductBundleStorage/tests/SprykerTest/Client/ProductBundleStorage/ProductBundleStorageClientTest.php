<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\PriceProductOfferVolume;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductBundleStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToProductStorageClientBridge;
use Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToProductStorageClientInterface;
use Spryker\Client\ProductBundleStorage\ProductBundleStorageFactory;
use Spryker\Client\ProductBundleStorage\Reader\ProductBundleStorageReaderInterface;
use SprykerTest\Client\ProductBundleStorage\ProductBundleStorageClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group PriceProductOfferVolume
 * @group ProductBundleStorageClientTest
 * Add your own group annotations below this line
 */
class ProductBundleStorageClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductBundleStorage\ProductBundleStorageClientTester
     */
    protected ProductBundleStorageClientTester $tester;

    /**
     * @var string
     */
    protected const TEST_KEY_PRODUCT_BUNDLE = 'kv:product_bundle:295';

    /**
     * @var string
     */
    protected const LOCALE_NAME = 'DE';

    /**
     * @return void
     */
    public function testExpandProductViewTransferWithBundledProductsExpandsWithBundledProducts(): void
    {
        // Arrange
        $productViewTransfer = $this->tester->createProductViewTransfer();
        $productBundleStorageTransfer = $this->tester->createProductBundleStorageTransfer();
        $productBundleStorageFactoryMock = $this->createAndConfigureProductBundleStorageFactoryMock($productBundleStorageTransfer, $productViewTransfer);

        // Act
        $expandedProductViewTransfer = $this->tester->getClientMock($productBundleStorageFactoryMock)->expandProductViewTransferWithBundledProducts($productViewTransfer, [], static::LOCALE_NAME);
        $expandedBundledProductTransfers = $expandedProductViewTransfer->getBundledProducts();
        $expandedBundledProductImageTransfers = $expandedBundledProductTransfers->getIterator()->current()->getProductImages();
        $productViewImageTransfers = $productViewTransfer->getImages();

        // Assert
        $this->assertSame($productViewTransfer->getName(), $expandedBundledProductTransfers->getIterator()->current()->getName());
        $this->assertSame($productViewImageTransfers->getIterator()->current()->getExternalUrlSmall(), $expandedBundledProductImageTransfers->getIterator()->current()->getExternalUrlSmall());
        $this->assertSame($productViewTransfer->getUrl(), $expandedBundledProductTransfers->getIterator()->current()->getUrl());
    }

    /**
     * @return void
     */
    public function testExpandProductViewTransferWithBundledProductsDoesNothingWhenIdProductConcreteIsNotProvidedAndEmptyProductBundlesInStorage(): void
    {
        // Arrange
        $emptyProductBundleStorageTransfer = new ProductBundleStorageTransfer();
        $productViewTransfer = $this->tester->createProductViewTransfer()
            ->setIdProductConcrete(null);
        $productBundleStorageFactoryMock = $this->createAndConfigureProductBundleStorageFactoryMock($emptyProductBundleStorageTransfer, $productViewTransfer);

        // Act
        $expandedProductViewTransfer = $this->tester->getClientMock($productBundleStorageFactoryMock)->expandProductViewTransferWithBundledProducts($productViewTransfer, [], static::LOCALE_NAME);

        // Assert
        $this->assertCount(0, $expandedProductViewTransfer->getBundledProducts());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductBundleStorageTransfer $productBundleStorageTransfer
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductBundleStorage\ProductBundleStorageFactory
     */
    protected function createAndConfigureProductBundleStorageFactoryMock(
        ProductBundleStorageTransfer $productBundleStorageTransfer,
        ProductViewTransfer $productViewTransfer
    ): ProductBundleStorageFactory {
        $productBundleStorageFactoryMock = $this->getMockBuilder(ProductBundleStorageFactory::class)
            ->onlyMethods(['getStorageClient', 'getProductStorageClient', 'createProductBundleStorageReader'])
            ->getMock();

        $productBundleStorageFactoryMock
            ->method('createProductBundleStorageReader')
            ->willReturn($this->getProductBundleStorageReaderMock([$productBundleStorageTransfer]));

        $productBundleStorageFactoryMock
            ->method('getProductStorageClient')
            ->willReturn($this->getProductStorageClientMock([$productViewTransfer]));

        return $productBundleStorageFactoryMock;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductBundleStorageTransfer> $productBundleStorageTransfers
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductBundleStorage\Reader\ProductBundleStorageReaderInterface
     */
    protected function getProductBundleStorageReaderMock(array $productBundleStorageTransfers): ProductBundleStorageReaderInterface
    {
        $storageClientMock = $this->getMockBuilder(ProductBundleStorageReaderInterface::class)
            ->onlyMethods(['getProductBundles'])
            ->disableOriginalConstructor()
            ->getMock();

        $storageClientMock
            ->method('getProductBundles')
            ->willReturn($productBundleStorageTransfers);

        return $storageClientMock;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductViewTransfer> $productViewTransfers
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageInterface
     */
    protected function getProductStorageClientMock(array $productViewTransfers): ProductBundleStorageToProductStorageClientInterface
    {
        $storageClientMock = $this->getMockBuilder(ProductBundleStorageToProductStorageClientBridge::class)
            ->onlyMethods(['getProductConcreteViewTransfers'])
            ->disableOriginalConstructor()
            ->getMock();

        $storageClientMock
            ->method('getProductConcreteViewTransfers')
            ->willReturn($productViewTransfers);

        return $storageClientMock;
    }
}
