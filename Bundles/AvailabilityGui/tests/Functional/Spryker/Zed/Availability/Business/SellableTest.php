<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\AvailabilityGui\Business;

use Codeception\TestCase\Test;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockProduct;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\AvailabilityGui\AvailabilityGuiDependencyProvider;
use Spryker\Zed\AvailabilityGui\Business\AvailabilityGuiBusinessFactory;
use Spryker\Zed\AvailabilityGui\Business\AvailabilityGuiFacade;
use Spryker\Zed\Kernel\Container;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group AvailabilityGui
 * @group Business
 * @group SellableTest
 */
class SellableTest extends Test
{

    /**
     * @var \Spryker\Zed\AvailabilityGui\Business\AvailabilityGuiFacadeInterface
     */
    private $AvailabilityGuiFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->AvailabilityGuiFacade = new AvailabilityGuiFacade();

        $container = new Container();
        $businessFactory = new AvailabilityGuiBusinessFactory();
        $dependencyProvider = new AvailabilityGuiDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $businessFactory->setContainer($container);

        $this->AvailabilityGuiFacade->setFactory($businessFactory);
    }

    /**
     * @return void
     */
    public function testIsProductSellable()
    {
        $this->setTestData();
        $stockProductEntity = SpyStockProductQuery::create()->findOneOrCreate();
        $stockProductEntity->setIsNeverOutOfStock(true)->save();

        $productEntity = SpyProductQuery::create()->findOneByIdProduct($stockProductEntity->getFkProduct());
        $isSellable = $this->AvailabilityGuiFacade->isProductSellable($productEntity->getSku(), 100);

        $this->assertTrue($isSellable);
    }

    /**
     * @return void
     */
    public function testCalculateRealStock()
    {
        $this->setTestData();
        $stockProductEntity = SpyStockProductQuery::create()->findOneOrCreate();
        $stockProductEntity->setIsNeverOutOfStock(false)->setQuantity(10)->save();
        $productEntity = SpyProductQuery::create()->findOneByIdProduct($stockProductEntity->getFkProduct());
        $isSellable = $this->AvailabilityGuiFacade->isProductSellable($productEntity->getSku(), 1);

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
            ->setName('TestStock1');

        $stockProduct = new SpyStockProduct();
        $stockProduct
            ->setStock($stock)
            ->setSpyProduct($productConcrete)
            ->setQuantity(5)
            ->save();

        $this->assertFalse($this->AvailabilityGuiFacade->isProductSellable('P1337', 6));
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
            ->filterByName('warehouse1')
            ->findOneOrCreate();

        $stockType1->setName('warehouse1')->save();

        $stockType2 = SpyStockQuery::create()
            ->filterByName('warehouse2')
            ->findOneOrCreate();
        $stockType2->setName('warehouse2')->save();

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
