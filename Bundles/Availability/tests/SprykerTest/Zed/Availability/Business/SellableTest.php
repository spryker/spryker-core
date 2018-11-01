<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Availability\Business;

use Codeception\Test\Unit;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockProduct;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\Availability\AvailabilityDependencyProvider;
use Spryker\Zed\Availability\Business\AvailabilityBusinessFactory;
use Spryker\Zed\Availability\Business\AvailabilityFacade;
use Spryker\Zed\Kernel\Container;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Availability
 * @group Business
 * @group SellableTest
 * Add your own group annotations below this line
 */
class SellableTest extends Unit
{
    /**
     * @var \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface
     */
    private $availabilityFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->availabilityFacade = new AvailabilityFacade();

        $container = new Container();
        $businessFactory = new AvailabilityBusinessFactory();
        $dependencyProvider = new AvailabilityDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $businessFactory->setContainer($container);

        $this->availabilityFacade->setFactory($businessFactory);
    }

    /**
     * @return void
     */
    public function testIsProductSellable()
    {
        $this->setTestData();
        $stockEntity = SpyStockQuery::create()->findOneByName('Warehouse1');
        $stockProductEntity = SpyStockProductQuery::create()->findOneOrCreate();
        $stockProductEntity
            ->setIsNeverOutOfStock(true)
            ->setFkStock($stockEntity->getIdStock())
            ->save();

        $productEntity = SpyProductQuery::create()->findOneByIdProduct($stockProductEntity->getFkProduct());
        $isSellable = $this->availabilityFacade->isProductSellable($productEntity->getSku(), 100);

        $this->assertTrue($isSellable);
    }

    /**
     * @return void
     */
    public function testCalculateRealStock()
    {
        $this->setTestData();
        $stockEntity = SpyStockQuery::create()->findOneByName('Warehouse1');

        $stockProductEntity = SpyStockProductQuery::create()->findOneOrCreate();
        $stockProductEntity->setIsNeverOutOfStock(false)
            ->setQuantity(10)
            ->setFkStock($stockEntity->getIdStock())
            ->save();

        $productEntity = SpyProductQuery::create()->findOneByIdProduct($stockProductEntity->getFkProduct());
        $isSellable = $this->availabilityFacade->isProductSellable($productEntity->getSku(), 1);

        $this->assertTrue($isSellable);
    }

    /**
     * @return void
     */
    public function testProductIsNotSellableIfStockNotSufficient()
    {
        $this->setTestData();

        $productAbstract = new SpyProductAbstract();
        $productAbstract
            ->setSku('AP1337')
            ->setAttributes('{}');

        $productConcrete = new SpyProduct();
        $productConcrete
            ->setSku('P1337')
            ->setSpyProductAbstract($productAbstract)
            ->setAttributes('{}');

        $stock = new SpyStock();
        $stock
            ->setName('test');

        $stockProduct = new SpyStockProduct();
        $stockProduct
            ->setStock($stock)
            ->setSpyProduct($productConcrete)
            ->setQuantity(5)
            ->save();

        $this->assertFalse($this->availabilityFacade->isProductSellable('P1337', 6));
    }

    /**
     * @return void
     */
    protected function setTestData()
    {
        $productAbstract = SpyProductAbstractQuery::create()
            ->filterBySku('test2')
            ->findOne();

        if (!$productAbstract) {
            $productAbstract = new SpyProductAbstract();
        }

        $productAbstract
            ->setSku('test2')
            ->setAttributes('{}')
            ->save();

        $productEntity = SpyProductQuery::create()
            ->filterByFkProductAbstract($productAbstract->getIdProductAbstract())
            ->filterBySku('test1')
            ->findOne();

        if (!$productEntity) {
            $productEntity = new SpyProduct();
        }

        $productEntity
            ->setFkProductAbstract($productAbstract->getIdProductAbstract())
            ->setSku('test1')
            ->setAttributes('{}')
            ->save();

        $stockType1 = SpyStockQuery::create()
            ->filterByName('Warehouse1')
            ->findOneOrCreate();

        $stockType1->setName('Warehouse1')->save();

        $stockType2 = SpyStockQuery::create()
            ->filterByName('Warehouse2')
            ->findOneOrCreate();
        $stockType2->setName('Warehouse2')->save();

        $stockProduct1 = SpyStockProductQuery::create()
            ->filterByFkStock($stockType1->getIdStock())
            ->filterByFkProduct($productEntity->getIdProduct())
            ->findOneOrCreate();
        $stockProduct1->setFkStock($stockType1->getIdStock())
            ->setQuantity(10)
            ->setFkProduct($productEntity->getIdProduct())
            ->save();
        $stockProduct2 = SpyStockProductQuery::create()
            ->filterByFkStock($stockType2->getIdStock())
            ->filterByFkProduct($productEntity->getIdProduct())
            ->findOneOrCreate();
        $stockProduct2->setFkStock($stockType2->getIdStock())
            ->setQuantity(20)
            ->setFkProduct($productEntity->getIdProduct())
            ->save();
    }
}
