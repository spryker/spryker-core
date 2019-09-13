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
use Generated\Shared\Transfer\TypeTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockProduct;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\Stock\Business\Exception\StockProductAlreadyExistsException;
use Spryker\Zed\Stock\Business\StockFacade;
use Spryker\Zed\Stock\Persistence\StockQueryContainer;

/**
 * Auto-generated group annotations
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
    /**
     * @var \Spryker\Zed\Stock\Business\StockFacade
     */
    protected $stockFacade;

    /**
     * @var \Spryker\Zed\Stock\Persistence\StockQueryContainer
     */
    protected $stockQueryContainer;

    /**
     * @var \Orm\Zed\Stock\Persistence\SpyStock
     */
    protected $stockEntity1;

    /**
     * @var \Orm\Zed\Stock\Persistence\SpyStock
     */
    protected $stockEntity2;

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
    protected function setupData()
    {
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

        $this->productStockEntity2 = new SpyStockProduct();
        $this->productStockEntity2
            ->setFkStock($this->stockEntity2->getIdStock())
            ->setQuantity(self::STOCK_QUANTITY_2)
            ->setIsNeverOutOfStock(false)
            ->setFkProduct($this->productConcreteEntity->getIdProduct())
            ->save();
    }
}
