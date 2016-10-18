<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Product;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Spryker\Shared\Product\ProductConstants;
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
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToPriceBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlBridge;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;
use Spryker\Zed\Touch\Business\TouchFacade;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;
use Spryker\Zed\Url\Business\UrlFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Product
 * @group ProductConcreteManagerTest
 */
class ProductConcreteManagerTest extends Test
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

        $this->localeFacade = new LocaleFacade();
        $this->touchFacade = new TouchFacade();
        $this->productFacade = new ProductFacade();
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

        $stock = (new StockProductTransfer())
            ->setQuantity(10);

        $this->productConcreteTransfer->setStocks(new \ArrayObject($stock));

        $priceProduct = (new PriceProductTransfer())
            ->setPrice(1234);

        $this->productConcreteTransfer->setPrice($priceProduct);

    }

    protected function setupDefaultProducts()
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);

        $idProductConcrete = $this->productConcreteManager->createProductConcrete($this->productConcreteTransfer);
        $this->productConcreteTransfer->setIdProductConcrete($idProductConcrete);
    }

    /**
     * @return void
     */
    public function testCreateProductConcreteShouldCreateProductConcrete()
    {
        $this->setupDefaultProducts();

        $this->assertTrue($this->productConcreteTransfer->getIdProductConcrete() > 0);
        $this->assertCreateProductConcrete($this->productConcreteTransfer);
    }

    /**
     * @return void
     */
    public function testSaveProductAbstractShouldUpdateProductAbstract()
    {
        $this->setupDefaultProducts();

        foreach ($this->productConcreteTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $localizedAttribute->setName(
                self::UPDATED_PRODUCT_ABSTRACT_NAME[$localizedAttribute->getLocale()->getLocaleName()]
            );
        }

        $idProductConcrete = $this->productConcreteManager->saveProductConcrete($this->productConcreteTransfer);

        $this->assertEquals($this->productConcreteTransfer->getIdProductConcrete(), $idProductConcrete);
        $this->assertSaveProductConcrete($this->productConcreteTransfer);
    }

    /**
     * @return void
     */
    public function testHasProductConcreteShouldReturnTrue()
    {
        $this->setupDefaultProducts();

        $exists = $this->productConcreteManager->hasProductConcrete($this->productConcreteTransfer->getSku());
        $this->assertTrue($exists);
    }

    /**
     * @return void
     */
    public function testHasProductConcreteShouldReturnFalse()
    {
        $exists = $this->productConcreteManager->hasProductConcrete('INVALIDSKU');
        $this->assertFalse($exists);
    }

    /**
     * @return void
     */
    public function testTouchProductActiveShouldTouchActiveLogic()
    {
        $this->createNewProductAndAssertNoTouchExists();

        $this->productConcreteManager->touchProductActive($this->productAbstractTransfer->getIdProductAbstract());

        $this->assertTouchEntry(
            $this->productAbstractTransfer->getIdProductAbstract(),
            ProductConstants::RESOURCE_TYPE_PRODUCT_CONCRETE, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE
        );
    }

    /**
     * @return void
     */
    public function testTouchProductInactiveShouldTouchInactiveLogic()
    {
        $this->createNewProductAndAssertNoTouchExists();

        $this->productConcreteManager->touchProductActive($this->productAbstractTransfer->getIdProductAbstract());
        $this->assertTouchEntry(
            $this->productAbstractTransfer->getIdProductAbstract(),
            ProductConstants::RESOURCE_TYPE_PRODUCT_CONCRETE,
            SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE
        );

        $this->productConcreteManager->touchProductInactive($this->productAbstractTransfer->getIdProductAbstract());
        $this->assertTouchEntry(
            $this->productAbstractTransfer->getIdProductAbstract(),
            ProductConstants::RESOURCE_TYPE_PRODUCT_CONCRETE,
            SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE
        );
    }

    /**
     * @return void
     */
    public function testTouchProductDeletedShouldTouchDeletedLogic()
    {
        $this->createNewProductAndAssertNoTouchExists();

        $this->productConcreteManager->touchProductDeleted($this->productAbstractTransfer->getIdProductAbstract());

        $this->assertTouchEntry(
            $this->productAbstractTransfer->getIdProductAbstract(),
            ProductConstants::RESOURCE_TYPE_PRODUCT_CONCRETE,
            SpyTouchTableMap::COL_ITEM_EVENT_DELETED
        );
    }

    /**
     * @return void
     */
    public function testGetProductConcreteByIdShouldReturnConcreteTransfer()
    {
        $this->setupDefaultProducts();

        $productConcreteTransfer = $this->productConcreteManager->getProductConcreteById(
            $this->productConcreteTransfer->getIdProductConcrete()
        );

        $this->assertCreateProductConcrete($productConcreteTransfer);
        $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcreteTransfer);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteByIdShouldReturnNull()
    {
        $productConcreteTransfer = $this->productConcreteManager->getProductConcreteById(101001);

        $this->assertNull($productConcreteTransfer);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteIdBySkuShouldReturnId()
    {
        $this->setupDefaultProducts();

        $id = $this->productConcreteManager->getProductConcreteIdBySku($this->productConcreteTransfer->getSku());

        $this->assertEquals($this->productConcreteTransfer->getIdProductConcrete(), $id);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteIdBySkuShouldReturnNull()
    {
        $id = $this->productConcreteManager->getProductConcreteIdBySku('INVALIDSKU');

        $this->assertNull($id);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteShouldReturnConcreteTransfer()
    {
        $this->setupDefaultProducts();

        $productConcrete = $this->productConcreteManager->getProductConcrete($this->productConcreteTransfer->getSku());

        $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcrete);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteShouldThrowException()
    {
        $this->expectException(MissingProductException::class);

        $productConcrete = $this->productConcreteManager->getProductConcrete('INVALIDSKU');
    }

    /**
     * @return void
     */
    public function testGetConcreteProductsByAbstractProductIdShouldReturnConcreteCollection()
    {
        $this->setupDefaultProducts();

        $productConcreteCollection = $this->productConcreteManager->getConcreteProductsByAbstractProductId(
            $this->productAbstractTransfer->getIdProductAbstract()
        );

        foreach ($productConcreteCollection as $productConcrete) {
            $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcrete);
            $this->assertEquals($this->productConcreteTransfer->getSku(), $productConcrete->getSku());
        }
    }

    /**
     * @return void
     */
    public function testGetProductAbstractIdByConcreteSku()
    {
        $this->setupDefaultProducts();

        $idProductAbstract = $this->productConcreteManager->getProductAbstractIdByConcreteSku($this->productConcreteTransfer->getSku());

        $this->assertEquals($this->productAbstractTransfer->getIdProductAbstract(), $idProductAbstract);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractIdByConcreteSkuShouldThrowException()
    {
        $this->expectException(MissingProductException::class);

        $this->setupDefaultProducts();

        $this->productConcreteManager->getProductAbstractIdByConcreteSku('INVALIDSKU');
    }

    /**
     * @return void
     */
    public function testGetConcreteProductsByAbstractProductIdShouldReturnEmptyArray()
    {
        $productConcreteCollection = $this->productConcreteManager->getConcreteProductsByAbstractProductId(
            121231
        );

        $this->assertEmpty($productConcreteCollection);
    }

    /**
     * @return void
     */
    public function testFindProductEntityByAbstractAndConcreteShouldReturnEntity()
    {
        $this->setupDefaultProducts();

        $productEntity = $this->productConcreteManager->findProductEntityByAbstractAndConcrete(
            $this->productAbstractTransfer,
            $this->productConcreteTransfer
        );

        $this->assertInstanceOf(SpyProduct::class, $productEntity);
    }

    /**
     * @return void
     */
    public function testFindProductEntityByAbstractAndConcreteShouldReturnNull()
    {
        $this->setupDefaultProducts();

        $this->productAbstractTransfer->setIdProductAbstract(122313);
        $this->productConcreteTransfer->setIdProductConcrete(122313);

        $productEntity = $this->productConcreteManager->findProductEntityByAbstractAndConcrete(
            $this->productAbstractTransfer,
            $this->productConcreteTransfer
        );

        $this->assertNull($productEntity);
    }

    /**
     * @return void
     */
    public function testGetLocalizedProductConcreteName()
    {
        $this->setupDefaultProducts();

        $productNameEN = $this->productConcreteManager->getLocalizedProductConcreteName(
            $this->productConcreteTransfer,
            $this->locales['en_US']
        );

        $productNameDE = $this->productConcreteManager->getLocalizedProductConcreteName(
            $this->productConcreteTransfer,
            $this->locales['de_DE']
        );

        $this->assertEquals(self::PRODUCT_CONCRETE_NAME['en_US'], $productNameEN);
        $this->assertEquals(self::PRODUCT_CONCRETE_NAME['de_DE'], $productNameDE);
    }

    /**
     * @return void
     */
    protected function createNewProductAndAssertNoTouchExists()
    {
        $this->setupDefaultProducts();

        $this->assertNoTouchEntry($this->productConcreteTransfer->getIdProductConcrete(), ProductConstants::RESOURCE_TYPE_PRODUCT_CONCRETE);
    }

    /**
     * @param int $idProductAbstract
     * @param string $touchType
     *
     * @return void
     */
    protected function assertNoTouchEntry($idProductAbstract, $touchType)
    {
        $touchEntity = $this->getProductTouchEntity($touchType, $idProductAbstract);

        $this->assertNull($touchEntity);
    }

    /**
     * @param int $idProductAbstract
     * @param string $touchType
     *
     * @return void
     */
    protected function assertTouchEntry($idProductAbstract, $touchType, $status)
    {
        $touchEntity = $this->getProductTouchEntity($touchType, $idProductAbstract);

        $this->assertEquals($touchType, $touchEntity->getItemType());
        $this->assertEquals($idProductAbstract, $touchEntity->getItemId());
        $this->assertEquals($status, $touchEntity->getItemEvent());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function assertCreateProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
        $createdProductEntity = $this->getProductConcreteEntityById($productConcreteTransfer->getIdProductConcrete());

        $this->assertNotNull($createdProductEntity);
        $this->assertEquals($productConcreteTransfer->getSku(), $createdProductEntity->getSku());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function assertSaveProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
        $updatedProductEntity = $this->getProductConcreteEntityById($productConcreteTransfer->getIdProductConcrete());

        $this->assertNotNull($updatedProductEntity);
        $this->assertEquals($this->productConcreteTransfer->getSku(), $updatedProductEntity->getSku());

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $expectedProductName = self::UPDATED_PRODUCT_ABSTRACT_NAME[$localizedAttribute->getLocale()->getLocaleName()];

            $this->assertEquals($expectedProductName, $localizedAttribute->getName());
        }
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function getProductConcreteEntityById($idProductConcrete)
    {
        return $this->productQueryContainer
            ->queryProduct()
            ->filterByIdProduct($idProductConcrete)
            ->findOne();
    }

    /**
     * @param string $touchType
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouch
     */
    protected function getProductTouchEntity($touchType, $idProductAbstract)
    {
        return $this->touchQueryContainer
            ->queryTouchEntriesByItemTypeAndItemIds($touchType, [$idProductAbstract])
            ->findOne();
    }

}
