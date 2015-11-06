<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Price;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Price\Business\PriceFacade;
use Generated\Zed\Ide\AutoCompletion;
use Orm\Zed\Price\Persistence\SpyPriceProduct;
use Orm\Zed\Price\Persistence\SpyPriceProductQuery;
use Orm\Zed\Price\Persistence\SpyPriceTypeQuery;
use Orm\Zed\Product\Persistence\SpyAbstractProduct;
use Orm\Zed\Product\Persistence\SpyAbstractProductQuery;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductQuery;

/**
 * @group PriceTest
 */
class ReaderTest extends Test
{

    const DUMMY_PRICE_TYPE_1 = 'TYPE1';
    const DUMMY_PRICE_TYPE_2 = 'TYPE2';
    const DUMMY_SKU_ABSTRACT_PRODUCT = 'ABSTRACT';
    const DUMMY_SKU_CONCRETE_PRODUCT = 'CONCRETE';
    const DUMMY_PRICE_1 = 99;
    const DUMMY_PRICE_2 = 100;
    /**
     * @var PriceFacade
     */
    private $priceFacade;
    /**
     * @var AutoCompletion
     */
    protected $locator;

    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();
        $this->priceFacade = new PriceFacade(new Factory('Price'), $this->locator);
        $this->setTestData();
    }

    public function testGetAllTypesValues()
    {
        $priceType = SpyPriceTypeQuery::create()->filterByName(self::DUMMY_PRICE_TYPE_2)->findOneOrCreate();
        $priceType->setName(self::DUMMY_PRICE_TYPE_2)->save();
        $priceType = SpyPriceTypeQuery::create()->filterByName(self::DUMMY_PRICE_TYPE_1)->findOneOrCreate();
        $priceType->setName(self::DUMMY_PRICE_TYPE_1)->save();

        $priceTypes = $this->priceFacade->getPriceTypeValues();

        $isTypeInResult_1 = false;
        $isTypeInResult_2 = false;
        foreach ($priceTypes as $priceType) {
            if ($priceType === self::DUMMY_PRICE_TYPE_1) {
                $isTypeInResult_1 = true;
            } elseif ($priceType === self::DUMMY_PRICE_TYPE_2) {
                $isTypeInResult_2 = true;
            }
        }
        $this->assertTrue($isTypeInResult_1);
        $this->assertTrue($isTypeInResult_2);
    }

    public function testHasValidPriceTrue()
    {
        $hasValidPrice = $this->priceFacade->hasValidPrice(self::DUMMY_SKU_ABSTRACT_PRODUCT, self::DUMMY_PRICE_TYPE_1);
        $this->assertTrue($hasValidPrice);
    }

    public function testHasValidPriceFalse()
    {
        $hasValidPrice = $this->priceFacade->hasValidPrice(self::DUMMY_SKU_CONCRETE_PRODUCT, self::DUMMY_PRICE_TYPE_2);
        $this->assertTrue($hasValidPrice);
    }

    public function testGetPriceForAbstractProduct()
    {
        $price = $this->priceFacade->getPriceBySku(self::DUMMY_SKU_ABSTRACT_PRODUCT, self::DUMMY_PRICE_TYPE_1);
        $this->assertEquals(100, $price);
    }

    public function testGetPrice()
    {
        $price = $this->priceFacade->getPriceBySku(self::DUMMY_SKU_ABSTRACT_PRODUCT, self::DUMMY_PRICE_TYPE_1);
        $this->assertEquals(100, $price);
    }

    public function testGetPriceForConcreteProduct()
    {
        $price = $this->priceFacade->getPriceBySku(self::DUMMY_SKU_CONCRETE_PRODUCT, self::DUMMY_PRICE_TYPE_2);
        $this->assertEquals(999, $price);
    }

    protected function deletePriceEntitiesAbstract($requestProduct)
    {
        SpyPriceProductQuery::create()->filterBySpyAbstractProduct($requestProduct)->delete();
    }

    protected function deletePriceEntitiesConcrete($requestProduct)
    {
        SpyPriceProductQuery::create()->filterByProduct($requestProduct)->delete();
    }

    protected function insertPriceEntity($requestProduct, $requestPriceType)
    {
        (new SpyPriceProduct())
            ->setPrice(100)
            ->setSpyAbstractProduct($requestProduct)
            ->setPriceType($requestPriceType)
            ->save();
    }

    protected function setTestData()
    {
        $priceType1 = SpyPriceTypeQuery::create()->filterByName(self::DUMMY_PRICE_TYPE_1)->findOneOrCreate();
        $priceType1->setName(self::DUMMY_PRICE_TYPE_1)->save();

        $priceType2 = SpyPriceTypeQuery::create()->filterByName(self::DUMMY_PRICE_TYPE_2)->findOneOrCreate();
        $priceType2->setName(self::DUMMY_PRICE_TYPE_2)->save();

        $abstractProduct = SpyAbstractProductQuery::create()
            ->filterBySku(self::DUMMY_SKU_ABSTRACT_PRODUCT)
            ->findOne()
        ;

        if ($abstractProduct === null) {
            $abstractProduct = new SpyAbstractProduct();
        }

        $abstractProduct->setSku(self::DUMMY_SKU_ABSTRACT_PRODUCT)
            ->setAttributes('{}')
            ->save()
        ;

        $concreteProduct = SpyProductQuery::create()
            ->filterBySku(self::DUMMY_SKU_CONCRETE_PRODUCT)
            ->findOne()
        ;

        if ($concreteProduct === null) {
            $concreteProduct = new SpyProduct();
        }
        $concreteProduct->setSku(self::DUMMY_SKU_CONCRETE_PRODUCT)
            ->setSpyAbstractProduct($abstractProduct)
            ->setAttributes('{}')
            ->save()
        ;

        $this->deletePriceEntitiesConcrete($concreteProduct);
        $concreteProduct->setSku(self::DUMMY_SKU_CONCRETE_PRODUCT)
            ->setSpyAbstractProduct($abstractProduct)
            ->setAttributes('{}')
            ->save()
        ;

        $this->deletePriceEntitiesAbstract($abstractProduct);
        (new SpyPriceProduct())
            ->setSpyAbstractProduct($abstractProduct)
            ->setPriceType($priceType1)
            ->setPrice(100)
            ->save();

        (new SpyPriceProduct())
            ->setProduct($concreteProduct)
            ->setPriceType($priceType2)
            ->setPrice(999)

            ->save();
    }

}
