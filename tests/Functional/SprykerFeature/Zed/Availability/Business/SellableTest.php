<?php

namespace Functional\SprykerFeature\Zed\Availability;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Availability\Business\AvailabilityFacade;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProductQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductQuery;
use SprykerFeature\Zed\Stock\Persistence\Propel\SpyStockProductQuery;
use SprykerFeature\Zed\Stock\Persistence\Propel\SpyStockQuery;

/**
 * @group AvailabilityTest
 */
class SellableTest extends Test
{
    /**
     * @var AvailabilityFacade
     */
    private $availabilityFacade;

    public function setUp()
    {
        parent::setUp();

        $locator = Locator::getInstance();
        $this->availabilityFacade = new AvailabilityFacade(new Factory('Availability'), $locator);
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

    protected function setTestData()
    {
        $abstractProduct = SpyAbstractProductQuery::create()
            ->filterBySku('test2')
            ->findOneOrCreate()
        ;
        $abstractProduct->setSku('test2')->save();

        $productEntity = SpyProductQuery::create()
            ->filterByFkAbstractProduct($abstractProduct->getIdAbstractProduct())
            ->filterBySku('test1')
            ->findOneOrCreate()
        ;

        $productEntity
            ->setFkAbstractProduct($abstractProduct->getIdAbstractProduct())
            ->setSku('test1')
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
