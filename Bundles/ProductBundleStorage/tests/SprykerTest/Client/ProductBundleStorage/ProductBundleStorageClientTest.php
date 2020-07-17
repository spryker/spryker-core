<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductBundleStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductBundleStorageTransfer;
use Generated\Shared\Transfer\ProductForBundleStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToProductStorageClientInterface;
use Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToStorageClientInterface;
use Spryker\Client\ProductBundleStorage\ProductBundleStorageClient;
use Spryker\Client\ProductBundleStorage\ProductBundleStorageClientInterface;
use Spryker\Client\ProductBundleStorage\ProductBundleStorageDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductBundleStorage
 * @group ProductBundleStorageClientTest
 * Add your own group annotations below this line
 */
class ProductBundleStorageClientTest extends Unit
{
    /**
     * @var string
     */
    protected const LOCALE = 'de_DE';

    /**
     * @var \SprykerTest\Client\ProductBundleStorage\ProductBundleStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->mockProductStorageClient();
    }

    /**
     * @return void
     */
    public function testExpandProductViewWithBundledProductsEnsureThatQuantityWasAdded(): void
    {
        // Arrange
        $concreteProductTransfer = $this->tester->haveProductBundle();
        $productForBundleTransfers = $concreteProductTransfer->getProductBundle()->getBundledProducts();

        $productViewTransfer = (new ProductViewTransfer())->setIdProductConcrete($concreteProductTransfer->getIdProductConcrete());

        $this->mockStorageClient([
            ProductBundleStorageTransfer::ID_PRODUCT_CONCRETE_BUNDLE => $concreteProductTransfer->getIdProductConcrete(),
            ProductBundleStorageTransfer::BUNDLED_PRODUCTS => [
                [
                    ProductForBundleStorageTransfer::ID_PRODUCT_CONCRETE => $productForBundleTransfers->offsetGet(0)->getIdProductConcrete(),
                    ProductForBundleStorageTransfer::QUANTITY => $productForBundleTransfers->offsetGet(0)->getQuantity(),
                ],
                [
                    ProductForBundleStorageTransfer::ID_PRODUCT_CONCRETE => $productForBundleTransfers->offsetGet(1)->getIdProductConcrete(),
                    ProductForBundleStorageTransfer::QUANTITY => $productForBundleTransfers->offsetGet(1)->getQuantity(),
                ],
                [
                    ProductForBundleStorageTransfer::ID_PRODUCT_CONCRETE => $productForBundleTransfers->offsetGet(2)->getIdProductConcrete(),
                    ProductForBundleStorageTransfer::QUANTITY => $productForBundleTransfers->offsetGet(2)->getQuantity(),
                ],
            ],
        ]);

        // Act
        $expandedProductViewTransfer = $this->createProductBundleStorageClient()
            ->expandProductViewWithBundledProducts($productViewTransfer, static::LOCALE);

        // Assert
        $this->assertNotEmpty($expandedProductViewTransfer->getBundledProducts());
        $this->assertSame(
            $productForBundleTransfers->offsetGet(0)->getQuantity(),
            $expandedProductViewTransfer->getBundledProducts()->offsetGet(0)->getQuantity()
        );
    }

    /**
     * @return void
     */
    public function testExpandProductViewWithBundledProductsWithoutBundledProducts(): void
    {
        // Arrange
        $concreteProductTransfer = $this->tester->haveProductBundle();
        $productViewTransfer = (new ProductViewTransfer())->setIdProductConcrete($concreteProductTransfer->getIdProductConcrete());

        $this->mockStorageClient();

        // Act
        $expandedProductViewTransfer = $this->createProductBundleStorageClient()
            ->expandProductViewWithBundledProducts($productViewTransfer, static::LOCALE);

        // Assert
        $this->assertEmpty($expandedProductViewTransfer->getBundledProducts());
    }

    /**
     * @param array|null $productBundleStorageTransferData
     *
     * @return void
     */
    protected function mockStorageClient(?array $productBundleStorageTransferData = null): void
    {
        $storageClientMock = $this->getMockBuilder(ProductBundleStorageToStorageClientInterface::class)->getMock();
        $storageClientMock->method('get')->willReturn($productBundleStorageTransferData);

        $this->tester->setDependency(ProductBundleStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);
    }

    /**
     * @return void
     */
    protected function mockProductStorageClient(): void
    {
        $productStorageClientMock = $this->getMockBuilder(ProductBundleStorageToProductStorageClientInterface::class)->getMock();
        $productStorageClientMock->method('getProductConcreteViewTransfers')
            ->willReturnCallback(function (array $productConcreteIds) {
                $productViewTransfers = [];

                foreach ($productConcreteIds as $productConcreteId) {
                    $productViewTransfers[] = (new ProductViewTransfer())->setIdProductConcrete($productConcreteId);
                }

                return $productViewTransfers;
            });

        $this->tester->setDependency(ProductBundleStorageDependencyProvider::CLIENT_PRODUCT_STORAGE, $productStorageClientMock);
    }

    /**
     * @return \Spryker\Client\ProductBundleStorage\ProductBundleStorageClientInterface
     */
    protected function createProductBundleStorageClient(): ProductBundleStorageClientInterface
    {
        return new ProductBundleStorageClient();
    }
}
