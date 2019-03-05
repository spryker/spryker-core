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

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected $floatProductConcreteEntity;

    public const ABSTRACT_SKU = 'abstract-sku';
    public const CONCRETE_SKU = 'concrete-sku';
    public const CONCRETE_FLOAT_SKU = 'concrete-float-sku';
    public const STOCK_QUANTITY_1 = 92;
    public const STOCK_QUANTITY_2 = 8;

    public const FLOAT_STOCK_QUANTITY_1 = 90.3;
    public const FLOAT_STOCK_QUANTITY_2 = 8.4;

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
     * @dataProvider calculateStockForProductProvider
     *
     * @param string $sku
     * @param float $expected
     *
     * @return void
     */
    public function testCalculateStockForProductShouldCheckAllStocks(string $sku, float $expected)
    {
        $productStock = $this->stockFacade->calculateStockForProduct($sku);

        $this->assertEquals($expected, $productStock);
    }

    /**
     * @return array
     */
    public function calculateStockForProductProvider(): array
    {
        return [
            'int stock' => [static::CONCRETE_SKU, 100],
            'float stock' => [static::CONCRETE_FLOAT_SKU, 98.7],
        ];
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
     * @dataProvider createStockProductProvider
     *
     * @param \Generated\Shared\Transfer\StockProductTransfer $stockProductTransfer
     *
     * @return void
     */
    public function testCreateStockProduct(StockProductTransfer $stockProductTransfer)
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

        $idStockProduct = $this->stockFacade->createStockProduct($stockProductTransfer);

        $stockProductEntity = SpyStockProductQuery::create()
            ->filterByIdStockProduct($idStockProduct)
            ->findOne();

        $this->assertEquals($stockProductTransfer->getQuantity(), $stockProductEntity->getQuantity());
    }

    /**
     * @return array
     */
    public function createStockProductProvider(): array
    {
        return [
            'int stock' => $this->createStockProductIntData(),
            'float stock' => $this->createStockProductFloatData(),
        ];
    }

    /**
     * @return array
     */
    protected function createStockProductIntData(): array
    {
        $stockProductTransfer = (new StockProductTransfer())
            ->setStockType('TEST')
            ->setQuantity(self::STOCK_QUANTITY_1)
            ->setSku('foo');

        return [$stockProductTransfer];
    }

    /**
     * @return array
     */
    protected function createStockProductFloatData(): array
    {
        $stockProductTransfer = (new StockProductTransfer())
            ->setStockType('TEST')
            ->setQuantity(self::FLOAT_STOCK_QUANTITY_1)
            ->setSku('foo');

        return [$stockProductTransfer];
    }

    /**
     * TODO no rollback on error
     *
     * @return void
     */
    public function testCreateStockProductShouldThrowException()
    {
        $this->markTestSkipped('Needs to be fixed first');

        $this->expectException(StockProductAlreadyExistsException::class);
        $this->expectExceptionMessage('Cannot duplicate entry: this stock type is already set for this product');

        $stockProductTransfer = (new StockProductTransfer())
            ->setStockType($this->stockEntity1->getName())
            ->setQuantity(self::STOCK_QUANTITY_1)
            ->setSku(self::CONCRETE_SKU);

        $idStockProduct = $this->stockFacade->createStockProduct($stockProductTransfer);

        $stockProductEntity = SpyStockProductQuery::create()
            ->filterByIdStockProduct($idStockProduct)
            ->findOne();

        $this->assertEquals(self::STOCK_QUANTITY_1, $stockProductEntity->getQuantity());
    }

    /**
     * @dataProvider updateStockProductProvider
     *
     * @param \Generated\Shared\Transfer\StockProductTransfer $stockProductTransfer
     *
     * @return void
     */
    public function testUpdateStockProduct(StockProductTransfer $stockProductTransfer)
    {
        $stockProductTransfer->setIdStockProduct($this->productStockEntity1->getIdStockProduct());
        $idStockProduct = $this->stockFacade->updateStockProduct($stockProductTransfer);

        $stockProductEntity = SpyStockProductQuery::create()
            ->filterByIdStockProduct($idStockProduct)
            ->findOne();

        $this->assertEquals($stockProductTransfer->getQuantity(), $stockProductEntity->getQuantity());
    }

    /**
     * @return array
     */
    public function updateStockProductProvider(): array
    {
        return [
            'int stock' => $this->updateStockProductIntData(),
            'float stock' => $this->updateStockProductFloatData(),
        ];
    }

    /**
     * @return array
     */
    protected function updateStockProductIntData(): array
    {
        $stockProductTransfer = (new StockProductTransfer())
            ->setStockType('TEST')
            ->setQuantity(555)
            ->setSku(static::CONCRETE_SKU);

        return [$stockProductTransfer];
    }

    /**
     * @return array
     */
    protected function updateStockProductFloatData(): array
    {
        $stockProductTransfer = (new StockProductTransfer())
            ->setStockType('TEST')
            ->setQuantity(555.7)
            ->setSku(static::CONCRETE_SKU);

        return [$stockProductTransfer];
    }

    /**
     * @dataProvider decrementStockProvider
     *
     * @param float $decrementBy
     * @param float $expected
     *
     * @return void
     */
    public function testDecrementStockShouldReduceStockSize(float $decrementBy, float $expected)
    {
        $this->stockFacade->decrementStockProduct(
            self::CONCRETE_SKU,
            $this->stockEntity1->getName(),
            $decrementBy
        );

        $stockSize = $this->stockFacade->calculateStockForProduct(self::CONCRETE_SKU);

        $this->assertEquals($expected, $stockSize);
    }

    /**
     * @return array
     */
    public function decrementStockProvider(): array
    {
        return [
            'int stock' => [10, 90],
            'float stock' => [10.1, 89.9],
        ];
    }

    /**
     * @dataProvider incrementStockProvider
     *
     * @param float $incrementBy
     * @param float $expected
     *
     * @return void
     */
    public function testIncrementStockShouldIncreaseStockSize(float $incrementBy, float $expected)
    {
        $this->stockFacade->incrementStockProduct(
            self::CONCRETE_SKU,
            $this->stockEntity1->getName(),
            $incrementBy
        );

        $stockSize = $this->stockFacade->calculateStockForProduct(self::CONCRETE_SKU);

        $this->assertEquals($expected, $stockSize);
    }

    /**
     * @return array
     */
    public function incrementStockProvider(): array
    {
        return [
            'int stock' => [10, 110],
            'float stock' => [10.1, 110.1],
        ];
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
     * @dataProvider persistStockProductCollectionProvider
     *
     * @param float $increment
     *
     * @return void
     */
    public function testPersistStockProductCollection(float $increment)
    {
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
     * @return array
     */
    public function persistStockProductCollectionProvider(): array
    {
        return [
            'int stock' => [20],
            'float stock' => [20.3],
        ];
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
            $this->assertTrue($stock->getQuantity() > 0);
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

        $this->floatProductConcreteEntity = new SpyProduct();
        $this->floatProductConcreteEntity
            ->setSku(self::CONCRETE_FLOAT_SKU)
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

        (new SpyStockProduct())->setFkStock($this->stockEntity1->getIdStock())
            ->setQuantity(self::FLOAT_STOCK_QUANTITY_1)
            ->setIsNeverOutOfStock(false)
            ->setFkProduct($this->floatProductConcreteEntity->getIdProduct())
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

        (new SpyStockProduct())->setFkStock($this->stockEntity2->getIdStock())
            ->setQuantity(self::FLOAT_STOCK_QUANTITY_2)
            ->setIsNeverOutOfStock(false)
            ->setFkProduct($this->floatProductConcreteEntity->getIdProduct())
            ->save();
    }
}
