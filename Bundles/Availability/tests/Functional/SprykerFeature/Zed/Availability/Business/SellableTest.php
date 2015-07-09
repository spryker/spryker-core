<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Availability;

use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Availability\Business\AvailabilityFacade;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProductQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProduct;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductQuery;
use SprykerFeature\Zed\Stock\Persistence\Propel\SpyStock;
use SprykerFeature\Zed\Stock\Persistence\Propel\SpyStockProduct;
use SprykerFeature\Zed\Stock\Persistence\Propel\SpyStockProductQuery;
use SprykerFeature\Zed\Stock\Persistence\Propel\SpyStockQuery;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Business
 * @group Availability
 * @group SellableTest
 */
class SellableTest extends AbstractFunctionalTest
{

    /**
     * @var AvailabilityFacade
     */
    private $availabilityFacade;

    public function setUp()
    {
        parent::setUp();

        $locator = Locator::getInstance();
        $this->availabilityFacade = $this->getFacade('SprykerFeature', 'Availability');
    }

    public function testIsProductSellable()
    {
        $this->setTestData();
        $stockProductEntity = SpyStockProductQuery::create()->findOneOrCreate();
        $stockProductEntity->setIsNeverOutOfStock(true)->save();

        $productEntity = SpyProductQuery::create()->findOneByIdProduct($stockProductEntity->getFkProduct());
        $isSellable = $this->availabilityFacade->isProductSellable($productEntity->getSku(), 100);

        $this->assertTrue($isSellable);
    }

    public function testCalculateRealStock()
    {
        $this->setTestData();
        $stockProductEntity = SpyStockProductQuery::create()->findOneOrCreate();
        $stockProductEntity->setIsNeverOutOfStock(false)->setQuantity(10)->save();
        $productEntity = SpyProductQuery::create()->findOneByIdProduct($stockProductEntity->getFkProduct());
        $isSellable = $this->availabilityFacade->isProductSellable($productEntity->getSku(), 1);

        $this->assertTrue($isSellable);
    }

    public function testProductIsNotSellableIfStockNotSufficient()
    {
        $this->setTestData();

        $abstractProduct = new SpyAbstractProduct();
        $abstractProduct
            ->setSku('AP1337')
            ->setAttributes('{}')
        ;

        $concreteProduct = new SpyProduct();
        $concreteProduct
            ->setSku('P1337')
            ->setSpyAbstractProduct($abstractProduct)
            ->setAttributes('{}')
        ;

        $stock = new SpyStock();
        $stock
            ->setName('TestStock1')
        ;

        $stockProduct = new SpyStockProduct();
        $stockProduct
            ->setStock($stock)
            ->setSpyProduct($concreteProduct)
            ->setQuantity(5)
            ->save()
        ;

        $this->assertFalse($this->availabilityFacade->isProductSellable('P1337', 6));
    }

    protected function setTestData()
    {
        $abstractProduct = SpyAbstractProductQuery::create()
            ->filterBySku('test2')
            ->findOne()
        ;

        if (!$abstractProduct) {
            $abstractProduct = new SpyAbstractProduct();
        }

        $abstractProduct
            ->setSku('test2')
            ->setAttributes('{}')
            ->save()
        ;

        $productEntity = SpyProductQuery::create()
            ->filterByFkAbstractProduct($abstractProduct->getIdAbstractProduct())
            ->filterBySku('test1')
            ->findOne()
        ;

        if (!$productEntity) {
            $productEntity = new SpyProduct();
        }

        $productEntity
            ->setFkAbstractProduct($abstractProduct->getIdAbstractProduct())
            ->setSku('test1')
            ->setAttributes('{}')
            ->save()
        ;

        $stockType1 = SpyStockQuery::create()
            ->filterByName('warehouse1')
            ->findOneOrCreate();

        $stockType1->setName('warehouse1')->save();

        $stockType2 = SpyStockQuery::create()
            ->filterByName('warehouse2')
            ->findOneOrCreate()
        ;
        $stockType2->setName('warehouse2')->save();

        $stockProduct1 = SpyStockProductQuery::create()
            ->filterByFkStock($stockType1->getIdStock())
            ->filterByFkProduct($productEntity->getIdProduct())
            ->findOneOrCreate()
        ;
        $stockProduct1->setFkStock($stockType1->getIdStock())
            ->setQuantity(10)
            ->setFkProduct($productEntity->getIdProduct())
            ->save()
        ;
        $stockProduct2 = SpyStockProductQuery::create()
            ->filterByFkStock($stockType2->getIdStock())
            ->filterByFkProduct($productEntity->getIdProduct())
            ->findOneOrCreate()
        ;
        $stockProduct2->setFkStock($stockType2->getIdStock())
            ->setQuantity(20)
            ->setFkProduct($productEntity->getIdProduct())
            ->save()
        ;
    }

}
