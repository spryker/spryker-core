<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProduct;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\PriceProduct\Business\PriceProductFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Model
 * @group WriterTest
 * Add your own group annotations below this line
 */
class WriterTest extends Unit
{
    const PRICE_TYPE_1 = 'TYPE1';
    const PRICE_TYPE_2 = 'TYPE2';
    const SKU_PRODUCT_ABSTRACT = 'ABSTRACT';
    const SKU_PRODUCT_CONCRETE = 'CONCRETE';
    const PRICE_VALUE_1 = 99;
    const PRICE_VALUE_2 = 100;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\PriceProductFacade
     */
    private $priceFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->priceFacade = new PriceProductFacade();

        $this->setTestData();
    }

    /**
     * @return void
     */
    public function testCreatePriceType()
    {
        $idPriceType = $this->priceFacade->createPriceType(self::PRICE_TYPE_1);
        $priceTypeEntity = SpyPriceTypeQuery::create()
            ->filterByName(self::PRICE_TYPE_1)
            ->findOne();

        $this->assertEquals($idPriceType, $priceTypeEntity->getIdPriceType());
    }

    /**
     * @return void
     */
    public function testCreatePriceForProductAbstract()
    {
        $productAbstractEntity = SpyProductAbstractQuery::create()->filterBySku(self::SKU_PRODUCT_ABSTRACT)->findOne();
        $priceTypeEntity1 = SpyPriceTypeQuery::create()->filterByName(self::PRICE_TYPE_1)->findOne();

        $productPriceEntity = SpyPriceProductQuery::create()->filterBySpyProductAbstract($productAbstractEntity)->find();
        $this->assertEquals(0, count($productPriceEntity));

        $priceProductTransfer = $this->generateTransferPriceForProductAbstract(
            self::SKU_PRODUCT_ABSTRACT,
            self::PRICE_TYPE_1
        );

        $this->priceFacade->createPriceForProduct($priceProductTransfer);

        $productPriceEntity = $this->findPriceEntitiesProductAbstract($productAbstractEntity, $priceTypeEntity1);
        $this->assertEquals(1, count($productPriceEntity));
    }

    /**
     * @return void
     */
    public function testCreatePriceForProductConcrete()
    {
        $productConcreteEntity = SpyProductQuery::create()->filterBySku(self::SKU_PRODUCT_CONCRETE)->findOne();
        $priceTypeEntity2 = SpyPriceTypeQuery::create()->filterByName(self::PRICE_TYPE_2)->findOne();

        $productPriceEntity = SpyPriceProductQuery::create()->filterByProduct($productConcreteEntity)->find();
        $this->assertEquals(0, count($productPriceEntity));

        $productPriceTransfer = $this->generateTransferPriceForProductConcrete(
            self::SKU_PRODUCT_CONCRETE,
            self::SKU_PRODUCT_ABSTRACT,
            self::PRICE_TYPE_2
        );
        $this->priceFacade->createPriceForProduct($productPriceTransfer);

        $productPriceEntity = $this->findPriceEntitiesProductConcrete($productConcreteEntity, $priceTypeEntity2);
        $this->assertEquals(1, count($productPriceEntity));
    }

    /**
     * @return void
     */
    public function testSetPriceForExistingProductAbstractShouldChangePrice()
    {
        $productAbstractEntity = SpyProductAbstractQuery::create()->filterBySku(self::SKU_PRODUCT_ABSTRACT)->findOne();

        $productPriceEntity = SpyPriceProductQuery::create()->filterBySpyProductAbstract($productAbstractEntity)->find();
        $this->assertEquals(0, count($productPriceEntity));

        $this->deletePriceEntitiesAbstract($productAbstractEntity);
        $productPriceTransfer = $this->generateTransferPriceForProductAbstract(
            self::SKU_PRODUCT_ABSTRACT,
            self::PRICE_TYPE_1
        );

        $this->priceFacade->createPriceForProduct($productPriceTransfer);
        $productPriceEntity = SpyPriceProductQuery::create()
            ->filterBySpyProductAbstract($productAbstractEntity)
            ->findOne();

        $productPriceTransfer->setPrice(self::PRICE_VALUE_2);

        $productPriceTransfer->setIdPriceProduct($productPriceEntity->getIdPriceProduct());
        $this->priceFacade->setPriceForProduct($productPriceTransfer);

        $productPriceEntity = SpyPriceProductQuery::create()
            ->filterBySpyProductAbstract($productAbstractEntity)
            ->findOne();

        $this->assertEquals(self::PRICE_VALUE_2, $productPriceEntity->getPrice());
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
        $productPriceTransfer = new PriceProductTransfer();
        $productPriceTransfer
            ->setPrice(self::PRICE_VALUE_2)
            ->setSkuProduct($sku)
            ->setSkuProductAbstract($abstractSku)
            ->setPriceTypeName($priceType);

        return $productPriceTransfer;
    }

    /**
     * @param string $abstractSku
     * @param string $priceType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function generateTransferPriceForProductAbstract($abstractSku, $priceType)
    {
        $productPriceTransfer = new PriceProductTransfer();
        $productPriceTransfer
            ->setPrice(self::PRICE_VALUE_2)
            ->setSkuProductAbstract($abstractSku)
            ->setPriceTypeName($priceType);

        return $productPriceTransfer;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstract
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceType $priceType
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct[]|\Propel\Runtime\Collection\ObjectCollection
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
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceType $priceType
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findPriceEntitiesProductConcrete($productConcrete, $priceType)
    {
        return SpyPriceProductQuery::create()
            ->filterByProduct($productConcrete)
            ->filterByPriceType($priceType)
            ->find();
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
     * @return void
     */
    protected function setTestData()
    {
        $priceTypeEntity1 = SpyPriceTypeQuery::create()->filterByName(self::PRICE_TYPE_1)->findOneOrCreate();
        $priceTypeEntity1->setName(self::PRICE_TYPE_1)->save();

        $priceTypeEntity2 = SpyPriceTypeQuery::create()->filterByName(self::PRICE_TYPE_2)->findOneOrCreate();
        $priceTypeEntity2->setName(self::PRICE_TYPE_2)->save();

        $productAbstractEntity = SpyProductAbstractQuery::create()
            ->filterBySku(self::SKU_PRODUCT_ABSTRACT)
            ->findOne();

        if ($productAbstractEntity === null) {
            $productAbstractEntity = new SpyProductAbstract();
        }

        $productAbstractEntity->setSku(self::SKU_PRODUCT_ABSTRACT)
            ->setAttributes('{}')
            ->save();

        $productConcreteEntity = SpyProductQuery::create()
            ->filterBySku(self::SKU_PRODUCT_CONCRETE)
            ->findOne();

        if ($productConcreteEntity === null) {
            $productConcreteEntity = new SpyProduct();
        }
        $productConcreteEntity->setSku(self::SKU_PRODUCT_CONCRETE)
            ->setAttributes('{}')
            ->setSpyProductAbstract($productAbstractEntity)
            ->save();

        $this->deletePriceEntitiesConcrete($productConcreteEntity);
        $productConcreteEntity->setSku(self::SKU_PRODUCT_CONCRETE)
            ->setAttributes('{}')
            ->setSpyProductAbstract($productAbstractEntity)
            ->save();

        $this->deletePriceEntitiesAbstract($productAbstractEntity);
    }

    /**
     * @return void
     */
    public function testPersistAbstractPriceShouldCreateNewPrice()
    {
        $productAbstractEntity = $this->createProductAbstractEntity();

        $beforePriceProductEntity = SpyPriceProductQuery::create()
            ->filterByFkProductAbstract($productAbstractEntity->getIdProductAbstract())
            ->findOne();

        $this->assertNull($beforePriceProductEntity);

        $productPriceTransfer = $this->generateTransferPriceForProductAbstract(
            self::SKU_PRODUCT_ABSTRACT,
            self::PRICE_TYPE_1
        );

        $productPriceTransfer->setIdProductAbstract($productAbstractEntity->getIdProductAbstract());

        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setIdProductAbstract($productAbstractEntity->getIdProductAbstract());
        $productAbstractTransfer->setPrice($productPriceTransfer);

        $this->priceFacade->persistProductAbstractPrice($productAbstractTransfer);

        $priceProductEntity = SpyPriceProductQuery::create()
            ->filterByFkProductAbstract($productAbstractEntity->getIdProductAbstract())
            ->findOne();

        $this->assertEquals(self::PRICE_VALUE_2, $priceProductEntity->getPrice());
        $this->assertEquals($productAbstractEntity->getIdProductAbstract(), $priceProductEntity->getFkProductAbstract());
        $this->assertNull($priceProductEntity->getFkProduct());
    }

    /**
     * @return void
     */
    public function testPersistAbstractPriceShouldUpdatePrice()
    {
        $productAbstractEntity = $this->createProductAbstractEntity();

        $priceTypeEntity = SpyPriceTypeQuery::create()->filterByName(self::PRICE_TYPE_1)->findOneOrCreate();
        $priceTypeEntity->save();

        $priceProductEntity = new SpyPriceProduct();
        $priceProductEntity->setFkProductAbstract($productAbstractEntity->getIdProductAbstract());
        $priceProductEntity->setPrice(self::PRICE_VALUE_1);
        $priceProductEntity->setPriceType($priceTypeEntity);
        $priceProductEntity->save();

        $this->assertNotNull($priceProductEntity);
        $this->assertNotNull($priceProductEntity->getIdPriceProduct());
        $this->assertEquals($priceProductEntity->getPrice(), self::PRICE_VALUE_1);

        $productPriceTransfer = new PriceProductTransfer();
        $productPriceTransfer
            ->setPrice(self::PRICE_VALUE_2)
            ->setPriceTypeName($priceProductEntity->getPriceType()->getName())
            ->setIdProductAbstract($productAbstractEntity->getIdProductAbstract());

        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setIdProductAbstract($productAbstractEntity->getIdProductAbstract());
        $productAbstractTransfer->setPrice($productPriceTransfer);

        $productAbstractTransfer = $this->priceFacade->persistProductAbstractPrice($productAbstractTransfer);

        $priceEntity = SpyPriceProductQuery::create()
            ->filterByFkProductAbstract($productAbstractEntity->getIdProductAbstract())
            ->filterByFkPriceType($priceTypeEntity->getIdPriceType())
            ->findOne();

        $this->assertEquals($productAbstractTransfer->getPrice()->getIdPriceProduct(), $priceProductEntity->getIdPriceProduct(), 'Product price ID mismatch!');
        $this->assertEquals(self::PRICE_VALUE_2, $priceEntity->getPrice(), 'Price value mismatch!');
        $this->assertEquals($productAbstractEntity->getIdProductAbstract(), $priceEntity->getFkProductAbstract(), 'Abstract product ID mismatch!');
        $this->assertNull($priceEntity->getFkProduct(), 'Missing product ID!');
    }

    /**
     * @return void
     */
    public function testPersistConcretePriceShouldCreateNewPrice()
    {
        $productConcreteEntity = SpyProductQuery::create()->filterBySku(self::SKU_PRODUCT_CONCRETE)->findOne();
        if ($productConcreteEntity === null) {
            $productConcreteEntity = new SpyProduct();
            $productConcreteEntity->save();
        }

        $productPriceEntity = SpyPriceProductQuery::create()
            ->filterByFkProduct($productConcreteEntity->getIdProduct())
            ->findOne();

        $this->assertNull($productPriceEntity);

        $productPriceTransfer = new PriceProductTransfer();
        $productPriceTransfer
            ->setPrice(self::PRICE_VALUE_2)
            ->setPriceTypeName(self::PRICE_TYPE_1)
            ->setIdProduct($productConcreteEntity->getIdProduct());

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setIdProductConcrete($productConcreteEntity->getIdProduct());
        $productConcreteTransfer->setPrice($productPriceTransfer);

        $this->priceFacade->persistProductConcretePrice($productConcreteTransfer);

        $priceEntity = SpyPriceProductQuery::create()
            ->filterByFkProduct($productConcreteEntity->getIdProduct())
            ->findOne();

        $this->assertEquals(self::PRICE_VALUE_2, $priceEntity->getPrice());
        $this->assertEquals($productConcreteEntity->getIdProduct(), $priceEntity->getFkProduct());
        $this->assertNull($priceEntity->getFkProductAbstract());
    }

    /**
     * @return void
     */
    public function testPersistProductAbstractPriceCollection()
    {
        $productAbstractEntity = $this->createProductAbstractEntity();

        $productPriceTransfer1 = new PriceProductTransfer();
        $productPriceTransfer1
            ->setPrice(self::PRICE_VALUE_1)
            ->setPriceTypeName(self::PRICE_TYPE_1);

        $productPriceTransfer2 = new PriceProductTransfer();
        $productPriceTransfer2
            ->setPrice(self::PRICE_VALUE_2)
            ->setPriceTypeName(self::PRICE_TYPE_2);

        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer
            ->setIdProductAbstract($productAbstractEntity->getIdProductAbstract())
            ->addPrices($productPriceTransfer1)
            ->addPrices($productPriceTransfer1);

        $this->priceFacade->persistProductAbstractPriceCollection($productAbstractTransfer);

        $priceEntities = SpyPriceProductQuery::create()
            ->filterByFkProductAbstract($productAbstractEntity->getIdProductAbstract())
            ->find();

        $this->assertCount(2, $priceEntities);

        $expectedPrices = [
            self::PRICE_TYPE_1 => self::PRICE_VALUE_1,
            self::PRICE_TYPE_2 => self::PRICE_VALUE_2,
        ];

        foreach ($priceEntities as $priceEntity) {
            $priceTypeName = $priceEntity->getPriceType()->getName();
            $this->assertSame($expectedPrices[$priceTypeName], $priceEntity->getPrice());
            $this->assertSame($productAbstractEntity->getIdProductAbstract(), $priceEntity->getFkProductAbstract());
            $this->assertNull($priceEntity->getFkProduct());
        }
    }

    /**
     * @return void
     */
    public function testPersistProductConcretePriceCollection()
    {
        $productConcreteEntity = SpyProductQuery::create()->filterBySku(self::SKU_PRODUCT_CONCRETE)->findOne();
        if ($productConcreteEntity === null) {
            $productConcreteEntity = new SpyProduct();
            $productConcreteEntity->save();
        }

        $productPriceTransfer1 = new PriceProductTransfer();
        $productPriceTransfer1
            ->setPrice(self::PRICE_VALUE_1)
            ->setPriceTypeName(self::PRICE_TYPE_1);

        $productPriceTransfer2 = new PriceProductTransfer();
        $productPriceTransfer2
            ->setPrice(self::PRICE_VALUE_2)
            ->setPriceTypeName(self::PRICE_TYPE_2);

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer
            ->setIdProductConcrete($productConcreteEntity->getIdProduct())
            ->addPrices($productPriceTransfer1)
            ->addPrices($productPriceTransfer2);

        $this->priceFacade->persistProductConcretePriceCollection($productConcreteTransfer);

        $priceEntities = SpyPriceProductQuery::create()
            ->filterByFkProduct($productConcreteEntity->getIdProduct())
            ->find();

        $this->assertCount(2, $priceEntities);

        $expectedPrices = [
            self::PRICE_TYPE_1 => self::PRICE_VALUE_1,
            self::PRICE_TYPE_2 => self::PRICE_VALUE_2,
        ];

        foreach ($priceEntities as $priceEntity) {
            $priceTypeName = $priceEntity->getPriceType()->getName();
            $this->assertSame($expectedPrices[$priceTypeName], $priceEntity->getPrice());
            $this->assertSame($productConcreteEntity->getIdProduct(), $priceEntity->getFkProduct());
            $this->assertNull($priceEntity->getFkProductAbstract());
        }
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function createProductAbstractEntity()
    {
        $productAbstractEntity = SpyProductAbstractQuery::create()->filterBySku(self::SKU_PRODUCT_ABSTRACT)->findOne();
        if ($productAbstractEntity === null) {
            $productAbstractEntity = new SpyProductAbstract();
            $productAbstractEntity->save();
        }

        return $productAbstractEntity;
    }
}
