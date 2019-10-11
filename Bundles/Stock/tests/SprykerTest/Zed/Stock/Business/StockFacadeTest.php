<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Stock\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TypeTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockProduct;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Orm\Zed\Stock\Persistence\SpyStockStoreQuery;
use Spryker\Zed\Stock\Business\Exception\StockProductAlreadyExistsException;
use Spryker\Zed\Stock\Business\StockFacade;
use Spryker\Zed\Stock\Persistence\StockQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Stock
 * @group Business
 * @group Facade
 * @group StockFacadeTest
 * Add your own group annotations below this line
 */
class StockFacadeTest extends Unit
{
    protected const STORE_NAME_DE = 'DE';
    protected const STORE_NAME_AT = 'AT';
    protected const STOCK_NAME = 'Test Warehouse';

    /**
     * @var \SprykerTest\Zed\Stock\StockBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Stock\Business\StockFacade
     */
    protected $stockFacade;

    /**
     * @var \Spryker\Zed\Stock\Persistence\StockQueryContainer
     */
    protected $stockQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected $storeTransfer;

    /**
     * @var \Orm\Zed\Stock\Persistence\SpyStock
     */
    protected $stockEntity1;

    /**
     * @var \Generated\Shared\Transfer\StockTransfer
     */
    protected $stockTransfer1;

    /**
     * @var \Orm\Zed\Stock\Persistence\SpyStock
     */
    protected $stockEntity2;

    /**
     * @var \Generated\Shared\Transfer\StockTransfer
     */
    protected $stockTransfer2;

    /**
     * @var \Orm\Zed\Stock\Persistence\SpyStockProduct
     */
    protected $productStockEntity1;

    /**
     * @var \Orm\Zed\Stock\Persistence\SpyStockProduct
     */
    protected $productStockEntity2;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected $productAbstractEntity;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected $productConcreteEntity;

    public const ABSTRACT_SKU = 'abstract-sku';
    public const CONCRETE_SKU = 'concrete-sku';
    public const STOCK_QUANTITY_1 = 92;
    public const STOCK_QUANTITY_2 = 8.2;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->stockFacade = new StockFacade();
        $this->stockQueryContainer = new StockQueryContainer();

        $this->setupData();
    }

    /**
     * @return void
     */
    public function testIsNeverOutOfStockShouldReturnFalse()
    {
        $isNeverOutOfStock = $this->stockFacade->isNeverOutOfStock(self::CONCRETE_SKU);

        $this->assertFalse($isNeverOutOfStock);
    }

    /**
     * @return void
     */
    public function testIsNeverOutOfStockShouldReturnTrue()
    {
        $this->productStockEntity1->setIsNeverOutOfStock(true);
        $this->productStockEntity1->setQuantity(null);
        $this->productStockEntity1->save();

        $isNeverOutOfStock = $this->stockFacade->isNeverOutOfStock(self::CONCRETE_SKU);

        $this->assertTrue($isNeverOutOfStock);
    }

    /**
     * @return void
     */
    public function testCalculateStockForProductShouldCheckAllStocks()
    {
        $productStock = $this->stockFacade->calculateStockForProduct(self::CONCRETE_SKU);

        $this->assertTrue($productStock->equals('100.2'));
    }

    /**
     * @return void
     */
    public function testCreateStockType()
    {
        $stockTypeTransfer = (new TypeTransfer())
            ->setName('Test-Stock-Type');

        $idStock = $this->stockFacade->createStockType($stockTypeTransfer);

        $exists = SpyStockQuery::create()
            ->filterByIdStock($idStock)
            ->count() > 0;

        $this->assertTrue($exists);
    }

    /**
     * @return void
     */
    public function testCreateStockProduct()
    {
        $productAbstractEntity = new SpyProductAbstract();
        $productAbstractEntity
            ->setSku('foo')
            ->setAttributes('{}')
            ->save();

        $productConcreteEntity = new SpyProduct();
        $productConcreteEntity
            ->setSku('foo')
            ->setAttributes('{}')
            ->setFkProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->save();

        $stockProductTransfer = (new StockProductTransfer())
            ->setStockType($this->stockEntity1->getName())
            ->setQuantity(self::STOCK_QUANTITY_1)
            ->setSku('foo');

        $idStockProduct = $this->stockFacade->createStockProduct($stockProductTransfer);

        $stockProductEntity = SpyStockProductQuery::create()
            ->filterByIdStockProduct($idStockProduct)
            ->findOne();

        $this->assertEquals(self::STOCK_QUANTITY_1, $stockProductEntity->getQuantity());
    }

    /**
     * @return void
     */
    public function testCreateStockProductShouldThrowException()
    {
        $this->expectException(StockProductAlreadyExistsException::class);
        $this->expectExceptionMessage('Cannot duplicate entry: this stock type is already set for this product');

        $stockProductTransfer = (new StockProductTransfer())
            ->setStockType($this->stockEntity1->getName())
            ->setQuantity(static::STOCK_QUANTITY_1)
            ->setSku(static::CONCRETE_SKU);

        $idStockProduct = $this->stockFacade->createStockProduct($stockProductTransfer);

        $stockProductEntity = SpyStockProductQuery::create()
            ->filterByIdStockProduct($idStockProduct)
            ->findOne();

        $this->assertEquals(static::STOCK_QUANTITY_1, $stockProductEntity->getQuantity());
    }

    /**
     * @return void
     */
    public function testUpdateStockProduct()
    {
        $stockProductTransfer = (new StockProductTransfer())
            ->setIdStockProduct($this->productStockEntity1->getIdStockProduct())
            ->setStockType($this->stockEntity1->getName())
            ->setQuantity(555)
            ->setSku(self::CONCRETE_SKU);

        $idStockProduct = $this->stockFacade->updateStockProduct($stockProductTransfer);

        $stockProductEntity = SpyStockProductQuery::create()
            ->filterByIdStockProduct($idStockProduct)
            ->findOne();

        $this->assertEquals(555, $stockProductEntity->getQuantity());
    }

    /**
     * @return void
     */
    public function testDecrementStockShouldReduceStockSize()
    {
        $this->stockFacade->decrementStockProduct(
            self::CONCRETE_SKU,
            $this->stockEntity1->getName(),
            10
        );

        $stockSize = $this->stockFacade->calculateStockForProduct(self::CONCRETE_SKU);

        $this->assertTrue($stockSize->equals('90.2'));
    }

    /**
     * @return void
     */
    public function testIncrementStockShouldIncreaseStockSize()
    {
        $this->stockFacade->incrementStockProduct(
            self::CONCRETE_SKU,
            $this->stockEntity1->getName(),
            10
        );

        $stockSize = $this->stockFacade->calculateStockForProduct(self::CONCRETE_SKU);

        $this->assertTrue($stockSize->equals('110.2'));
    }

    /**
     * @return void
     */
    public function testHasStockProductShouldReturnTrue()
    {
        $exists = $this->stockFacade->hasStockProduct(
            self::CONCRETE_SKU,
            $this->stockEntity1->getName()
        );

        $this->assertTrue($exists);
    }

    /**
     * @return void
     */
    public function testHasStockProductShouldReturnFalse()
    {
        $exists = $this->stockFacade->hasStockProduct(
            'INVALIDSKU',
            'INVALIDTYPE'
        );

        $this->assertFalse($exists);
    }

    /**
     * @return void
     */
    public function testPersistStockProductCollection()
    {
        $increment = 20;

        $stockTransfer1 = (new StockProductTransfer())
            ->setSku(self::CONCRETE_SKU)
            ->setQuantity(self::STOCK_QUANTITY_1 + $increment)
            ->setIsNeverOutOfStock(false)
            ->setStockType($this->stockEntity1->getName());

        $stockTransfer2 = (new StockProductTransfer())
            ->setSku(self::CONCRETE_SKU)
            ->setQuantity(self::STOCK_QUANTITY_1 + $increment)
            ->setIsNeverOutOfStock(false)
            ->setStockType($this->stockEntity2->getName());

        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setStocks(new ArrayObject([
                $stockTransfer1, $stockTransfer2,
            ]));

        $this->stockFacade->persistStockProductCollection($productConcreteTransfer);

        $stockProductEntityCollection = SpyStockProductQuery::create()
            ->joinStock()
            ->filterByFkProduct($this->productConcreteEntity->getIdProduct())
            ->find();

        $this->assertNotEmpty($stockProductEntityCollection);

        foreach ($stockProductEntityCollection as $stockProductEntity) {
            $this->assertEquals(self::STOCK_QUANTITY_1 + $increment, $stockProductEntity->getQuantity());
            $this->assertEquals($this->productConcreteEntity->getIdProduct(), $stockProductEntity->getFkProduct());
        }
    }

    /**
     * @return void
     */
    public function testExpandProductConcreteWithStocks()
    {
        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setIdProductConcrete($this->productConcreteEntity->getIdProduct())
            ->setSku(self::CONCRETE_SKU);

        $productConcreteTransfer = $this->stockFacade->expandProductConcreteWithStocks($productConcreteTransfer);

        $this->assertNotEmpty($productConcreteTransfer->getStocks());
        foreach ($productConcreteTransfer->getStocks() as $stock) {
            $this->assertTrue($stock->getQuantity()->greaterThan(0));
            $this->assertEquals($stock->getSku(), self::CONCRETE_SKU);
        }
    }

    /**
     * @return void
     */
    public function testExpandProductConcreteWithStocksWillExpandOnlyWithActiveStocks(): void
    {
        //Arrange
        $this->stockEntity2->setIsActive(false)->save();
        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setIdProductConcrete($this->productConcreteEntity->getIdProduct())
            ->setSku(self::CONCRETE_SKU);

        //Act
        $productConcreteTransfer = $this->stockFacade->expandProductConcreteWithStocks($productConcreteTransfer);

        //Assert
        $this->assertNotEmpty($productConcreteTransfer->getStocks());
        foreach ($productConcreteTransfer->getStocks() as $stock) {
            $this->assertNotEquals($this->stockTransfer2->getIdStock(), $stock->getFkStock(), 'Stock ID did not match expected value.');
        }
    }

    /**
     * @return void
     */
    public function testGetAvailableStockTypesWillReturnCollectionOfStockNamesIndexedByStoreNames(): void
    {
        //Arrange
        $this->stockEntity2->setIsActive(false)->save();

        //Act
        $stocks = $this->stockFacade->getAvailableStockTypes();

        //Assert
        $this->assertEquals([
            $this->stockTransfer1->getName() => $this->stockTransfer1->getName(),
            $this->stockTransfer2->getName() => $this->stockTransfer2->getName(),
        ], $stocks, 'Available stock types collection does not match expected value.');
    }

    /**
     * @return void
     */
    public function testGetStockProductsByIdProductWillReturnStockProductWhereStockIsActive(): void
    {
        //Arrange
        $this->stockEntity2->setIsActive(false)->save();

        //Act
        $stockProductTransfers = $this->stockFacade->getStockProductsByIdProduct($this->productConcreteEntity->getIdProduct());

        //Assert
        $this->assertCount(1, $stockProductTransfers, 'Stock products count does not match expected value.');
        $this->assertTrue(
            $stockProductTransfers[0]->getQuantity()->equals(static::STOCK_QUANTITY_1),
            'Stock product quantity does not match expected value.'
        );
        $this->assertEquals(
            $this->stockTransfer1->getIdStock(),
            $stockProductTransfers[0]->getFkStock(),
            'Stock ID does not match expected value.'
        );
    }

    /**
     * @return void
     */
    public function testGetStockTypesForStoreWillReturnCollectionOfStockNamesIndexedByStockName(): void
    {
        //Act
        $stockCollection = $this->stockFacade->getStockTypesForStore($this->storeTransfer);

        //Assert
        $this->assertIsArray($stockCollection, 'Stock types collection should be an array.');
        $this->assertEquals([
            $this->stockTransfer1->getName() => $this->stockTransfer1->getName(),
        ], $stockCollection, 'Stock types collection does not match expected value.');
    }

    /**
     * @return void
     */
    public function testGetWarehouseToStoreMappingWillReturnCollectionOfStocksWithCollectionOfStoresNamesIndexedByStoresName(): void
    {
        //Arrange
        $this->tester->haveStockStoreRelation(
            (new StockTransfer())->fromArray($this->stockEntity2->toArray(), true),
            $this->storeTransfer
        );

        //Act
        $stockCollection = $this->stockFacade->getWarehouseToStoreMapping();

        //Assert
        $this->assertIsArray($stockCollection, 'Warehouse to store mapping collection should be an array.');
        $storeName = $this->storeTransfer->getName();
        $this->assertEquals([
            $this->stockTransfer1->getName() => [
                $storeName => $storeName,
            ],
            $this->stockTransfer2->getName() => [
                $storeName => $storeName,
            ],
        ], $stockCollection, 'Warehouse to store mapping collection does not match expected value.');
    }

    /**
     * @return void
     */
    public function testGetStoreToWarehouseMappingWillReturnCollectionOfStoreNamesWithCollectionOfStockNamesIndexedByStockName(): void
    {
        //Arrange
        /** @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer2 */
        $storeTransfer2 = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $this->assignStockToStore($storeTransfer2, $this->stockTransfer1);
        $this->assignStockToStore($storeTransfer2, $this->stockTransfer2);

        //Act
        $storeToWarehouseMapping = $this->stockFacade->getStoreToWarehouseMapping();

        //Assert
        $this->assertArrayHasKey(
            $this->storeTransfer->getName(),
            $storeToWarehouseMapping,
            'Store to warehouse mapping collection does not have expected key.'
        );
        $this->assertEquals(
            [$this->stockTransfer1->getName()],
            $storeToWarehouseMapping[$this->storeTransfer->getName()],
            'Store to warehouse mapping collection does not match expected value.'
        );

        $this->assertArrayHasKey(
            $storeTransfer2->getName(),
            $storeToWarehouseMapping,
            'Store to warehouse mapping collection does not have expected key.'
        );
        $this->assertEquals(
            [
                $this->stockTransfer1->getName(),
                $this->stockTransfer2->getName(),
            ],
            $storeToWarehouseMapping[$storeTransfer2->getName()],
            'Store to warehouse mapping collection does not match expected value.'
        );
    }

    /**
     * @return void
     */
    public function testFindStockProductsByIdProductForStoreWillReturnCollectionOfStockProducts(): void
    {
        //Arrange
        $this->tester->haveStockStoreRelation($this->stockTransfer2, $this->storeTransfer);

        //Act
        $productStockCollection = $this->stockFacade->findStockProductsByIdProductForStore(
            $this->productConcreteEntity->getIdProduct(),
            $this->storeTransfer
        );

        //Assert
        $this->assertIsArray($productStockCollection, 'Product stock collection should be an array.');
        $this->assertCount(2, $productStockCollection, 'Product stock collection count does not match expected value.');
        foreach ($productStockCollection as $stockProductTransfer) {
            $this->assertEquals(
                $this->productConcreteEntity->getSku(),
                $stockProductTransfer->getSku(),
                'Concrete product sku of stock product does not match expected value.'
            );
        }
    }

    /**
     * @return void
     */
    public function testFindStockProductsByIdProductForStoreWillReturnCollectionOfStockProductsWithInactiveStocksIncluded(): void
    {
        //Arrange
        $this->tester->haveStockStoreRelation($this->stockTransfer2, $this->storeTransfer);
        $this->stockEntity2->setIsActive(false)->save();

        //Act
        $productStockCollection = $this->stockFacade->findStockProductsByIdProductForStore(
            $this->productConcreteEntity->getIdProduct(),
            $this->storeTransfer
        );

        //Assert
        $this->assertIsArray($productStockCollection, 'Product stock collection should be an array.');
        $this->assertCount(2, $productStockCollection, 'Product stock collection count does not match expected value.');
        foreach ($productStockCollection as $stockProductTransfer) {
            $this->assertEquals(
                $this->productConcreteEntity->getSku(),
                $stockProductTransfer->getSku(),
                'Concrete product sku of stock product does not match expected value.'
            );
        }
    }

    /**
     * @return void
     */
    public function testFindStockByNameWillFindExistingStock(): void
    {
        //Act
        $stockTransfer = $this->stockFacade->findStockByName($this->stockTransfer1->getName());

        //Assert
        $this->assertEquals($stockTransfer->getIdStock(), $this->stockTransfer1->getIdStock(), 'Stock ID does not match expected value.');
        $this->assertEquals($stockTransfer->getName(), $this->stockTransfer1->getName(), 'Stock name does not match expected value.');
    }

    /**
     * @return void
     */
    public function testFindStockByNameWillReturnNullForNonExistedStockName(): void
    {
        //Act
        $stockTransfer = $this->stockFacade->findStockByName($this->stockTransfer1->getName());

        //Assert
        $this->assertEquals($stockTransfer->getIdStock(), $this->stockTransfer1->getIdStock(), 'Stock ID does not match expected value.');
        $this->assertEquals($stockTransfer->getName(), $this->stockTransfer1->getName(), 'Stock name does not match expected value.');
    }

    /**
     * @return void
     */
    public function testCreateStockWillCreateStock(): void
    {
        //Arrange
        $originStockTransfer = (new StockTransfer())
            ->setName(static::STOCK_NAME)
            ->setIsActive(false);

        //Act
        $stockResponseTransfer = $this->stockFacade->createStock($originStockTransfer);

        //Assert
        $this->assertTrue($stockResponseTransfer->getIsSuccessful(), 'Stock response should be successful.');
        $stockTransfer = $stockResponseTransfer->getStock();
        $this->assertIsInt($stockTransfer->getIdStock(), 'Stock ID should be integer value.');
        $this->assertEquals($originStockTransfer->getName(), $stockTransfer->getName(), 'Stock name does not match expected value.');
        $this->assertEquals($originStockTransfer->getIsActive(), $stockTransfer->getIsActive(), 'Stock active status does not match expected value.');
    }

    /**
     * @return void
     */
    public function testCreateStockWithRelationToStoreWillCreateStockWithRelations(): void
    {
        //Arrange
        $storeRelationTransfer = (new StoreRelationTransfer())
            ->setIdStores([$this->storeTransfer->getIdStore()]);
        $originStockTransfer = (new StockTransfer())
            ->setName(static::STOCK_NAME)
            ->setIsActive(false)
            ->setStoreRelation($storeRelationTransfer);

        //Act
        $stockResponseTransfer = $this->stockFacade->createStock($originStockTransfer);
        $storeStockRelation = $this->stockFacade->getStockTypesForStore($this->storeTransfer);

        //Assert
        $this->assertTrue($stockResponseTransfer->getIsSuccessful(), 'Stock response should be successful.');
        $stockTransfer = $stockResponseTransfer->getStock();
        $this->assertIsInt($stockTransfer->getIdStock(), 'Stock ID should be integer value.');
        $this->assertEquals($originStockTransfer->getName(), $stockTransfer->getName(), 'Stock name does not match expected value.');
        $this->assertEquals($originStockTransfer->getIsActive(), $stockTransfer->getIsActive(), 'Stock active status does not match expected value.');
        $this->assertNotNull($stockTransfer->getStoreRelation(), 'Stock should have store relations.');
        $this->assertEquals(
            $storeRelationTransfer->getIdStores(),
            $stockTransfer->getStoreRelation()->getIdStores(),
            'IDs of related stores does not match expected value.'
        );
        $this->assertContains($stockTransfer->getName(), $storeStockRelation, 'Store relation does not contain expected store name.');
    }

    /**
     * @return void
     */
    public function testFindStockByIdShouldReturnStockTransferForExistingStockId(): void
    {
        //Act
        $stockTransfer = $this->stockFacade->findStockById($this->stockTransfer1->getIdStock());

        //Assert
        $this->assertEquals($this->stockTransfer1->getIdStock(), $stockTransfer->getIdStock(), 'Stock ID should be integer value.');
        $this->assertEquals($this->stockTransfer1->getName(), $stockTransfer->getName(), 'Stock name does not match expected value.');
        $this->assertEquals($this->stockTransfer1->getIsActive(), $stockTransfer->getIsActive(), 'Stock active status does not match expected value.');
    }

    /**
     * @return void
     */
    public function testFindStockByIdShouldReturnStockTransferForNonExistingStockId(): void
    {
        //Act
        $result = $this->stockFacade->findStockById(-1);

        //Assert
        $this->assertNull($result, 'The result must be null');
    }

    /**
     * @return void
     */
    public function testUpdateStockShouldUpdateStockName(): void
    {
        //Arrange
        $originStockTransfer = $this->stockTransfer1;
        $originStockTransfer->setName(static::STOCK_NAME);

        //Act
        $stockResponseTransfer = $this->stockFacade->updateStock($originStockTransfer);

        //Assert
        $this->assertTrue($stockResponseTransfer->getIsSuccessful(), 'Stock response should be successful.');
        $stockTransfer = $stockResponseTransfer->getStock();
        $this->assertEquals($originStockTransfer->getName(), $stockTransfer->getName(), 'Stock name does not match expected value.');
    }

    /**
     * @return void
     */
    public function testUpdateStockShouldUpdateStockStatus(): void
    {
        //Arrange
        $originStockTransfer = $this->stockTransfer1;
        $originStockTransfer->setIsActive(false);

        //Act
        $stockResponseTransfer = $this->stockFacade->updateStock($originStockTransfer);

        //Assert
        $this->assertTrue($stockResponseTransfer->getIsSuccessful(), 'Stock response should be successful.');
        $stockTransfer = $stockResponseTransfer->getStock();
        $this->assertEquals($originStockTransfer->getIsActive(), $stockTransfer->getIsActive(), 'Stock active status does not match expected value.');
    }

    /**
     * @return void
     */
    public function testUpdateStockShouldAddStoreRelations(): void
    {
        //Arrange
        $storeRelationTransfer = (new StoreRelationTransfer())
            ->setIdStores([$this->storeTransfer->getIdStore()]);
        $originStockTransfer = $this->stockTransfer2;
        $originStockTransfer->setStoreRelation($storeRelationTransfer);

        //Act
        $stockResponseTransfer = $this->stockFacade->updateStock($originStockTransfer);
        $storeStockRelation = $this->stockFacade->getStockTypesForStore($this->storeTransfer);

        //Assert
        $this->assertTrue($stockResponseTransfer->getIsSuccessful(), 'Stock response should be successful.');
        $stockTransfer = $stockResponseTransfer->getStock();
        $this->assertContains($stockTransfer->getName(), $storeStockRelation, 'Store relation does not contain expected store name.');
    }

    /**
     * @return void
     */
    public function testUpdateStockShouldRemoveStoreRelations(): void
    {
        //Arrange
        $storeRelationTransfer = (new StoreRelationTransfer())->setIdStores([]);
        $originStockTransfer = $this->stockTransfer1;
        $originStockTransfer->setStoreRelation($storeRelationTransfer);

        //Act
        $stockResponseTransfer = $this->stockFacade->updateStock($originStockTransfer);
        $storeStockRelation = $this->stockFacade->getStockTypesForStore($this->storeTransfer);

        //Assert
        $this->assertTrue($stockResponseTransfer->getIsSuccessful(), 'Stock response should be successful.');
        $stockTransfer = $stockResponseTransfer->getStock();
        $this->assertNotContains($stockTransfer->getName(), $storeStockRelation, 'Store relation should not contain store name.');
    }

    /**
     * @return void
     */
    protected function setupData()
    {
        $this->cleanUpDatabase();

        $this->storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => static::STORE_NAME_DE,
        ]);

        $this->productAbstractEntity = new SpyProductAbstract();
        $this->productAbstractEntity
            ->setSku(self::ABSTRACT_SKU)
            ->setAttributes('{}')
            ->save();

        $this->productConcreteEntity = new SpyProduct();
        $this->productConcreteEntity
            ->setSku(self::CONCRETE_SKU)
            ->setAttributes('{}')
            ->setFkProductAbstract($this->productAbstractEntity->getIdProductAbstract())
            ->save();

        $this->stockEntity1 = new SpyStock();
        $this->stockEntity1
            ->setName('TEST')
            ->save();
        $this->stockTransfer1 = $this->mapStockEntityToStockTransfer($this->stockEntity1, new StockTransfer());
        $this->assignStockToStore($this->storeTransfer, $this->stockTransfer1);

        $this->productStockEntity1 = new SpyStockProduct();
        $this->productStockEntity1
            ->setFkStock($this->stockEntity1->getIdStock())
            ->setQuantity(self::STOCK_QUANTITY_1)
            ->setIsNeverOutOfStock(false)
            ->setFkProduct($this->productConcreteEntity->getIdProduct())
            ->save();

        $this->stockEntity2 = new SpyStock();
        $this->stockEntity2
            ->setName('TEST2')
            ->save();
        $this->stockTransfer2 = $this->mapStockEntityToStockTransfer($this->stockEntity2, new StockTransfer());

        $this->productStockEntity2 = new SpyStockProduct();
        $this->productStockEntity2
            ->setFkStock($this->stockEntity2->getIdStock())
            ->setQuantity(self::STOCK_QUANTITY_2)
            ->setIsNeverOutOfStock(false)
            ->setFkProduct($this->productConcreteEntity->getIdProduct())
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return void
     */
    protected function assignStockToStore(StoreTransfer $storeTransfer, StockTransfer $stockTransfer): void
    {
        $this->tester->haveStockStoreRelation(
            $stockTransfer,
            $storeTransfer
        );
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStock $stockEntity
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    protected function mapStockEntityToStockTransfer(SpyStock $stockEntity, StockTransfer $stockTransfer): StockTransfer
    {
        return $stockTransfer->fromArray($stockEntity->toArray(), true);
    }

    /**
     * @return void
     */
    protected function cleanUpDatabase(): void
    {
        SpyStockStoreQuery::create()->deleteAll();
        SpyStockProductQuery::create()->deleteAll();
        SpyStockQuery::create()->deleteAll();
    }
}
