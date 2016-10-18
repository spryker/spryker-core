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
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Price\Business\PriceFacade;
use Spryker\Zed\Price\Persistence\PriceQueryContainer;
use Spryker\Zed\Product\Business\Attribute\AttributeManager;
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
 * @group ProductAbstractManagerTest
 */
class ProductAbstractManagerTest extends Test
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

    const ABSTRACT_SKU = 'foo';
    const CONCRETE_SKU = 'foo-concrete';

    const PRICE = 1234;

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
     * @var \Spryker\Zed\Price\Persistence\PriceQueryContainerInterface
     */
    protected $priceProductQueryContainer;

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
        $this->priceProductQueryContainer = new PriceQueryContainer();

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
            ->setSku(self::ABSTRACT_SKU);

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
            ->setSku(self::CONCRETE_SKU);

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
    public function testCreateProductAbstractShouldCreateProductAbstract()
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        $this->assertTrue($idProductAbstract > 0);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->assertCreateProductAbstract($this->productAbstractTransfer);
    }

    /**
     * @return void
     */
    public function testSaveProductAbstractShouldUpdateProductAbstract()
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        foreach ($this->productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $localizedAttribute->setName(
                self::UPDATED_PRODUCT_ABSTRACT_NAME[$localizedAttribute->getLocale()->getLocaleName()]
            );
        }

        $idProductAbstract = $this->productAbstractManager->saveProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->assertSaveProductAbstract($this->productAbstractTransfer);
    }

    /**
     * @return void
     */
    public function testHasProductAbstractShouldReturnFalse()
    {
        $this->assertFalse(
            $this->productAbstractManager->hasProductAbstract('sku that does not exist')
        );
    }

    /**
     * @return void
     */
    public function testHasProductAbstractShouldReturnTrue()
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();

        $this->assertTrue(
            $this->productAbstractManager->hasProductAbstract(self::ABSTRACT_SKU)
        );
    }

    /**
     * @return void
     */
    public function testGetProductAbstractIdBySku()
    {
        $expectedId = $this->createNewProductAbstractAndAssertNoTouchExists();
        $idProductAbstract = $this->productAbstractManager->getProductAbstractIdBySku(self::ABSTRACT_SKU);

        $this->assertEquals(
            $expectedId,
            $idProductAbstract
        );
    }

    /**
     * @return void
     */
    public function testGetProductAbstractIdBySkuShouldReturnNull()
    {
        $idProductAbstract = $this->productAbstractManager->getProductAbstractIdBySku('INVALIDSKU');

        $this->assertNull($idProductAbstract);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractById()
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();
        $productAbstract = $this->productAbstractManager->getProductAbstractById($idProductAbstract);

        $this->assertInstanceOf(ProductAbstractTransfer::class, $productAbstract);
        $this->assertEquals(self::ABSTRACT_SKU, $productAbstract->getSku());
    }

    /**
     * @return void
     */
    public function testGetProductAbstractByIdShouldReturnNull()
    {
        $productAbstract = $this->productAbstractManager->getProductAbstractById(1010001);

        $this->assertNull($productAbstract);
    }

    /**
     * @return void
     */
    public function testGetAbstractSkuFromProductConcrete()
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();
        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);
        $this->productConcreteManager->createProductConcrete($this->productConcreteTransfer);

        $abstractSku = $this->productAbstractManager->getAbstractSkuFromProductConcrete(self::CONCRETE_SKU);

        $this->assertEquals(self::ABSTRACT_SKU, $abstractSku);
    }

    /**
     * @return void
     */
    public function testGetAbstractSkuFromProductConcreteShouldThrowException()
    {
        try {
            $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();
            $abstractSku = $this->productAbstractManager->getAbstractSkuFromProductConcrete('INVALIDSKU');

        } catch (\Exception $e) {
            $this->assertInstanceOf('Spryker\Zed\Product\Business\Exception\MissingProductException', $e);
            $this->assertEquals(
                'Tried to retrieve a product concrete with sku INVALIDSKU, but it does not exist.',
                $e->getMessage()
            );
        }
    }

    /**
     * @return void
     */
    public function testGetLocalizedProductAbstractName()
    {
        $nameEN = $this->productAbstractManager->getLocalizedProductAbstractName(
            $this->productAbstractTransfer,
            $this->locales['en_US']
        );

        $nameDE = $this->productAbstractManager->getLocalizedProductAbstractName(
            $this->productAbstractTransfer,
            $this->locales['de_DE']
        );

        $this->assertEquals(self::PRODUCT_ABSTRACT_NAME['en_US'], $nameEN);
        $this->assertEquals(self::PRODUCT_ABSTRACT_NAME['de_DE'], $nameDE);
    }

    /**
     * @return void
     */
    public function testTouchProductActiveShouldTouchActiveLogic()
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();

        $this->productAbstractManager->touchProductActive($idProductAbstract);

        $this->assertTouchEntry($idProductAbstract, ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);
        $this->assertTouchEntry($idProductAbstract, ProductConstants::RESOURCE_TYPE_URL, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);
        $this->assertTouchEntry($idProductAbstract, ProductConstants::RESOURCE_TYPE_ATTRIBUTE_MAP, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);
    }

    /**
     * @return void
     */
    public function testTouchProductInactiveShouldTouchInactiveLogic()
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();

        $this->productAbstractManager->touchProductActive($idProductAbstract);
        $this->assertTouchEntry($idProductAbstract, ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);
        $this->assertTouchEntry($idProductAbstract, ProductConstants::RESOURCE_TYPE_URL, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);
        $this->assertTouchEntry($idProductAbstract, ProductConstants::RESOURCE_TYPE_ATTRIBUTE_MAP, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);

        $this->productAbstractManager->touchProductInactive($idProductAbstract);
        $this->assertTouchEntry($idProductAbstract, ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT, SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE);
        $this->assertTouchEntry($idProductAbstract, ProductConstants::RESOURCE_TYPE_URL, SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE);
        $this->assertTouchEntry($idProductAbstract, ProductConstants::RESOURCE_TYPE_ATTRIBUTE_MAP, SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE);
    }

    /**
     * @return void
     */
    public function testTouchProductDeletedShouldTouchDeletedLogic()
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();

        $this->productAbstractManager->touchProductDeleted($idProductAbstract);

        $this->assertTouchEntry($idProductAbstract, ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT, SpyTouchTableMap::COL_ITEM_EVENT_DELETED);
        $this->assertTouchEntry($idProductAbstract, ProductConstants::RESOURCE_TYPE_URL, SpyTouchTableMap::COL_ITEM_EVENT_DELETED);
        $this->assertTouchEntry($idProductAbstract, ProductConstants::RESOURCE_TYPE_ATTRIBUTE_MAP, SpyTouchTableMap::COL_ITEM_EVENT_DELETED);
    }

    /**
     * @return void
     */
    public function testPersistProductShouldPersistPriceWhenCreatingProduct()
    {
        $price = (new PriceProductTransfer())
            ->setPrice(self::PRICE);

        $this->productAbstractTransfer->setPrice($price);

        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        $priceEntity = $this->priceProductQueryContainer
            ->queryPriceProduct()
            ->filterByFkProductAbstract($idProductAbstract)
            ->findOne();

        $this->assertNotNull($priceEntity);
        $this->assertEquals(self::PRICE, $priceEntity->getPrice());
    }

    /**
     * @return void
     */
    public function testPersistProductShouldPersistPriceWhenUpdatingProduct()
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        $price = (new PriceProductTransfer())
            ->setPrice(self::PRICE);

        $this->productAbstractTransfer->setPrice($price);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $idProductAbstract = $this->productAbstractManager->saveProductAbstract($this->productAbstractTransfer);

        $priceEntity = $this->priceProductQueryContainer
            ->queryPriceProduct()
            ->filterByFkProductAbstract($idProductAbstract)
            ->findOne();

        $this->assertNotNull($priceEntity);
        $this->assertEquals(self::PRICE, $priceEntity->getPrice());
    }

    /**
     * @return int
     */
    protected function createNewProductAbstractAndAssertNoTouchExists()
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        $this->assertNoTouchEntry($idProductAbstract, ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT);
        $this->assertNoTouchEntry($idProductAbstract, ProductConstants::RESOURCE_TYPE_URL);
        $this->assertNoTouchEntry($idProductAbstract, ProductConstants::RESOURCE_TYPE_ATTRIBUTE_MAP);

        return $idProductAbstract;
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
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function assertCreateProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $createdProductEntity = $this->getProductAbstractEntityById($productAbstractTransfer->getIdProductAbstract());

        $this->assertNotNull($createdProductEntity);
        $this->assertEquals($productAbstractTransfer->getSku(), $createdProductEntity->getSku());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function assertSaveProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $updatedProductEntity = $this->getProductAbstractEntityById($productAbstractTransfer->getIdProductAbstract());

        $this->assertNotNull($updatedProductEntity);
        $this->assertEquals($this->productAbstractTransfer->getSku(), $updatedProductEntity->getSku());

        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $expectedProductName = self::UPDATED_PRODUCT_ABSTRACT_NAME[$localizedAttribute->getLocale()->getLocaleName()];

            $this->assertEquals($expectedProductName, $localizedAttribute->getName());
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function getProductAbstractEntityById($idProductAbstract)
    {
        return $this->productQueryContainer
            ->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
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
