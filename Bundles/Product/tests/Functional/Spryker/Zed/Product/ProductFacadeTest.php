<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Product;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Spryker\Zed\Url\Business\UrlFacade;

/**
 * @group Spryker
 * @group Zed
 * @group Business
 * @group ProductFacadeTest
 */
class ProductFacadeTest extends Test
{

    const SKU_PRODUCT_ABSTRACT = 'Abstract product sku';

    const SKU_CONCRETE_PRODUCT = 'Concrete product sku';

    const TAX_SET_NAME = 'Sales Tax';

    const TAX_RATE_NAME = 'VAT';

    const TAX_RATE_PERCENTAGE = 10;

    const CONCRETE_PRODUCT_NAME = 'Concrete product name';

    /**
     * @var ProductFacade
     */
    protected $productFacade;

    /**
     * @var LocaleFacade
     */
    protected $localeFacade;

    /**
     * @var UrlFacade
     */
    protected $urlFacade;

    /**
     * @var ProductQueryContainer
     */
    protected $productQueryContainer;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->localeFacade = new LocaleFacade();
        $this->productFacade = new ProductFacade();
        $this->urlFacade = new UrlFacade();
        $this->productQueryContainer = new ProductQueryContainer();
    }

    /**
     * @group Product
     *
     * @return void
     */
    public function testCreateAttributeTypeCreatesAndReturnsId()
    {
        $attributeTypeQuery = $this->productQueryContainer->queryAttributeTypeByName('AnAttributeType');
        $this->assertEquals(0, $attributeTypeQuery->count());

        $idAttributeType = $this->productFacade->createAttributeType('AnAttributeType', 'input');

        $this->assertEquals(1, $attributeTypeQuery->count());
        $this->assertEquals($idAttributeType, $attributeTypeQuery->findOne()->getIdProductAttributeType());
    }

    /**
     * @group Product
     *
     * @return void
     */
    public function testCreateAttributeCreatesAndReturnsId()
    {
        $this->productFacade->createAttributeType('AnAttributeType', 'input');
        $attributeQuery = $this->productQueryContainer->queryAttributeByName('ANonExistentAttribute');
        $this->assertEquals(0, $attributeQuery->count());

        $idAttribute = $this->productFacade->createAttribute('ANonExistentAttribute', 'AnAttributeType');

        $this->assertEquals(1, $attributeQuery->count());
        $this->assertEquals($idAttribute, $attributeQuery->findOne()->getIdProductAttributesMetadata());
    }

    /**
     * @group Product
     *
     * @return void
     */
    public function testHasAttributeTypeReturnsRightValue()
    {
        $this->assertFalse($this->productFacade->hasAttributeType('AnAttributeType'));
        $this->productFacade->createAttributeType('AnAttributeType', 'input');
        $this->assertTrue($this->productFacade->hasAttributeType('AnAttributeType'));
    }

    /**
     * @group Product
     *
     * @return void
     */
    public function testHasAttributeReturnsRightValue()
    {
        $this->assertFalse($this->productFacade->hasAttribute('AnAttribute'));

        $this->productFacade->createAttributeType('AnAttributeType', 'input');
        $this->productFacade->createAttribute('AnAttribute', 'AnAttributeType');

        $this->assertTrue($this->productFacade->hasAttribute('AnAttribute'));
    }

    /**
     * @group Product
     *
     * @return void
     */
    public function testCreateProductAbstractCreatesAndReturnsId()
    {
        $productAbstractQuery = $this->productQueryContainer->queryProductAbstractBySku('AnProductAbstractSku');

        $this->assertEquals(0, $productAbstractQuery->count());

        $productAbstract = new ProductAbstractTransfer();
        $productAbstract->setSku('AnProductAbstractSku');
        $productAbstract->setAttributes([]);
        $productAbstract->setLocalizedAttributes(new LocalizedAttributesTransfer());

        $idProductAbstract = $this->productFacade->createProductAbstract($productAbstract);

        $this->assertEquals(1, $productAbstractQuery->count());
        $this->assertEquals($idProductAbstract, $productAbstractQuery->findOne()->getIdProductAbstract());
    }

    /**
     * @group Product
     *
     * @return void
     */
    public function testGetEffectiveTaxRateReturnsInteger()
    {
        $concreteProductQuery = $this->productQueryContainer->queryConcreteProductBySku('AConcreteProductSku');

        $this->assertEquals(0, $concreteProductQuery->count());

        $productAbstract = new ProductAbstractTransfer();
        $productAbstract->setSku('AnProductAbstractSku');
        $productAbstract->setAttributes([]);
        $productAbstract->setLocalizedAttributes(new LocalizedAttributesTransfer());

        $idProductAbstract = $this->productFacade->createProductAbstract($productAbstract);

        $concreteProduct = new ConcreteProductTransfer();
        $concreteProduct->setSku('AConcreteProductSku');
        $concreteProduct->setAttributes([]);
        $concreteProduct->setLocalizedAttributes(new LocalizedAttributesTransfer());
        $concreteProduct->setIsActive(true);

        $this->productFacade->createConcreteProduct($concreteProduct, $idProductAbstract);

        $effectiveTaxRate = $this->productFacade->getEffectiveTaxRateForConcreteProduct('AConcreteProductSku');

        $this->assertInternalType('integer', $effectiveTaxRate);
    }

    /**
     * @group Product
     *
     * @return void
     */
    public function testHasProductAbstractReturnsRightValue()
    {
        $this->assertFalse($this->productFacade->hasProductAbstract('AProductSku'));

        $productAbstract = new ProductAbstractTransfer();
        $productAbstract->setSku('AProductSku');
        $productAbstract->setAttributes([]);
        $productAbstract->setLocalizedAttributes(new LocalizedAttributesTransfer());

        $this->productFacade->createProductAbstract($productAbstract);

        $this->assertTrue($this->productFacade->hasProductAbstract('AProductSku'));
    }

    /**
     * @group Product
     *
     * @return void
     */
    public function testCreateConcreteProductCreatesAndReturnsId()
    {
        $concreteProductQuery = $this->productQueryContainer->queryConcreteProductBySku('AConcreteProductSku');

        $this->assertEquals(0, $concreteProductQuery->count());

        $productAbstract = new ProductAbstractTransfer();
        $productAbstract->setSku('AnProductAbstractSku');
        $productAbstract->setAttributes([]);
        $productAbstract->setLocalizedAttributes(new LocalizedAttributesTransfer());
        $idProductAbstract = $this->productFacade->createProductAbstract($productAbstract);

        $concreteProduct = new ConcreteProductTransfer();
        $concreteProduct->setSku('AConcreteProductSku');
        $concreteProduct->setAttributes([]);
        $concreteProduct->setLocalizedAttributes(new LocalizedAttributesTransfer());
        $concreteProduct->setIsActive(true);
        $idConcreteProduct = $this->productFacade->createConcreteProduct($concreteProduct, $idProductAbstract);

        $this->assertEquals(1, $concreteProductQuery->count());
        $this->assertEquals($idConcreteProduct, $concreteProductQuery->findOne()->getIdProduct());
    }

    /**
     * @group Product
     *
     * @return void
     */
    public function testHasConcreteProductReturnsRightValue()
    {
        $this->assertFalse($this->productFacade->hasConcreteProduct('AConcreteProductSku'));

        $productAbstract = new ProductAbstractTransfer();
        $productAbstract->setSku('AnProductAbstractSku');
        $productAbstract->setAttributes([]);
        $productAbstract->setLocalizedAttributes(new LocalizedAttributesTransfer());

        $idProductAbstract = $this->productFacade->createProductAbstract($productAbstract);

        $concreteProduct = new ConcreteProductTransfer();
        $concreteProduct->setSku('AConcreteProductSku');
        $concreteProduct->setAttributes([]);
        $concreteProduct->setLocalizedAttributes(new LocalizedAttributesTransfer());
        $concreteProduct->setIsActive(true);
        $this->productFacade->createConcreteProduct($concreteProduct, $idProductAbstract);

        $this->assertTrue($this->productFacade->hasConcreteProduct('AConcreteProductSku'));
    }

    /**
     * @group Product
     *
     * @return void
     */
    public function testCreateProductUrlCreatesAndReturnsCorrectUrl()
    {
        $urlString = '/someUrl';
        $locale = $this->localeFacade->createLocale('ABCDE');

        $productAbstract = new ProductAbstractTransfer();
        $productAbstract->setSku('AnProductAbstractSku');
        $productAbstract->setAttributes([]);
        $productAbstract->setLocalizedAttributes(new LocalizedAttributesTransfer());
        $idProductAbstract = $this->productFacade->createProductAbstract($productAbstract);
        $url = $this->productFacade->createProductUrl('AnProductAbstractSku', $urlString, $locale);

        $this->assertTrue($this->urlFacade->hasUrl($urlString));

        $this->assertEquals($urlString, $url->getUrl());
        $this->assertEquals($idProductAbstract, $url->getFkProductAbstract());
        $this->assertEquals($idProductAbstract, $url->getResourceId());
        $this->assertEquals('product_abstract', $url->getResourceType());
        $this->assertEquals($locale->getIdLocale(), $url->getFkLocale());
    }

    /**
     * @group Product
     *
     * @return void
     */
    public function testGetAbstractSkuFromConcreteProduct()
    {
        $this->assertFalse($this->productFacade->hasConcreteProduct(self::SKU_CONCRETE_PRODUCT));

        $productAbstract = new ProductAbstractTransfer();
        $productAbstract->setSku(self::SKU_PRODUCT_ABSTRACT);
        $productAbstract->setAttributes([]);
        $productAbstract->setLocalizedAttributes(new LocalizedAttributesTransfer());

        $idProductAbstract = $this->productFacade->createProductAbstract($productAbstract);

        $concreteProduct = new ConcreteProductTransfer();
        $concreteProduct->setSku(self::SKU_CONCRETE_PRODUCT);
        $concreteProduct->setAttributes([]);
        $concreteProduct->setLocalizedAttributes(new LocalizedAttributesTransfer());
        $concreteProduct->setIsActive(true);
        $this->productFacade->createConcreteProduct($concreteProduct, $idProductAbstract);

        $this->assertTrue($this->productFacade->hasConcreteProduct(self::SKU_CONCRETE_PRODUCT));

        $this->assertEquals($this->productFacade->getAbstractSkuFromConcreteProduct(self::SKU_CONCRETE_PRODUCT), self::SKU_PRODUCT_ABSTRACT);
    }

    /**
     * @group Product
     *
     * @return void
     */
    public function testGetConcreteProduct()
    {
        $localeName = \Spryker\Shared\Kernel\Store::getInstance()->getCurrentLocale();
        $localeTransfer = $this->localeFacade->getLocale($localeName);

        $taxRateEntity = new SpyTaxRate();
        $taxRateEntity->setRate(self::TAX_RATE_PERCENTAGE)
            ->setName(self::TAX_RATE_NAME);

        $taxSetEntity = new SpyTaxSet();
        $taxSetEntity->addSpyTaxRate($taxRateEntity)
            ->setName(self::TAX_SET_NAME);

        $productAbstractEntity = new SpyProductAbstract();
        $productAbstractEntity->setSpyTaxSet($taxSetEntity)
            ->setAttributes('')
            ->setSku(self::SKU_PRODUCT_ABSTRACT);

        $localizedAttributesEntity = new SpyProductLocalizedAttributes();
        $localizedAttributesEntity->setName(self::CONCRETE_PRODUCT_NAME)
            ->setAttributes('')
            ->setFkLocale($localeTransfer->getIdLocale());

        $concreteProductEntity = new SpyProduct();
        $concreteProductEntity->setSpyProductAbstract($productAbstractEntity)
            ->setAttributes('')
            ->addSpyProductLocalizedAttributes($localizedAttributesEntity)
            ->setSku(self::SKU_CONCRETE_PRODUCT)
            ->save();

        $concreteProductTransfer = $this->productFacade->getConcreteProduct($concreteProductEntity->getSku());
        $this->assertEquals(self::CONCRETE_PRODUCT_NAME, $concreteProductTransfer->getName());
        $this->assertEquals(self::SKU_CONCRETE_PRODUCT, $concreteProductTransfer->getSku());
        $this->assertEquals(self::SKU_PRODUCT_ABSTRACT, $concreteProductTransfer->getProductAbstractSku());
        $this->assertEquals($concreteProductEntity->getIdProduct(), $concreteProductTransfer->getIdConcreteProduct());
        $this->assertEquals($productAbstractEntity->getIdProductAbstract(), $concreteProductTransfer->getIdProductAbstract());

        $taxSetTransfer = $concreteProductTransfer->getTaxSet();
        $this->assertEquals(self::TAX_SET_NAME, $taxSetTransfer->getName());

        $this->assertNotEmpty($taxSetTransfer->getTaxRates());
        $taxRateTransfer = $taxSetTransfer->getTaxRates()[0];
        $this->assertEquals(self::TAX_RATE_NAME, $taxRateTransfer->getName());
        $this->assertEquals(self::TAX_RATE_PERCENTAGE, $taxRateTransfer->getRate());
    }

}
