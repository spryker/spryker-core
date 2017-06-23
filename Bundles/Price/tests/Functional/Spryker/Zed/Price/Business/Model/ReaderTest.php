<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Price\Business\Model;

use Codeception\TestCase\Test;
use Orm\Zed\Price\Persistence\SpyPriceProduct;
use Orm\Zed\Price\Persistence\SpyPriceProductQuery;
use Orm\Zed\Price\Persistence\SpyPriceTypeQuery;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Price\Business\Exception\MissingPriceException;
use Spryker\Zed\Price\Business\PriceFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Price
 * @group Business
 * @group Model
 * @group ReaderTest
 */
class ReaderTest extends Test
{

    const DUMMY_PRICE_TYPE_1 = 'TYPE1';
    const DUMMY_PRICE_TYPE_2 = 'TYPE2';
    const DUMMY_PRICE_TYPE_3 = 'TYPE3';
    const DUMMY_PRICE_TYPE_4 = 'TYPE4';
    const DUMMY_SKU_PRODUCT_ABSTRACT = 'ABSTRACT';
    const DUMMY_SKU_PRODUCT_CONCRETE = 'CONCRETE';
    const DUMMY_PRICE_1 = 99;
    const DUMMY_PRICE_2 = 100;

    /**
     * @var \Spryker\Zed\Price\Business\PriceFacade
     */
    private $priceFacade;

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
        $priceTypeEntity1 = SpyPriceTypeQuery::create()->filterByName(self::DUMMY_PRICE_TYPE_2)->findOneOrCreate();
        $priceTypeEntity1->setName(self::DUMMY_PRICE_TYPE_2)->save();

        $priceTypeEntity2 = SpyPriceTypeQuery::create()->filterByName(self::DUMMY_PRICE_TYPE_1)->findOneOrCreate();
        $priceTypeEntity2->setName(self::DUMMY_PRICE_TYPE_1)->save();

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
    public function testHasValidNonExistentPriceForAbstractProductReturnsFalse()
    {
        $hasValidPrice = $this->priceFacade->hasValidPrice(self::DUMMY_SKU_PRODUCT_ABSTRACT, self::DUMMY_PRICE_TYPE_4);
        $this->assertFalse($hasValidPrice);
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
    public function testGetNonExistentPriceForAbstractProduct()
    {
        $this->expectException(MissingPriceException::class);

        $this->priceFacade->getPriceBySku(self::DUMMY_SKU_PRODUCT_ABSTRACT, self::DUMMY_PRICE_TYPE_4);
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
    public function testFindPricesBySkuForAbstractProductReturnsOnlyAbstractPrices()
    {
        $priceProductTransfers = $this->priceFacade->findPricesBySku(self::DUMMY_SKU_PRODUCT_ABSTRACT);

        $this->assertCount(2, $priceProductTransfers);
    }

    /**
     * @return void
     */
    public function testFindPricesBySkuForConcreteProductReturnsMergedPrices()
    {
        $priceProductTransfers = $this->priceFacade->findPricesBySku(self::DUMMY_SKU_PRODUCT_CONCRETE);

        $this->assertCount(3, $priceProductTransfers);

        $expectedPrices = [
            self::DUMMY_PRICE_TYPE_1 => 100,
            self::DUMMY_PRICE_TYPE_2 => 999,
            self::DUMMY_PRICE_TYPE_3 => 120,
        ];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $expectedPrice = $expectedPrices[$priceProductTransfer->getPriceTypeName()];
            $this->assertSame(
                $expectedPrice,
                $priceProductTransfer->getPrice(),
                sprintf('Price is not the same as expected in "%s" price type.', $priceProductTransfer->getPriceTypeName())
            );
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $spyProductAbstractEntity
     *
     * @return void
     */
    protected function deletePriceEntitiesAbstract($spyProductAbstractEntity)
    {
        SpyPriceProductQuery::create()->filterBySpyProductAbstract($spyProductAbstractEntity)->delete();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $spyProductEntity
     *
     * @return void
     */
    protected function deletePriceEntitiesConcrete($spyProductEntity)
    {
        SpyPriceProductQuery::create()->filterByProduct($spyProductEntity)->delete();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $spyProductAbstractEntity
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @return void
     */
    protected function insertPriceEntity($spyProductAbstractEntity, $priceType)
    {
        (new SpyPriceProduct())
            ->setPrice(100)
            ->setSpyProductAbstract($spyProductAbstractEntity)
            ->setPriceType($priceType)
            ->save();
    }

    /**
     * @return void
     */
    protected function setTestData()
    {
        $priceTypeEntity1 = SpyPriceTypeQuery::create()->filterByName(self::DUMMY_PRICE_TYPE_1)->findOneOrCreate();
        $priceTypeEntity1->setName(self::DUMMY_PRICE_TYPE_1)->save();

        $priceTypeEntity2 = SpyPriceTypeQuery::create()->filterByName(self::DUMMY_PRICE_TYPE_2)->findOneOrCreate();
        $priceTypeEntity2->setName(self::DUMMY_PRICE_TYPE_2)->save();

        $priceTypeEntity3 = SpyPriceTypeQuery::create()->filterByName(self::DUMMY_PRICE_TYPE_3)->findOneOrCreate();
        $priceTypeEntity3->setName(self::DUMMY_PRICE_TYPE_3)->save();

        $priceTypeEntity4 = SpyPriceTypeQuery::create()->filterByName(self::DUMMY_PRICE_TYPE_4)->findOneOrCreate();
        $priceTypeEntity4->setName(self::DUMMY_PRICE_TYPE_4)->save();

        $productAbstractEntity = SpyProductAbstractQuery::create()
            ->filterBySku(self::DUMMY_SKU_PRODUCT_ABSTRACT)
            ->findOne();

        if ($productAbstractEntity === null) {
            $productAbstractEntity = new SpyProductAbstract();
        }

        $productAbstractEntity->setSku(self::DUMMY_SKU_PRODUCT_ABSTRACT)
            ->setAttributes('{}')
            ->save();

        $productConcreteEntity = SpyProductQuery::create()
            ->filterBySku(self::DUMMY_SKU_PRODUCT_CONCRETE)
            ->findOne();

        if ($productConcreteEntity === null) {
            $productConcreteEntity = new SpyProduct();
        }
        $productConcreteEntity->setSku(self::DUMMY_SKU_PRODUCT_CONCRETE)
            ->setSpyProductAbstract($productAbstractEntity)
            ->setAttributes('{}')
            ->save();

        $this->deletePriceEntitiesConcrete($productConcreteEntity);
        $productConcreteEntity->setSku(self::DUMMY_SKU_PRODUCT_CONCRETE)
            ->setSpyProductAbstract($productAbstractEntity)
            ->setAttributes('{}')
            ->save();

        $this->deletePriceEntitiesAbstract($productAbstractEntity);
        (new SpyPriceProduct())
            ->setSpyProductAbstract($productAbstractEntity)
            ->setPriceType($priceTypeEntity1)
            ->setPrice(100)
            ->save();

        (new SpyPriceProduct())
            ->setProduct($productConcreteEntity)
            ->setPriceType($priceTypeEntity2)
            ->setPrice(999)
            ->save();

        (new SpyPriceProduct())
            ->setSpyProductAbstract($productAbstractEntity)
            ->setPriceType($priceTypeEntity3)
            ->setPrice(100)
            ->save();

        (new SpyPriceProduct())
            ->setProduct($productConcreteEntity)
            ->setPriceType($priceTypeEntity3)
            ->setPrice(120)
            ->save();
    }

}
