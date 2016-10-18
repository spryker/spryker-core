<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Product;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\LocalizedUrlTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Price\Business\PriceFacade;
use Spryker\Zed\Product\Business\Attribute\AttributeManager;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Spryker\Zed\Product\Business\Product\PluginAbstractManager;
use Spryker\Zed\Product\Business\Product\PluginConcreteManager;
use Spryker\Zed\Product\Business\Product\Sku\SkuGenerator;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\Product\Business\Product\ProductAbstractAssertion;
use Spryker\Zed\Product\Business\Product\ProductAbstractManager;
use Spryker\Zed\Product\Business\Product\ProductConcreteAssertion;
use Spryker\Zed\Product\Business\Product\ProductConcreteManager;
use Spryker\Zed\Product\Business\Product\ProductManager;
use Spryker\Zed\Product\Business\Product\ProductUrlGenerator;
use Spryker\Zed\Product\Business\Product\ProductUrlManager;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToPriceBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlBridge;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;
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

    const PRODUCT_ABSTRACT_NAME = [
        'en_US' => 'Product name en_US',
        'de_DE' => 'Product name de_DE',
    ];

    const PRODUCT_CONCRETE_NAME = [
        'en_US' => 'Product concrete name en_US',
        'de_DE' => 'Product concrete name de_DE',
    ];

    const UPDATED_PRODUCT_ABSTRACT_NAME = [
        'en_US' => 'Updated Product name en_US',
        'de_DE' => 'Updated Product name de_DE',
    ];

    const UPDATED_PRODUCT_CONCRETE_NAME = [
        'en_US' => 'Updated Product concrete name en_US',
        'de_DE' => 'Updated Product concrete name de_DE',
    ];

    const ABSTRACT_SKU = 'sku';

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected $locales;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Url\Business\UrlFacadeInterface
     */
    protected $urlFacade;

    /**
     * @var \Spryker\Zed\Touch\Business\TouchFacadeInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Price\Business\PriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductManagerInterface
     */
    protected $productManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface
     */
    protected $productAbstractManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    protected $productConcreteManager;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->setupLocales();
        $this->setupProductAbstract();
        $this->setupProductConcrete();

        $this->productFacade = new ProductFacade();
        $this->localeFacade = new LocaleFacade();
        $this->urlFacade = new UrlFacade();
        $this->priceFacade = new PriceFacade();
        $this->productQueryContainer = new ProductQueryContainer();
        $this->touchQueryContainer = new TouchQueryContainer();

        $attributeManager = new AttributeManager(
            $this->productQueryContainer
        );

        $productAbstractAssertion = new ProductAbstractAssertion(
            $this->productQueryContainer
        );

        $productConcreteAssertion = new ProductConcreteAssertion(
            $this->productQueryContainer
        );

        $productConcretePluginManager = new PluginConcreteManager(
            $beforeCreatePlugins = [],
            $afterCreatePlugins = [],
            $readPlugins = [],
            $beforeUpdatePlugins = [],
            $afterUpdatePlugins = []
        );

        $this->productConcreteManager = new ProductConcreteManager(
            $attributeManager,
            $this->productQueryContainer,
            new ProductToTouchBridge($this->touchFacade),
            new ProductToUrlBridge($this->urlFacade),
            new ProductToLocaleBridge($this->localeFacade),
            new ProductToPriceBridge($this->priceFacade),
            $productAbstractAssertion,
            $productConcreteAssertion,
            $productConcretePluginManager
        );

        $abstractPluginManager = new PluginAbstractManager(
            $beforeCreatePlugins = [],
            $afterCreatePlugins = [],
            $readPlugins = [],
            $beforeUpdatePlugins = [],
            $afterUpdatePlugins = []
        );

        $this->productAbstractManager = new ProductAbstractManager(
            $attributeManager,
            $this->productQueryContainer,
            new ProductToTouchBridge($this->touchFacade),
            new ProductToUrlBridge($this->urlFacade),
            new ProductToLocaleBridge($this->localeFacade),
            new ProductToPriceBridge($this->priceFacade),
            $this->productConcreteManager,
            $productAbstractAssertion,
            $abstractPluginManager,
            new SkuGenerator(new ProductToUrlBridge($this->urlFacade))
        );

        $urlGenerator = new ProductUrlGenerator(
            $this->productAbstractManager,
            new ProductToLocaleBridge($this->localeFacade),
            new ProductToUrlBridge($this->urlFacade)
        );

        $productUrlManager = new ProductUrlManager(
            new ProductToUrlBridge($this->urlFacade),
            new ProductToTouchBridge($this->touchFacade),
            new ProductToLocaleBridge($this->localeFacade),
            $this->productQueryContainer,
            $urlGenerator
        );

        $this->productManager = new ProductManager(
            $attributeManager,
            $this->productAbstractManager,
            $this->productConcreteManager,
            $this->productQueryContainer
        );
    }

    /**
     * @return void
     */
    protected function setupLocales()
    {
        $this->locales['de_DE'] = new LocaleTransfer();
        $this->locales['de_DE']
            ->setIdLocale(46)
            ->setIsActive(true)
            ->setLocaleName('de_DE');

        $this->locales['en_US'] = new LocaleTransfer();
        $this->locales['en_US']
            ->setIdLocale(66)
            ->setIsActive(true)
            ->setLocaleName('en_US');
    }

    /**
     * @return void
     */
    protected function setupProductAbstract()
    {
        $this->productAbstractTransfer = new ProductAbstractTransfer();
        $this->productAbstractTransfer
            ->setSku('foo');

        $localizedAttribute = new LocalizedAttributesTransfer();
        $localizedAttribute
            ->setName(self::PRODUCT_ABSTRACT_NAME['de_DE'])
            ->setLocale($this->locales['de_DE']);

        $this->productAbstractTransfer->addLocalizedAttributes($localizedAttribute);

        $localizedAttribute = new LocalizedAttributesTransfer();
        $localizedAttribute
            ->setName(self::PRODUCT_ABSTRACT_NAME['en_US'])
            ->setLocale($this->locales['en_US']);

        $this->productAbstractTransfer->addLocalizedAttributes($localizedAttribute);
    }

    /**
     * @return void
     */
    protected function setupProductConcrete()
    {
        $this->productConcreteTransfer = new ProductConcreteTransfer();
        $this->productConcreteTransfer
            ->setSku('foo-concrete');

        $localizedAttribute = new LocalizedAttributesTransfer();
        $localizedAttribute
            ->setName(self::PRODUCT_CONCRETE_NAME['de_DE'])
            ->setLocale($this->locales['de_DE']);

        $this->productConcreteTransfer->addLocalizedAttributes($localizedAttribute);

        $localizedAttribute = new LocalizedAttributesTransfer();
        $localizedAttribute
            ->setName(self::PRODUCT_CONCRETE_NAME['en_US'])
            ->setLocale($this->locales['en_US']);

        $this->productConcreteTransfer->addLocalizedAttributes($localizedAttribute);
    }

    /**
     * @return void
     */
    protected function setupDefaultProducts()
    {
        $this->productFacade->addProduct($this->productAbstractTransfer, [$this->productConcreteTransfer]);
    }

    /**
     * @return void
     */
    public function testAddProductShouldAddProduct()
    {
        $idProductAbstract = $this->productFacade->addProduct($this->productAbstractTransfer, [$this->productConcreteTransfer]);

        $this->assertTrue($idProductAbstract > 0);
        $this->assertEquals($this->productAbstractTransfer->getIdProductAbstract(), $idProductAbstract);
    }

    /**
     * @return void
     */
    public function testSaveProductShouldSaveProduct()
    {
        $this->productFacade->addProduct($this->productAbstractTransfer, [$this->productConcreteTransfer]);

        $idProductAbstract = $this->productFacade->saveProduct($this->productAbstractTransfer, [$this->productConcreteTransfer]);

        $this->assertTrue($idProductAbstract > 0);
        $this->assertEquals($this->productAbstractTransfer->getIdProductAbstract(), $idProductAbstract);
    }

    /**
     * @return void
     */
    public function testCreateProductAbstract()
    {
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);

        $this->assertTrue($idProductAbstract > 0);
        $this->assertEquals($this->productAbstractTransfer->getIdProductAbstract(), $idProductAbstract);
    }

    /**
     * @return void
     */
    public function testSaveProductAbstract()
    {
        $this->productFacade->createProductAbstract($this->productAbstractTransfer);
        $idProductAbstract = $this->productFacade->saveProductAbstract($this->productAbstractTransfer);

        $this->assertTrue($idProductAbstract > 0);
        $this->assertEquals($this->productAbstractTransfer->getIdProductAbstract(), $idProductAbstract);
    }

    /**
     * @return void
     */
    public function testHasProductAbstractShouldReturnTrue()
    {
        $this->setupDefaultProducts();

        $exists = $this->productFacade->hasProductAbstract($this->productAbstractTransfer->getSku());

        $this->assertTrue($exists);
    }

    /**
     * @return void
     */
    public function testHasProductAbstractShouldReturnFalse()
    {
        $exists = $this->productFacade->hasProductAbstract('INVALIDSKU');

        $this->assertFalse($exists);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractIdBySku()
    {
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);

        $id = $this->productFacade->getProductAbstractIdBySku($this->productAbstractTransfer->getSku());

        $this->assertEquals($idProductAbstract, $id);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractById()
    {
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);

        $productAbstract = $this->productFacade->getProductAbstractById($idProductAbstract);

        $this->assertInstanceOf(ProductAbstractTransfer::class, $productAbstract);
        $this->assertEquals($idProductAbstract, $productAbstract->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testGetAbstractSkuFromProductConcrete()
    {
        $this->setupDefaultProducts();

        $abstractSku = $this->productFacade->getAbstractSkuFromProductConcrete($this->productConcreteTransfer->getSku());

        $this->assertEquals($this->productAbstractTransfer->getSku(), $abstractSku);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractIdByConcreteSku()
    {
        $this->setupDefaultProducts();

        $idProductAbstract = $this->productFacade->getProductAbstractIdByConcreteSku($this->productConcreteTransfer->getSku());

        $this->assertEquals($this->productAbstractTransfer->getIdProductAbstract(), $idProductAbstract);
    }

    /**
     * @return void
     */
    public function testCreateProductConcrete()
    {
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);
        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);
        $idProductConcrete = $this->productFacade->createProductConcrete($this->productConcreteTransfer);

        $this->assertTrue($idProductConcrete > 0);
        $this->assertEquals($this->productConcreteTransfer->getIdProductConcrete(), $idProductConcrete);
    }

    /**
     * @return void
     */
    public function testSaveProductConcrete()
    {
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);
        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);
        $this->productFacade->createProductConcrete($this->productConcreteTransfer);

        $idProductConcrete = $this->productFacade->saveProductConcrete($this->productConcreteTransfer);

        $this->assertTrue($idProductConcrete > 0);
    }

    /**
     * @return void
     */
    public function testHasProductConcreteShouldReturnTrue()
    {
        $this->setupDefaultProducts();

        $exists = $this->productFacade->hasProductConcrete($this->productConcreteTransfer->getSku());

        $this->assertTrue($exists);
    }

    /**
     * @return void
     */
    public function testHasProductConcreteShouldReturnFalse()
    {
        $exists = $this->productFacade->hasProductConcrete('INVALIDSKU');

        $this->assertFalse($exists);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteIdBySku()
    {
        $this->setupDefaultProducts();

        $id = $this->productFacade->getProductConcreteIdBySku($this->productConcreteTransfer->getSku());

        $this->assertEquals($this->productConcreteTransfer->getIdProductConcrete(), $id);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteById()
    {
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);
        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);
        $idProductConcrete = $this->productFacade->createProductConcrete($this->productConcreteTransfer);

        $productConcrete = $this->productFacade->getProductConcreteById($idProductConcrete);

        $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcrete);
        $this->assertEquals($idProductConcrete, $productConcrete->getIdProductConcrete());
    }

    /**
     * @return void
     */
    public function testGetProductConcrete()
    {
        $this->setupDefaultProducts();

        $productConcrete = $this->productFacade->getProductConcrete($this->productConcreteTransfer->getSku());

        $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcrete);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteShouldThrowException()
    {
        $this->expectException(MissingProductException::class);
        $this->expectExceptionMessage('Tried to retrieve a product concrete with sku INVALIDSKU, but it does not exist.');

        $this->productFacade->getProductConcrete('INVALIDSKU');
    }

    /**
     * @return void
     */
    public function testGetConcreteProductsByAbstractProductId()
    {
        $this->setupDefaultProducts();

        $productConcreteCollection = $this->productFacade->getConcreteProductsByAbstractProductId($this->productAbstractTransfer->getIdProductAbstract());

        $this->assertNotEmpty((array) $productConcreteCollection);

        foreach ($productConcreteCollection as $productConcrete) {
            $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcrete);
            $this->assertEquals(
                $this->productAbstractTransfer->getIdProductAbstract(),
                $this->productConcreteTransfer->getFkProductAbstract()
            );
        }
    }

    /**
     * @return void
     */
    public function testTouchProductActive()
    {
        $this->setupDefaultProducts();

        $this->productFacade->touchProductActive($this->productAbstractTransfer->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testTouchProductInActive()
    {
        $this->setupDefaultProducts();

        $this->productFacade->touchProductInactive($this->productAbstractTransfer->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testTouchProductDeleted()
    {
        $this->setupDefaultProducts();

        $this->productFacade->touchProductDeleted($this->productAbstractTransfer->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testTouchProductConcreteActive()
    {
        $this->setupDefaultProducts();

        $this->productFacade->touchProductConcreteActive($this->productAbstractTransfer->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testTouchProductConcreteInactive()
    {
        $this->setupDefaultProducts();

        $this->productFacade->touchProductConcreteInactive($this->productAbstractTransfer->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testTouchProductConcreteDelete()
    {
        $this->setupDefaultProducts();

        $this->productFacade->touchProductConcreteDelete($this->productAbstractTransfer->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testCreateProductUrl()
    {
        $this->setupDefaultProducts();

        $productUrl = $this->productFacade->createProductUrl($this->productAbstractTransfer);

        $this->assertInstanceOf(ProductUrlTransfer::class, $productUrl);
    }

    /**
     * @return void
     */
    public function testUpdateProductUrl()
    {
        $this->setupDefaultProducts();

        $productUrl = $this->productFacade->updateProductUrl($this->productAbstractTransfer);

        $this->assertInstanceOf(ProductUrlTransfer::class, $productUrl);
    }

    /**
     * @return void
     */
    public function testGetProductUrl()
    {
        $this->setupDefaultProducts();

        $productUrl = $this->productFacade->getProductUrl($this->productAbstractTransfer);

        $this->assertInstanceOf(ProductUrlTransfer::class, $productUrl);
    }

    /**
     * @return void
     */
    public function testDeleteProductUrl()
    {
        $this->setupDefaultProducts();

        $this->productFacade->deleteProductUrl($this->productAbstractTransfer);
    }

    /**
     * @return void
     */
    public function testTouchProductAbstractUrlActive()
    {
        $this->setupDefaultProducts();
        $this->productFacade->createProductUrl($this->productAbstractTransfer);

        $this->productFacade->touchProductAbstractUrlActive($this->productAbstractTransfer);
    }

    /**
     * @return void
     */
    public function testTouchProductAbstractUrlDeleted()
    {
        $this->setupDefaultProducts();
        $this->productFacade->createProductUrl($this->productAbstractTransfer);

        $this->productFacade->touchProductAbstractUrlDeleted($this->productAbstractTransfer);
    }

}
