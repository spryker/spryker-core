<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Stock;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProductQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProduct;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductQuery;
use SprykerFeature\Zed\Stock\Business\StockFacade;
use SprykerFeature\Zed\Stock\Persistence\Propel\SpyStockProductQuery;
use SprykerFeature\Zed\Stock\Persistence\Propel\SpyStockQuery;
use SprykerFeature\Zed\Stock\Persistence\StockQueryContainer;
use SprykerEngine\Zed\Kernel\Persistence\Factory;

/**
 * @group StockTest
 */
class CalculatorTest extends Test
{

    /**
     * @var StockFacade
     */
    private $stockFacade;

    /**
     * @var StockQueryContainer
     */
    private $stockQueryContainer;

    /**
     * @var SpyProduct
     */
    private $productEntity;

    public function setUp()
    {
        parent::setUp();

        $locator = Locator::getInstance();
        $this->stockFacade = new StockFacade(new \SprykerEngine\Zed\Kernel\Business\Factory('Stock'), $locator);
        $this->stockQueryContainer = new StockQueryContainer(new Factory('Stock'), $locator);
    }

    public function testCalculateStock()
    {
        $this->setTestData();

        $stock = $this->stockFacade->calculateStockForProduct($this->productEntity->getSku());
        $this->assertEquals(30, $stock);
    }

    protected function setTestData()
    {
        $abstractProduct = SpyAbstractProductQuery::create()
            ->filterBySku('test')
            ->findOne()
        ;

        if (null === $abstractProduct) {
            $abstractProduct = new SpyAbstractProduct();
            $abstractProduct->setSku('test');
        }

        $abstractProduct->setAttributes('{}')
            ->save()
        ;

        $product = SpyProductQuery::create()
            ->filterBySku('test2')
            ->findOne()
        ;

        if (null === $product) {
            $product = new SpyProduct();
            $product->setSku('test2');
        }

        $product->setFkAbstractProduct($abstractProduct->getIdAbstractProduct())
            ->setAttributes('{}')
            ->save()
        ;

        $this->productEntity = $product;

        $stockType1 = SpyStockQuery::create()
            ->filterByName('warehouse1')
            ->findOneOrCreate()
        ;

        $stockType1->setName('warehouse1')->save();

        $stockType2 = SpyStockQuery::create()
            ->filterByName('warehouse2')
            ->findOneOrCreate()
        ;
        $stockType2->setName('warehouse2')->save();

        $stockProduct1 = SpyStockProductQuery::create()
            ->filterByFkStock($stockType1->getIdStock())
            ->filterByFkProduct($this->productEntity->getIdProduct())
            ->findOneOrCreate()
        ;
        $stockProduct1->setFkStock($stockType1->getIdStock())
            ->setQuantity(10)
            ->setFkProduct($this->productEntity->getIdProduct())
            ->save()
        ;

        $stockProduct2 = SpyStockProductQuery::create()
            ->filterByFkStock($stockType2->getIdStock())
            ->filterByFkProduct($this->productEntity->getIdProduct())
            ->findOneOrCreate()
        ;
        $stockProduct2->setFkStock($stockType2->getIdStock())
            ->setQuantity(20)
            ->setFkProduct($this->productEntity->getIdProduct())
            ->save()
        ;
    }

}
