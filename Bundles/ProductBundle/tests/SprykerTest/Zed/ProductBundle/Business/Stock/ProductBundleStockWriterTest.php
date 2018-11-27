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
use PHPUnit_Framework_MockObject_MockObject;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandlerInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockWriter;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToStockQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

/**
 * Auto-generated group annotations
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
    public const ID_STORE = 1;

    /**
     * @return void
     */
    public function testUpdateStockShouldCalculatedStockBasedOnBundledProducts()
    {
        $idProductBundle = 1;
        $bundleQuantity = 2;
        $idRelatedProductId = 2;
        $relatedProductSku = 'sku-321';
        $relatedProductStock = 10;

        $productBundleAvailabilityHandlerMock = $this->createProductBundleAvailabilityHandlerMock();
        $productBundleAvailabilityHandlerMock->expects($this->once())->method('updateBundleAvailability');

        $productStockWriteMock = $this->createProductStockWriterMock($productBundleAvailabilityHandlerMock);

        $this->setupFindProductBundleBySku($productStockWriteMock, new SpyProductBundle());
        $this->setupFindBundledItemsByIdBundleProduct($idProductBundle, $bundleQuantity, $idRelatedProductId, $relatedProductSku, $productStockWriteMock);
        $this->setupFindProductStock($productStockWriteMock, $relatedProductStock, $idRelatedProductId);
        $this->setupFindOrCreateProductStockEntity($productStockWriteMock);

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setSku($relatedProductSku);
        $productConcreteTransfer->setIdProductConcrete($idProductBundle);

        $updatedProductConcreteTransfer = $productStockWriteMock->updateStock($productConcreteTransfer);

        $stocks = $updatedProductConcreteTransfer->getStocks();

        $this->assertCount(2, $stocks);

        $stockTransfer = $stocks[0];
        $this->assertSame($relatedProductStock / $bundleQuantity, $stockTransfer->getQuantity());

        $stockTransfer = $stocks[1];
        $this->assertSame($relatedProductStock / $bundleQuantity, $stockTransfer->getQuantity());
    }

    /**
     * @return void
     */
    public function testUpdateStockShouldResetStockWhenThereIsNoBundleItems()
    {
        $idProductBundle = 1;
        $idRelatedProductId = 2;
        $relatedProductSku = 'sku-321';

        $productBundleAvailabilityHandlerMock = $this->createProductBundleAvailabilityHandlerMock();
        $productBundleAvailabilityHandlerMock->expects($this->once())->method('removeBundleAvailability');

        $productStockWriteMock = $this->createProductStockWriterMock($productBundleAvailabilityHandlerMock);

        $this->setupFindProductBundleBySku($productStockWriteMock);
        $this->setupFindProductStock($productStockWriteMock, 0, $idRelatedProductId);

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setSku($relatedProductSku);
        $productConcreteTransfer->setIdProductConcrete($idProductBundle);

        $updatedProductConcreteTransfer = $productStockWriteMock->updateStock($productConcreteTransfer);
        $stocks = $updatedProductConcreteTransfer->getStocks();

        $this->assertCount(2, $stocks);

        $stockTransfer = $stocks[0];
        $this->assertSame(0, $stockTransfer->getQuantity());

        $stockTransfer = $stocks[1];
        $this->assertSame(0, $stockTransfer->getQuantity());
    }

    /**
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandlerInterface|null $productBundleAvailabilityMock
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface|null $productBundleQueryContainerMock
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToStockQueryContainerInterface|null $stockQueryContainerMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface|null $storeFacadeMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockWriter
     */
    protected function createProductStockWriterMock(
        ?ProductBundleAvailabilityHandlerInterface $productBundleAvailabilityMock = null,
        ?ProductBundleQueryContainerInterface $productBundleQueryContainerMock = null,
        ?ProductBundleToStockQueryContainerInterface $stockQueryContainerMock = null,
        ?ProductBundleToStoreFacadeInterface $storeFacadeMock = null
    ) {
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
                StoreTransfer::ID_STORE => self::ID_STORE,
            ]))
                ->build();
            $storeFacadeMock->method('getCurrentStore')->willReturn($storeTransfer);
            $storeFacadeMock->method('getStoreByName')->willReturn($storeTransfer);
        }

        $connectionMock = $this->getMockBuilder(ConnectionInterface::class)->getMock();

        $productBundleQueryContainerMock->method('getConnection')->willReturn($connectionMock);

        return $this->getMockBuilder(ProductBundleStockWriter::class)
            ->setConstructorArgs([$productBundleQueryContainerMock, $stockQueryContainerMock, $productBundleAvailabilityMock, $storeFacadeMock])
            ->setMethods(['findProductStocks', 'findOrCreateProductStockEntity', 'findBundledItemsByIdBundleProduct', 'findProductBundleBySku'])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Stock\Persistence\SpyStockProduct
     */
    protected function createStockProductEntityMock()
    {
        $stockProductEntityMock = $this->getMockBuilder(SpyStockProduct::class)
            ->setMethods(['save'])
            ->getMock();

        $stockProductEntityMock->method('save')->willReturn(1);

        return $stockProductEntityMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected function createProductBundleQueryContainerMock()
    {
        return $this->getMockBuilder(ProductBundleQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToStockQueryContainerInterface
     */
    protected function createStockQueryContainerMock()
    {
        return $this->getMockBuilder(ProductBundleToStockQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandlerInterface
     */
    protected function createProductBundleAvailabilityHandlerMock()
    {
        return $this->getMockBuilder(ProductBundleAvailabilityHandlerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface
     */
    protected function createStoreFacadeMock()
    {
        return $this->getMockBuilder(ProductBundleToStoreFacadeInterface::class)->getMock();
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $productStockWriteMock
     * @param \Orm\Zed\ProductBundle\Persistence\SpyProductBundle|null $productBundleEntity
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\ProductBundle\Persistence\SpyProductBundle
     */
    protected function setupFindProductBundleBySku(
        PHPUnit_Framework_MockObject_MockObject $productStockWriteMock,
        ?SpyProductBundle $productBundleEntity = null
    ) {
        $productStockWriteMock->method('findProductBundleBySku')->willReturn($productBundleEntity);

        return $productStockWriteMock;
    }

    /**
     * @param int $idProductBundle
     * @param int $bundleQuantity
     * @param int $idRelatedProductId
     * @param string $relatedProductSku
     * @param \PHPUnit_Framework_MockObject_MockObject $productStockWriteMock
     *
     * @return void
     */
    protected function setupFindBundledItemsByIdBundleProduct(
        $idProductBundle,
        $bundleQuantity,
        $idRelatedProductId,
        $relatedProductSku,
        PHPUnit_Framework_MockObject_MockObject $productStockWriteMock
    ) {
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
     * @param \PHPUnit_Framework_MockObject_MockObject $productStockWriteMock
     * @param int $stock
     * @param int $idRelatedProductId
     *
     * @return void
     */
    protected function setupFindProductStock(
        PHPUnit_Framework_MockObject_MockObject $productStockWriteMock,
        $stock,
        $idRelatedProductId
    ) {

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
     * @param \PHPUnit_Framework_MockObject_MockObject $productStockWriteMock
     *
     * @return void
     */
    protected function setupFindOrCreateProductStockEntity(PHPUnit_Framework_MockObject_MockObject $productStockWriteMock)
    {
        $stockProductEntityMock = $this->createStockProductEntityMock();

        $stockEntity = new SpyStock();
        $stockEntity->setName('Test stock');
        $stockProductEntityMock->setStock($stockEntity);

        $productStockWriteMock->method('findOrCreateProductStockEntity')->willReturn($stockProductEntityMock);
    }
}
