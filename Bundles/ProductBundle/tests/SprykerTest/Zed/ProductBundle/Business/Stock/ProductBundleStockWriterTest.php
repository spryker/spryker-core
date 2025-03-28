<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Stock;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockProduct;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandlerInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockWriter;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToStockQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Stock
 * @group ProductBundleStockWriterTest
 * Add your own group annotations below this line
 */
class ProductBundleStockWriterTest extends Unit
{
    /**
     * @var int
     */
    public const ID_STORE = 1;

    /**
     * @return void
     */
    public function testUpdateStockShouldCalculatedStockBasedOnBundledProducts(): void
    {
        $idProductBundle = 1;
        $bundleQuantity = new Decimal(2);
        $idRelatedProductId = 2;
        $relatedProductSku = 'sku-321';
        $relatedProductStock = new Decimal(15);

        $productBundleAvailabilityHandlerMock = $this->createProductBundleAvailabilityHandlerMock();
        $productBundleAvailabilityHandlerMock->expects($this->once())->method('updateBundleAvailability');

        $productStockWriteMock = $this->createProductStockWriterMock($productBundleAvailabilityHandlerMock);

        $this->setupFindProductBundleBySku($productStockWriteMock, new SpyProductBundle());
        $this->setupFindBundledItemsByIdBundleProduct($idProductBundle, $bundleQuantity->toString(), $idRelatedProductId, $relatedProductSku, $productStockWriteMock);
        $this->setupFindProductStock($productStockWriteMock, $relatedProductStock, $idRelatedProductId);
        $this->setupFindOrCreateProductStockEntity($productStockWriteMock);

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setSku($relatedProductSku);
        $productConcreteTransfer->setIdProductConcrete($idRelatedProductId);

        $updatedProductConcreteTransfer = $productStockWriteMock->updateStock($productConcreteTransfer);

        $stocks = $updatedProductConcreteTransfer->getStocks();

        $this->assertCount(2, $stocks);

        $stockTransfer = $stocks[0];
        $this->assertTrue($stockTransfer->getQuantity()->equals(7));

        $stockTransfer = $stocks[1];
        $this->assertTrue($stockTransfer->getQuantity()->equals(7));
    }

    /**
     * @return void
     */
    public function testUpdateStockShouldResetStockWhenThereIsNoBundleItems(): void
    {
        $idProductBundle = 1;
        $idRelatedProductId = 2;
        $relatedProductSku = 'sku-321';

        $productBundleAvailabilityHandlerMock = $this->createProductBundleAvailabilityHandlerMock();
        $productBundleAvailabilityHandlerMock->expects($this->once())->method('removeBundleAvailability');

        $productStockWriteMock = $this->createProductStockWriterMock($productBundleAvailabilityHandlerMock);

        $this->setupFindProductBundleBySku($productStockWriteMock);
        $this->setupFindProductStock($productStockWriteMock, new Decimal(0), $idRelatedProductId);

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setSku($relatedProductSku);
        $productConcreteTransfer->setIdProductConcrete($idProductBundle);

        $updatedProductConcreteTransfer = $productStockWriteMock->updateStock($productConcreteTransfer);
        $stocks = $updatedProductConcreteTransfer->getStocks();

        $this->assertCount(2, $stocks);

        $stockTransfer = $stocks[0];
        $this->assertTrue($stockTransfer->getQuantity()->isZero());

        $stockTransfer = $stocks[1];
        $this->assertTrue($stockTransfer->getQuantity()->isZero());
    }

    /**
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandlerInterface|null $productBundleAvailabilityMock
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface|null $productBundleQueryContainerMock
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToStockQueryContainerInterface|null $stockQueryContainerMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface|null $storeFacadeMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockWriter
     */
    protected function createProductStockWriterMock(
        ?ProductBundleAvailabilityHandlerInterface $productBundleAvailabilityMock = null,
        ?ProductBundleQueryContainerInterface $productBundleQueryContainerMock = null,
        ?ProductBundleToStockQueryContainerInterface $stockQueryContainerMock = null,
        ?ProductBundleToStoreFacadeInterface $storeFacadeMock = null
    ): ProductBundleStockWriter {
        if ($productBundleQueryContainerMock === null) {
            $productBundleQueryContainerMock = $this->createProductBundleQueryContainerMock();
        }

        if ($stockQueryContainerMock === null) {
            $stockQueryContainerMock = $this->createStockQueryContainerMock();
        }

        if ($productBundleAvailabilityMock === null) {
            $productBundleAvailabilityMock = $this->createProductBundleAvailabilityHandlerMock();
        }

        if ($storeFacadeMock === null) {
            $storeFacadeMock = $this->createStoreFacadeMock();
            $storeTransfer = (new StoreBuilder([
                StoreTransfer::ID_STORE => static::ID_STORE,
            ]))
                ->build();
            $storeFacadeMock->method('getCurrentStore')->willReturn($storeTransfer);
            $storeFacadeMock->method('getStoreByName')->willReturn($storeTransfer);
            $storeFacadeMock->method('getAllStores')->willReturn([$storeTransfer]);
        }

        $connectionMock = $this->getMockBuilder(ConnectionInterface::class)->getMock();

        $productBundleQueryContainerMock->method('getConnection')->willReturn($connectionMock);

        return $this->getMockBuilder(ProductBundleStockWriter::class)
            ->setConstructorArgs([$productBundleQueryContainerMock, $stockQueryContainerMock, $productBundleAvailabilityMock, $storeFacadeMock])
            ->onlyMethods(['findProductStocks', 'findOrCreateProductStockEntity', 'findBundledItemsByIdBundleProduct', 'findProductBundleBySku'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Stock\Persistence\SpyStockProduct
     */
    protected function createStockProductEntityMock(): SpyStockProduct
    {
        $stockProductEntityMock = $this->getMockBuilder(SpyStockProduct::class)
            ->onlyMethods(['save'])
            ->getMock();

        $stockProductEntityMock->method('save')->willReturn(1);

        return $stockProductEntityMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected function createProductBundleQueryContainerMock(): ProductBundleQueryContainerInterface
    {
        return $this->getMockBuilder(ProductBundleQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToStockQueryContainerInterface
     */
    protected function createStockQueryContainerMock(): ProductBundleToStockQueryContainerInterface
    {
        return $this->getMockBuilder(ProductBundleToStockQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandlerInterface
     */
    protected function createProductBundleAvailabilityHandlerMock(): ProductBundleAvailabilityHandlerInterface
    {
        return $this->getMockBuilder(ProductBundleAvailabilityHandlerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface
     */
    protected function createStoreFacadeMock(): ProductBundleToStoreFacadeInterface
    {
        return $this->getMockBuilder(ProductBundleToStoreFacadeInterface::class)->getMock();
    }

    /**
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockWriter|\PHPUnit\Framework\MockObject\MockObject $productStockWriteMock
     * @param \Orm\Zed\ProductBundle\Persistence\SpyProductBundle|null $productBundleEntity
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockWriter
     */
    protected function setupFindProductBundleBySku(
        ProductBundleStockWriter $productStockWriteMock,
        ?SpyProductBundle $productBundleEntity = null
    ): ProductBundleStockWriter {
        $productStockWriteMock->method('findProductBundleBySku')->willReturn($productBundleEntity);

        return $productStockWriteMock;
    }

    /**
     * @param int $idProductBundle
     * @param string $bundleQuantity
     * @param int $idRelatedProductId
     * @param string $relatedProductSku
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockWriter|\PHPUnit\Framework\MockObject\MockObject $productStockWriteMock
     *
     * @return void
     */
    protected function setupFindBundledItemsByIdBundleProduct(
        int $idProductBundle,
        string $bundleQuantity,
        int $idRelatedProductId,
        string $relatedProductSku,
        ProductBundleStockWriter $productStockWriteMock
    ): void {
        $productBundleEntity = new SpyProductBundle();
        $productBundleEntity->setIdProductBundle($idProductBundle);
        $productBundleEntity->setQuantity($bundleQuantity);

        $productEntity = new SpyProduct();
        $productEntity->setIdProduct($idRelatedProductId);
        $productEntity->setSku($relatedProductSku);

        $productBundleEntity->setSpyProductRelatedByFkBundledProduct($productEntity);
        $productBundleEntity->setFkBundledProduct($idRelatedProductId);

        $bundledProducts = new ObjectCollection();
        $bundledProducts->append($productBundleEntity);

        $productStockWriteMock->method('findBundledItemsByIdBundleProduct')->willReturn($bundledProducts);
    }

    /**
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockWriter|\PHPUnit\Framework\MockObject\MockObject $productStockWriteMock
     * @param \Spryker\DecimalObject\Decimal $stock
     * @param int $idRelatedProductId
     *
     * @return void
     */
    protected function setupFindProductStock(
        ProductBundleStockWriter $productStockWriteMock,
        Decimal $stock,
        int $idRelatedProductId
    ): void {
        $stockProducts = new ObjectCollection();

        $stockProductEntity = $this->createStockProductEntityMock();
        $stockProductEntity->setQuantity($stock);
        $stockProductEntity->setFkProduct($idRelatedProductId);
        $stockProductEntity->setFkStock(1);

        $stockProducts->append($stockProductEntity);

        $stockProductEntity = $this->createStockProductEntityMock();
        $stockProductEntity->setQuantity($stock);
        $stockProductEntity->setFkProduct($idRelatedProductId);
        $stockProductEntity->setFkStock(2);

        $stockProducts->append($stockProductEntity);

        $productStockWriteMock->method('findProductStocks')->willReturn($stockProducts);
    }

    /**
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockWriter|\PHPUnit\Framework\MockObject\MockObject $productStockWriteMock
     *
     * @return void
     */
    protected function setupFindOrCreateProductStockEntity(ProductBundleStockWriter $productStockWriteMock): void
    {
        $stockProductEntityMock = $this->createStockProductEntityMock();

        $stockEntity = new SpyStock();
        $stockEntity->setName('Test stock');
        $stockProductEntityMock->setStock($stockEntity);

        $productStockWriteMock->method('findOrCreateProductStockEntity')->willReturn($stockProductEntityMock);
    }
}
