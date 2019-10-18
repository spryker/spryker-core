<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Stock\Business;

use Codeception\Test\Unit;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\Stock\Business\StockFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Stock
 * @group Business
 * @group CalculatorTest
 * Add your own group annotations below this line
 */
class CalculatorTest extends Unit
{
    /**
     * @var \Spryker\Zed\Stock\Business\StockFacade
     */
    private $stockFacade;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProduct
     */
    private $productEntity;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->stockFacade = new StockFacade();

        $this->setupProductEntity();
        $this->setupStockProductEntity();
    }

    /**
     * @return void
     */
    public function testCalculateStock()
    {
        $stock = $this->stockFacade->calculateStockForProduct($this->productEntity->getSku());
        $this->assertTrue($stock->equals(30));
    }

    /**
     * @return void
     */
    protected function setupProductEntity()
    {
        $productAbstract = SpyProductAbstractQuery::create()
            ->filterBySku('test')
            ->findOneOrCreate();

        $productAbstract
            ->setAttributes('{}')
            ->save();

        $product = SpyProductQuery::create()
            ->filterBySku('test2')
            ->findOneOrCreate();

        $product
            ->setFkProductAbstract($productAbstract->getIdProductAbstract())
            ->setAttributes('{}')
            ->save();

        $this->productEntity = $product;

        $stockType1 = SpyStockQuery::create()
            ->filterByName('warehouse1')
            ->findOneOrCreate();

        $stockType1->setName('warehouse1')->save();

        $stockType2 = SpyStockQuery::create()
            ->filterByName('warehouse2')
            ->findOneOrCreate();
        $stockType2->setName('warehouse2')->save();

        $stockProduct1 = SpyStockProductQuery::create()
            ->filterByFkStock($stockType1->getIdStock())
            ->filterByFkProduct($this->productEntity->getIdProduct())
            ->findOneOrCreate();
        $stockProduct1->setFkStock($stockType1->getIdStock())
            ->setQuantity(10)
            ->setFkProduct($this->productEntity->getIdProduct())
            ->save();

        $stockProduct2 = SpyStockProductQuery::create()
            ->filterByFkStock($stockType2->getIdStock())
            ->filterByFkProduct($this->productEntity->getIdProduct())
            ->findOneOrCreate();
        $stockProduct2->setFkStock($stockType2->getIdStock())
            ->setQuantity(20)
            ->setFkProduct($this->productEntity->getIdProduct())
            ->save();
    }

    /**
     * @return void
     */
    protected function setupStockProductEntity()
    {
        $stockType1 = SpyStockQuery::create()
            ->filterByName('warehouse1')
            ->findOneOrCreate();

        $stockType1
            ->setName('warehouse1')->save();

        $stockType2 = SpyStockQuery::create()
            ->filterByName('warehouse2')
            ->findOneOrCreate();

        $stockType2
            ->setName('warehouse2')->save();

        $stockProduct1 = SpyStockProductQuery::create()
            ->filterByFkStock($stockType1->getIdStock())
            ->filterByFkProduct($this->productEntity->getIdProduct())
            ->findOneOrCreate();

        $stockProduct1->setFkStock($stockType1->getIdStock())
            ->setQuantity(10)
            ->setFkProduct($this->productEntity->getIdProduct())
            ->save();

        $stockProduct2 = SpyStockProductQuery::create()
            ->filterByFkStock($stockType2->getIdStock())
            ->filterByFkProduct($this->productEntity->getIdProduct())
            ->findOneOrCreate();

        $stockProduct2->setFkStock($stockType2->getIdStock())
            ->setQuantity(20)
            ->setFkProduct($this->productEntity->getIdProduct())
            ->save();
    }
}
