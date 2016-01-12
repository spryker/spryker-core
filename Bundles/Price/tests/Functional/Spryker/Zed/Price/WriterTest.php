<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Price;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Price\Business\PriceFacade;
use Generated\Zed\Ide\AutoCompletion;
use Orm\Zed\Price\Persistence\SpyPriceProductQuery;
use Orm\Zed\Price\Persistence\SpyPriceTypeQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductQuery;

/**
 * @group PriceTest
 */
class WriterTest extends Test
{

    const DUMMY_PRICE_TYPE_1 = 'TYPE1';
    const DUMMY_PRICE_TYPE_2 = 'TYPE2';
    const DUMMY_SKU_PRODUCT_ABSTRACT = 'ABSTRACT';
    const DUMMY_SKU_CONCRETE_PRODUCT = 'CONCRETE';
    const DUMMY_NEW_PRICE_1 = 99;
    const DUMMY_NEW_PRICE_2 = 100;

    /**
     * @var PriceFacade
     */
    private $priceFacade;

    /**
     * @var AutoCompletion
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
        $priceTypeEntity = $this->priceFacade->createPriceType(self::DUMMY_PRICE_TYPE_1);
        $priceTypeQuery = SpyPriceTypeQuery::create()->filterByName($priceTypeEntity->getName())->findOne();

        $this->assertNotEmpty($priceTypeQuery);
    }

    /**
     * @return void
     */
    public function testCreatePriceForProductAbstract()
    {
        $productAbstract = SpyProductAbstractQuery::create()->filterBySku(self::DUMMY_SKU_PRODUCT_ABSTRACT)->findOne();
        $priceType1 = SpyPriceTypeQuery::create()->filterByName(self::DUMMY_PRICE_TYPE_1)->findOne();

        $request = SpyPriceProductQuery::create()->filterBySpyProductAbstract($productAbstract)->find();
        $this->assertEquals(0, count($request));

        $transferPriceProduct = $this->setTransferPriceProductAbstract(
            self::DUMMY_SKU_PRODUCT_ABSTRACT,
            self::DUMMY_PRICE_TYPE_1
        );

        $this->priceFacade->createPriceForProduct($transferPriceProduct);

        $request = $this->findPriceEntitiesProductAbstract($productAbstract, $priceType1);
        $this->assertEquals(1, count($request));
    }

    /**
     * @return void
     */
    public function testCreatePriceForConcreteProduct()
    {
        $concreteProduct = SpyProductQuery::create()->filterBySku(self::DUMMY_SKU_CONCRETE_PRODUCT)->findOne();
        $priceType2 = SpyPriceTypeQuery::create()->filterByName(self::DUMMY_PRICE_TYPE_2)->findOne();

        $request = SpyPriceProductQuery::create()->filterByProduct($concreteProduct)->find();
        $this->assertEquals(0, count($request));

        $transferPriceProduct = $this->setTransferPriceProduct(
            self::DUMMY_SKU_CONCRETE_PRODUCT,
            self::DUMMY_SKU_PRODUCT_ABSTRACT,
            self::DUMMY_PRICE_TYPE_2
        );
        $this->priceFacade->createPriceForProduct($transferPriceProduct);

        $request = $this->findPriceEntitiesConcreteProduct($concreteProduct, $priceType2);
        $this->assertEquals(1, count($request));
    }

    /**
     * @return void
     */
    public function testSetPriceForExistingProductAbstractShouldChangePrice()
    {
        $productAbstract = SpyProductAbstractQuery::create()->filterBySku(self::DUMMY_SKU_PRODUCT_ABSTRACT)->findOne();

        $request = SpyPriceProductQuery::create()->filterBySpyProductAbstract($productAbstract)->find();
        $this->assertEquals(0, count($request));

        $this->deletePriceEntitiesAbstract($productAbstract);
        $transferPriceProduct = $this->setTransferPriceProductAbstract(
            self::DUMMY_SKU_PRODUCT_ABSTRACT,
            self::DUMMY_PRICE_TYPE_1
        );

        $this->priceFacade->createPriceForProduct($transferPriceProduct);
        $request = SpyPriceProductQuery::create()
            ->filterBySpyProductAbstract($productAbstract)
            ->findOne();

        $transferPriceProduct->setPrice(self::DUMMY_NEW_PRICE_2);

        $transferPriceProduct->setIdPriceProduct($request->getIdPriceProduct());
        $this->priceFacade->setPriceForProduct($transferPriceProduct);

        $request = SpyPriceProductQuery::create()
            ->filterBySpyProductAbstract($productAbstract)
            ->findOne();

        $this->assertEquals(self::DUMMY_NEW_PRICE_2, $request->getPrice());
    }

    protected function setTransferPriceProduct($sku, $abstractSku, $priceType)
    {
        $transferPriceProduct = new PriceProductTransfer();
        $transferPriceProduct
            ->setPrice(100)
            ->setSkuProduct($sku)
            ->setSkuProductAbstract($abstractSku)
            ->setPriceTypeName($priceType);

        return $transferPriceProduct;
    }

    protected function setTransferPriceProductAbstract($abstractSku, $priceType)
    {
        $transferPriceProduct = new PriceProductTransfer();
        $transferPriceProduct
            ->setPrice(100)
            ->setSkuProductAbstract($abstractSku)
            ->setPriceTypeName($priceType);

        return $transferPriceProduct;
    }

    protected function findPriceEntitiesProductAbstract($productAbstract, $priceType)
    {
        return SpyPriceProductQuery::create()
            ->filterBySpyProductAbstract($productAbstract)
            ->filterByPriceType($priceType)
            ->find();
    }

    protected function findPriceEntitiesConcreteProduct($concreteProduct, $priceType)
    {
        return SpyPriceProductQuery::create()
            ->filterByProduct($concreteProduct)
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

        $concreteProduct = SpyProductQuery::create()
            ->filterBySku(self::DUMMY_SKU_CONCRETE_PRODUCT)
            ->findOne();

        if ($concreteProduct === null) {
            $concreteProduct = new SpyProduct();
        }
        $concreteProduct->setSku(self::DUMMY_SKU_CONCRETE_PRODUCT)
            ->setAttributes('{}')
            ->setSpyProductAbstract($productAbstract)
            ->save();

        $this->deletePriceEntitiesConcrete($concreteProduct);
        $concreteProduct->setSku(self::DUMMY_SKU_CONCRETE_PRODUCT)
            ->setAttributes('{}')
            ->setSpyProductAbstract($productAbstract)
            ->save();

        $this->deletePriceEntitiesAbstract($productAbstract);
    }

}
