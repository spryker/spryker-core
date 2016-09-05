<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Price;

use Codeception\TestCase\Test;
use Orm\Zed\Price\Persistence\SpyPriceProduct;
use Orm\Zed\Price\Persistence\SpyPriceProductQuery;
use Orm\Zed\Price\Persistence\SpyPriceTypeQuery;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Price\Business\PriceFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Price
 * @group ReaderTest
 */
class ReaderTest extends Test
{

    const DUMMY_PRICE_TYPE_1 = 'TYPE1';
    const DUMMY_PRICE_TYPE_2 = 'TYPE2';
    const DUMMY_SKU_PRODUCT_ABSTRACT = 'ABSTRACT';
    const DUMMY_SKU_PRODUCT_CONCRETE = 'CONCRETE';
    const DUMMY_PRICE_1 = 99;
    const DUMMY_PRICE_2 = 100;

    /**
     * @var \Spryker\Zed\Price\Business\PriceFacade
     */
    private $priceFacade;

    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->priceFacade = new PriceFacade();
        $this->setTestData();
    }

    /**
     * @return void
     */
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

    /**
     * @return void
     */
    public function testHasValidPriceTrue()
    {
        $hasValidPrice = $this->priceFacade->hasValidPrice(self::DUMMY_SKU_PRODUCT_ABSTRACT, self::DUMMY_PRICE_TYPE_1);
        $this->assertTrue($hasValidPrice);
    }

    /**
     * @return void
     */
    public function testHasValidPriceFalse()
    {
        $hasValidPrice = $this->priceFacade->hasValidPrice(self::DUMMY_SKU_PRODUCT_CONCRETE, self::DUMMY_PRICE_TYPE_2);
        $this->assertTrue($hasValidPrice);
    }

    /**
     * @return void
     */
    public function testGetPriceForProductAbstract()
    {
        $price = $this->priceFacade->getPriceBySku(self::DUMMY_SKU_PRODUCT_ABSTRACT, self::DUMMY_PRICE_TYPE_1);
        $this->assertEquals(100, $price);
    }

    /**
     * @return void
     */
    public function testGetPrice()
    {
        $price = $this->priceFacade->getPriceBySku(self::DUMMY_SKU_PRODUCT_ABSTRACT, self::DUMMY_PRICE_TYPE_1);
        $this->assertEquals(100, $price);
    }

    /**
     * @return void
     */
    public function testGetPriceForProductConcrete()
    {
        $price = $this->priceFacade->getPriceBySku(self::DUMMY_SKU_PRODUCT_CONCRETE, self::DUMMY_PRICE_TYPE_2);
        $this->assertEquals(999, $price);
    }

    /**
     * @return void
     */
    protected function deletePriceEntitiesAbstract($requestProduct)
    {
        SpyPriceProductQuery::create()->filterBySpyProductAbstract($requestProduct)->delete();
    }

    /**
     * @return void
     */
    protected function deletePriceEntitiesConcrete($requestProduct)
    {
        SpyPriceProductQuery::create()->filterByProduct($requestProduct)->delete();
    }

    /**
     * @return void
     */
    protected function insertPriceEntity($requestProduct, $requestPriceType)
    {
        (new SpyPriceProduct())
            ->setPrice(100)
            ->setSpyProductAbstract($requestProduct)
            ->setPriceType($requestPriceType)
            ->save();
    }

    /**
     * @return void
     */
    protected function setTestData()
    {
        $priceType1 = SpyPriceTypeQuery::create()->filterByName(self::DUMMY_PRICE_TYPE_1)->findOneOrCreate();
        $priceType1->setName(self::DUMMY_PRICE_TYPE_1)->save();

        $priceType2 = SpyPriceTypeQuery::create()->filterByName(self::DUMMY_PRICE_TYPE_2)->findOneOrCreate();
        $priceType2->setName(self::DUMMY_PRICE_TYPE_2)->save();

        $productAbstract = SpyProductAbstractQuery::create()
            ->filterBySku(self::DUMMY_SKU_PRODUCT_ABSTRACT)
            ->findOne();

        if ($productAbstract === null) {
            $productAbstract = new SpyProductAbstract();
        }

        $productAbstract->setSku(self::DUMMY_SKU_PRODUCT_ABSTRACT)
            ->setAttributes('{}')
            ->save();

        $productConcrete = SpyProductQuery::create()
            ->filterBySku(self::DUMMY_SKU_PRODUCT_CONCRETE)
            ->findOne();

        if ($productConcrete === null) {
            $productConcrete = new SpyProduct();
        }
        $productConcrete->setSku(self::DUMMY_SKU_PRODUCT_CONCRETE)
            ->setSpyProductAbstract($productAbstract)
            ->setAttributes('{}')
            ->save();

        $this->deletePriceEntitiesConcrete($productConcrete);
        $productConcrete->setSku(self::DUMMY_SKU_PRODUCT_CONCRETE)
            ->setSpyProductAbstract($productAbstract)
            ->setAttributes('{}')
            ->save();

        $this->deletePriceEntitiesAbstract($productAbstract);
        (new SpyPriceProduct())
            ->setSpyProductAbstract($productAbstract)
            ->setPriceType($priceType1)
            ->setPrice(100)
            ->save();

        (new SpyPriceProduct())
            ->setProduct($productConcrete)
            ->setPriceType($priceType2)
            ->setPrice(999)

            ->save();
    }

}
