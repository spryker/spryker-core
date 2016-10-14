<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Price;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\PriceProductTransfer;
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
 * @group WriterTest
 */
class WriterTest extends Test
{

    const PRICE_TYPE_1 = 'TYPE1';
    const PRICE_TYPE_2 = 'TYPE2';
    const SKU_PRODUCT_ABSTRACT = 'ABSTRACT';
    const SKU_PRODUCT_CONCRETE = 'CONCRETE';
    const PRICE_VALUE_1 = 99;
    const PRICE_VALUE_2 = 100;

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
    public function testCreatePriceType()
    {
        $priceTypeEntity = $this->priceFacade->createPriceType(self::PRICE_TYPE_1);
        $priceTypeQuery = SpyPriceTypeQuery::create()->filterByName($priceTypeEntity->getName())->findOne();

        $this->assertNotEmpty($priceTypeQuery);
    }

    /**
     * @return void
     */
    public function testCreatePriceForProductAbstract()
    {
        $productAbstract = SpyProductAbstractQuery::create()->filterBySku(self::SKU_PRODUCT_ABSTRACT)->findOne();
        $priceType1 = SpyPriceTypeQuery::create()->filterByName(self::PRICE_TYPE_1)->findOne();

        $request = SpyPriceProductQuery::create()->filterBySpyProductAbstract($productAbstract)->find();
        $this->assertEquals(0, count($request));

        $transferPriceProduct = $this->generateTransferPriceForProductAbstract(
            self::SKU_PRODUCT_ABSTRACT,
            self::PRICE_TYPE_1
        );

        $this->priceFacade->createPriceForProduct($transferPriceProduct);

        $request = $this->findPriceEntitiesProductAbstract($productAbstract, $priceType1);
        $this->assertEquals(1, count($request));
    }

    /**
     * @return void
     */
    public function testCreatePriceForProductConcrete()
    {
        $productConcrete = SpyProductQuery::create()->filterBySku(self::SKU_PRODUCT_CONCRETE)->findOne();
        $priceType2 = SpyPriceTypeQuery::create()->filterByName(self::PRICE_TYPE_2)->findOne();

        $request = SpyPriceProductQuery::create()->filterByProduct($productConcrete)->find();
        $this->assertEquals(0, count($request));

        $transferPriceProduct = $this->generateTransferPriceForProductConcrete(
            self::SKU_PRODUCT_CONCRETE,
            self::SKU_PRODUCT_ABSTRACT,
            self::PRICE_TYPE_2
        );
        $this->priceFacade->createPriceForProduct($transferPriceProduct);

        $request = $this->findPriceEntitiesProductConcrete($productConcrete, $priceType2);
        $this->assertEquals(1, count($request));
    }

    /**
     * @return void
     */
    public function testSetPriceForExistingProductAbstractShouldChangePrice()
    {
        $productAbstract = SpyProductAbstractQuery::create()->filterBySku(self::SKU_PRODUCT_ABSTRACT)->findOne();

        $request = SpyPriceProductQuery::create()->filterBySpyProductAbstract($productAbstract)->find();
        $this->assertEquals(0, count($request));

        $this->deletePriceEntitiesAbstract($productAbstract);
        $transferPriceProduct = $this->generateTransferPriceForProductAbstract(
            self::SKU_PRODUCT_ABSTRACT,
            self::PRICE_TYPE_1
        );

        $this->priceFacade->createPriceForProduct($transferPriceProduct);
        $request = SpyPriceProductQuery::create()
            ->filterBySpyProductAbstract($productAbstract)
            ->findOne();

        $transferPriceProduct->setPrice(self::PRICE_VALUE_2);

        $transferPriceProduct->setIdPriceProduct($request->getIdPriceProduct());
        $this->priceFacade->setPriceForProduct($transferPriceProduct);

        $request = SpyPriceProductQuery::create()
            ->filterBySpyProductAbstract($productAbstract)
            ->findOne();

        $this->assertEquals(self::PRICE_VALUE_2, $request->getPrice());
    }

    /**
     * @param string $sku
     * @param string $abstractSku
     * @param string $priceType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function generateTransferPriceForProductConcrete($sku, $abstractSku, $priceType)
    {
        $transferPriceProduct = new PriceProductTransfer();
        $transferPriceProduct
            ->setPrice(self::PRICE_VALUE_2)
            ->setSkuProduct($sku)
            ->setSkuProductAbstract($abstractSku)
            ->setPriceTypeName($priceType);

        return $transferPriceProduct;
    }

    /**
     * @param string $abstractSku
     * @param string $priceType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function generateTransferPriceForProductAbstract($abstractSku, $priceType)
    {
        $transferPriceProduct = new PriceProductTransfer();
        $transferPriceProduct
            ->setPrice(self::PRICE_VALUE_2)
            ->setSkuProductAbstract($abstractSku)
            ->setPriceTypeName($priceType);

        return $transferPriceProduct;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstract
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProduct[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findPriceEntitiesProductAbstract($productAbstract, $priceType)
    {
        return SpyPriceProductQuery::create()
            ->filterBySpyProductAbstract($productAbstract)
            ->filterByPriceType($priceType)
            ->find();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productConcrete
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProduct[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findPriceEntitiesProductConcrete($productConcrete, $priceType)
    {
        return SpyPriceProductQuery::create()
            ->filterByProduct($productConcrete)
            ->filterByPriceType($priceType)
            ->find();
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
    protected function setTestData()
    {
        $priceType1 = SpyPriceTypeQuery::create()->filterByName(self::PRICE_TYPE_1)->findOneOrCreate();
        $priceType1->setName(self::PRICE_TYPE_1)->save();

        $priceType2 = SpyPriceTypeQuery::create()->filterByName(self::PRICE_TYPE_2)->findOneOrCreate();
        $priceType2->setName(self::PRICE_TYPE_2)->save();

        $productAbstract = SpyProductAbstractQuery::create()
            ->filterBySku(self::SKU_PRODUCT_ABSTRACT)
            ->findOne();

        if ($productAbstract === null) {
            $productAbstract = new SpyProductAbstract();
        }

        $productAbstract->setSku(self::SKU_PRODUCT_ABSTRACT)
            ->setAttributes('{}')
            ->save();

        $productConcrete = SpyProductQuery::create()
            ->filterBySku(self::SKU_PRODUCT_CONCRETE)
            ->findOne();

        if ($productConcrete === null) {
            $productConcrete = new SpyProduct();
        }
        $productConcrete->setSku(self::SKU_PRODUCT_CONCRETE)
            ->setAttributes('{}')
            ->setSpyProductAbstract($productAbstract)
            ->save();

        $this->deletePriceEntitiesConcrete($productConcrete);
        $productConcrete->setSku(self::SKU_PRODUCT_CONCRETE)
            ->setAttributes('{}')
            ->setSpyProductAbstract($productAbstract)
            ->save();

        $this->deletePriceEntitiesAbstract($productAbstract);
    }

    /**
     * @return void
     */
    public function testPersistAbstractPriceShouldCreateNewPrice()
    {
        $productAbstract = SpyProductAbstractQuery::create()->filterBySku(self::SKU_PRODUCT_ABSTRACT)->findOne();
        if ($productAbstract === null) {
            $productAbstract = new SpyProductAbstract();
            $productAbstract->save();
        }

        $existingPrice = SpyPriceProductQuery::create()
            ->filterByFkProductAbstract($productAbstract->getIdProductAbstract())
            ->findOne();

        $this->assertNull($existingPrice);

        $transferPriceProduct = $this->generateTransferPriceForProductAbstract(
            self::SKU_PRODUCT_ABSTRACT,
            self::PRICE_TYPE_1
        );

        $transferPriceProduct->setIdProductAbstract($productAbstract->getIdProductAbstract());

        $this->priceFacade->persistProductAbstractPrice($transferPriceProduct);

        $priceEntity = SpyPriceProductQuery::create()
            ->filterByFkProductAbstract($productAbstract->getIdProductAbstract())
            ->findOne();

        $this->assertEquals(self::PRICE_VALUE_2, $priceEntity->getPrice());
        $this->assertEquals($productAbstract->getIdProductAbstract(), $priceEntity->getFkProductAbstract());
        $this->assertNull($priceEntity->getFkProduct());
    }

    /**
     * @return void
     */
    public function testPersistAbstractPriceShouldUpdatePrice()
    {
        $productAbstract = SpyProductAbstractQuery::create()->filterBySku(self::SKU_PRODUCT_ABSTRACT)->findOne();
        if ($productAbstract === null) {
            $productAbstract = new SpyProductAbstract();
            $productAbstract->save();
        }

        $priceType1 = SpyPriceTypeQuery::create()->filterByName(self::PRICE_TYPE_1)->findOneOrCreate();
        $priceType1->save();

        $existingPrice = new SpyPriceProduct();
        $existingPrice->setFkProductAbstract($productAbstract->getIdProductAbstract());
        $existingPrice->setPrice(self::PRICE_VALUE_1);
        $existingPrice->setPriceType($priceType1);
        $existingPrice->save();

        $this->assertNotNull($existingPrice);
        $this->assertNotNull($existingPrice->getIdPriceProduct());
        $this->assertEquals($existingPrice->getPrice(), self::PRICE_VALUE_1);

        $transferPriceProduct = new PriceProductTransfer();
        $transferPriceProduct
            ->setPrice(self::PRICE_VALUE_2)
            ->setPriceTypeName($existingPrice->getPriceType()->getName())
            ->setIdProductAbstract($productAbstract->getIdProductAbstract());

        $idPriceProduct = $this->priceFacade->persistProductAbstractPrice($transferPriceProduct);

        $priceEntity = SpyPriceProductQuery::create()
            ->filterByFkProductAbstract($productAbstract->getIdProductAbstract())
            ->filterByFkPriceType($priceType1->getIdPriceType())
            ->findOne();

        $this->assertEquals($idPriceProduct, $existingPrice->getIdPriceProduct(), 'Product price ID mismatch!');
        $this->assertEquals(self::PRICE_VALUE_2, $priceEntity->getPrice(), 'Price value mismatch!');
        $this->assertEquals($productAbstract->getIdProductAbstract(), $priceEntity->getFkProductAbstract(), 'Abstract product ID mismatch!');
        $this->assertNull($priceEntity->getFkProduct(), 'Missing product ID!');
    }

    /**
     * @return void
     */
    public function testPersistConcretePriceShouldCreateNewPrice()
    {
        $productConcrete = SpyProductQuery::create()->filterBySku(self::SKU_PRODUCT_CONCRETE)->findOne();
        if ($productConcrete === null) {
            $productConcrete = new SpyProduct();
            $productConcrete->save();
        }

        $existingPrice = SpyPriceProductQuery::create()
            ->filterByFkProduct($productConcrete->getIdProduct())
            ->findOne();

        $this->assertNull($existingPrice);

        $transferPriceProduct = new PriceProductTransfer();
        $transferPriceProduct
            ->setPrice(self::PRICE_VALUE_2)
            ->setPriceTypeName(self::PRICE_TYPE_1)
            ->setIdProduct($productConcrete->getIdProduct());

        $this->priceFacade->persistProductConcretePrice($transferPriceProduct);

        $priceEntity = SpyPriceProductQuery::create()
            ->filterByFkProduct($productConcrete->getIdProduct())
            ->findOne();

        $this->assertEquals(self::PRICE_VALUE_2, $priceEntity->getPrice());
        $this->assertEquals($productConcrete->getIdProduct(), $priceEntity->getFkProduct());
        $this->assertNull($priceEntity->getFkProductAbstract());
    }

}
