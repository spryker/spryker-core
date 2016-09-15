<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Product;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;
use Spryker\Zed\Url\Business\UrlFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Product
 * @group ProductFacadeTest
 */
class ProductFacadeTest extends Test
{

    const SKU_PRODUCT_ABSTRACT = 'Product abstract sku';

    const SKU_PRODUCT_CONCRETE = 'Product concrete sku';

    const TAX_SET_NAME = 'Sales Tax';

    const TAX_RATE_NAME = 'VAT';

    const TAX_RATE_PERCENTAGE = 10;

    const PRODUCT_CONCRETE_NAME = [
        'en_US' => 'Product concrete name en_US',
        'de_DE' => 'Product concrete name de_DE',
    ];

    /**
     * @var \Spryker\Zed\Product\Business\ProductFacade
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacade
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Url\Business\UrlFacade
     */
    protected $urlFacade;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainer
     */
    protected $productQueryContainer;

    /**
     * @var \Generated\Zed\Ide\AutoCompletion
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
    public function testCreateProductAbstractCreatesAndReturnsId()
    {
        $productAbstractQuery = $this->productQueryContainer->queryProductAbstractBySku('AnProductAbstractSku');

        $this->assertEquals(0, $productAbstractQuery->count());

        $productAbstract = new ProductAbstractTransfer();
        $productAbstract->setSku('AnProductAbstractSku');
        $productAbstract->setAttributes([]);
        $productAbstract->addLocalizedAttributes($this->createLocalizedAttributesTransfer());

        $idProductAbstract = $this->productFacade->createProductAbstract($productAbstract);

        $this->assertEquals(1, $productAbstractQuery->count());
        $this->assertEquals($idProductAbstract, $productAbstractQuery->findOne()->getIdProductAbstract());
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
        $productAbstract->addLocalizedAttributes($this->createLocalizedAttributesTransfer());

        $this->productFacade->createProductAbstract($productAbstract);

        $this->assertTrue($this->productFacade->hasProductAbstract('AProductSku'));
    }

    /**
     * @group Product
     *
     * @return void
     */
    public function testCreateProductConcreteCreatesAndReturnsId()
    {
        $productConcreteQuery = $this->productQueryContainer->queryProductConcreteBySku('AProductConcreteSku');

        $this->assertEquals(0, $productConcreteQuery->count());

        $productAbstract = new ProductAbstractTransfer();
        $productAbstract->setSku('AnProductAbstractSku');
        $productAbstract->setAttributes([]);
        $productAbstract->addLocalizedAttributes($this->createLocalizedAttributesTransfer());
        $idProductAbstract = $this->productFacade->createProductAbstract($productAbstract);

        $productConcrete = new ProductConcreteTransfer();
        $productConcrete->setSku('AProductConcreteSku');
        $productConcrete->setAttributes([]);
        $productConcrete->addLocalizedAttributes($this->createLocalizedAttributesTransfer());
        $productConcrete->setIsActive(true);
        $productConcrete->setFkProductAbstract($idProductAbstract);

        $idProductConcrete = $this->productFacade->createProductConcrete($productConcrete);

        $this->assertEquals(1, $productConcreteQuery->count());
        $this->assertEquals($idProductConcrete, $productConcreteQuery->findOne()->getIdProduct());
    }

    /**
     * @group Product
     *
     * @return void
     */
    public function testHasProductConcreteReturnsRightValue()
    {
        $this->assertFalse($this->productFacade->hasProductConcrete('AProductConcreteSku'));

        $productAbstract = new ProductAbstractTransfer();
        $productAbstract->setSku('AnProductAbstractSku');
        $productAbstract->setAttributes([]);
        $productAbstract->addLocalizedAttributes($this->createLocalizedAttributesTransfer());

        $idProductAbstract = $this->productFacade->createProductAbstract($productAbstract);

        $productConcrete = new ProductConcreteTransfer();
        $productConcrete->setSku('AProductConcreteSku');
        $productConcrete->setAttributes([]);
        $productConcrete->addLocalizedAttributes($this->createLocalizedAttributesTransfer());
        $productConcrete->setIsActive(true);
        $productConcrete->setFkProductAbstract($idProductAbstract);

        $this->productFacade->createProductConcrete($productConcrete);

        $this->assertTrue($this->productFacade->hasProductConcrete('AProductConcreteSku'));
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
        $productAbstract->addLocalizedAttributes($this->createLocalizedAttributesTransfer());
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
    public function testGetAbstractSkuFromProductConcrete()
    {
        $this->assertFalse($this->productFacade->hasProductConcrete(self::SKU_PRODUCT_CONCRETE));

        $productAbstract = new ProductAbstractTransfer();
        $productAbstract->setSku(self::SKU_PRODUCT_ABSTRACT);
        $productAbstract->setAttributes([]);
        $productAbstract->addLocalizedAttributes($this->createLocalizedAttributesTransfer());

        $idProductAbstract = $this->productFacade->createProductAbstract($productAbstract);

        $productConcrete = new ProductConcreteTransfer();
        $productConcrete->setSku(self::SKU_PRODUCT_CONCRETE);
        $productConcrete->setAttributes([]);
        $productConcrete->addLocalizedAttributes($this->createLocalizedAttributesTransfer());
        $productConcrete->setIsActive(true);
        $productConcrete->setFkProductAbstract($idProductAbstract);

        $this->productFacade->createProductConcrete($productConcrete);

        $this->assertTrue($this->productFacade->hasProductConcrete(self::SKU_PRODUCT_CONCRETE));

        $this->assertEquals($this->productFacade->getAbstractSkuFromProductConcrete(self::SKU_PRODUCT_CONCRETE), self::SKU_PRODUCT_ABSTRACT);
    }

    /**
     * @group Product
     *
     * @return void
     */
    public function testGetProductConcrete()
    {
        $localeName = Store::getInstance()->getCurrentLocale();
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
        $localizedAttributesEntity->setName(
            self::PRODUCT_CONCRETE_NAME[$localeTransfer->getLocaleName()]
        )
            ->setAttributes('')
            ->setFkLocale($localeTransfer->getIdLocale());

        $productConcreteEntity = new SpyProduct();
        $productConcreteEntity->setSpyProductAbstract($productAbstractEntity)
            ->setAttributes('')
            ->addSpyProductLocalizedAttributes($localizedAttributesEntity)
            ->setSku(self::SKU_PRODUCT_CONCRETE)
            ->save();

        $productConcreteTransfer = $this->productFacade->getProductConcrete($productConcreteEntity->getSku());

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
            $this->assertEquals(
                self::PRODUCT_CONCRETE_NAME[$localeTransfer->getLocaleName()],
                $localizedAttributesTransfer->getName()
            );
        }

        $this->assertEquals(self::SKU_PRODUCT_CONCRETE, $productConcreteTransfer->getSku());
        $this->assertEquals(self::SKU_PRODUCT_ABSTRACT, $productConcreteTransfer->getAbstractSku());
        $this->assertEquals($productConcreteEntity->getIdProduct(), $productConcreteTransfer->getIdProductConcrete());
        $this->assertEquals($productAbstractEntity->getIdProductAbstract(), $productConcreteTransfer->getFkProductAbstract());
    }

    /**
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function createLocalizedAttributesTransfer()
    {
        $localeName = Store::getInstance()->getCurrentLocale();
        $localeTransfer = $this->localeFacade->getLocale($localeName);

        $localizedAttributesTransfer = new LocalizedAttributesTransfer();
        $localizedAttributesTransfer
            ->setLocale($localeTransfer)
            ->setName('Foo');

        return $localizedAttributesTransfer;
    }

}
