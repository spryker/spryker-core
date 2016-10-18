<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Product;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\LocalizedUrlTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Product\Business\Attribute\AttributeManager;
use Spryker\Zed\Product\Business\Product\PluginAbstractManager;
use Spryker\Zed\Product\Business\Product\PluginConcreteManager;
use Spryker\Zed\Product\Business\Product\Sku\SkuGenerator;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\Product\Business\Product\ProductAbstractAssertion;
use Spryker\Zed\Product\Business\Product\ProductAbstractManager;
use Spryker\Zed\Product\Business\Product\ProductConcreteAssertion;
use Spryker\Zed\Product\Business\Product\ProductConcreteManager;
use Spryker\Zed\Product\Business\Product\ProductUrlGenerator;
use Spryker\Zed\Product\Business\Product\ProductUrlManager;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToPriceBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlBridge;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;
use Spryker\Zed\Url\Business\UrlFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Product
 * @group ProductUrlManagerTest
 */
class ProductUrlManagerTest extends Test
{

    const PRODUCT_NAME = [
        'en_US' => 'Product name en_US',
        'de_DE' => 'Product name de_DE',
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
     * @var \Spryker\Zed\Product\Business\Product\ProductUrlManagerInterface
     */
    protected $productUrlManager;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransfer;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface
     */
    protected $productAbstractManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    protected $productConcreteManager;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->setupLocales();
        $this->setupProductAbstract();

        $this->localeFacade = new LocaleFacade();
        $this->productFacade = new ProductFacade();
        $this->urlFacade = new UrlFacade();
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

        $this->productUrlManager = new ProductUrlManager(
            new ProductToUrlBridge($this->urlFacade),
            new ProductToTouchBridge($this->touchFacade),
            new ProductToLocaleBridge($this->localeFacade),
            $this->productQueryContainer,
            $urlGenerator
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
            ->setName(self::PRODUCT_NAME['de_DE'])
            ->setLocale($this->locales['de_DE']);

        $this->productAbstractTransfer->addLocalizedAttributes($localizedAttribute);

        $localizedAttribute = new LocalizedAttributesTransfer();
        $localizedAttribute
            ->setName(self::PRODUCT_NAME['en_US'])
            ->setLocale($this->locales['en_US']);

        $this->productAbstractTransfer->addLocalizedAttributes($localizedAttribute);
    }

    /**
     * @return void
     */
    public function testCreateProductUrlShouldCreateNewUrlForProductAbstract()
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $expectedENUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en/product-name-enus-' . $idProductAbstract);
        $expectedDEUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de/product-name-dede-' . $idProductAbstract);

        $productUrl = $this->productUrlManager->createProductUrl($this->productAbstractTransfer);

        $this->assertProductUrl($productUrl, $expectedENUrl, $expectedDEUrl);
        $this->assertUrlTransfer($expectedENUrl, $this->locales['en_US']);
        $this->assertUrlTransfer($expectedDEUrl, $this->locales['de_DE']);
    }

    /**
     * @return void
     */
    public function testUpdateProductUrlShouldSaveUrlForProductAbstract()
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->productUrlManager->createProductUrl($this->productAbstractTransfer);

        $expectedENUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en/new-product-name-enus-' . $idProductAbstract);
        $expectedDEUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de/new-product-name-dede-' . $idProductAbstract);

        foreach ($this->productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $localizedAttribute->setName('New ' . $localizedAttribute->getName());
        }

        $productUrl = $this->productUrlManager->updateProductUrl($this->productAbstractTransfer);

        $this->assertProductUrl($productUrl, $expectedENUrl, $expectedDEUrl);
        $this->assertUrlTransfer($expectedENUrl, $this->locales['en_US']);
        $this->assertUrlTransfer($expectedDEUrl, $this->locales['de_DE']);
    }

    /**
     * @return void
     */
    public function testDeleteProductUrlShouldDeleteUrlForProductAbstract()
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->productUrlManager->createProductUrl($this->productAbstractTransfer);
        $this->productUrlManager->deleteProductUrl($this->productAbstractTransfer);

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $urlTransfer = $this->urlFacade->getUrlByIdProductAbstractAndIdLocale(
                $this->productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract(),
                $localeTransfer->getIdLocale()
            );

            $this->assertNull($urlTransfer->getIdUrl());
        }
    }

    /**
     * TODO rollback on error
     *
     * @return void
     */
    public function SKIP_testCreateUrlShouldThrowExceptionWhenUrlExists()
    {
        try {
            $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
            $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

            $this->productUrlManager->createProductUrl($this->productAbstractTransfer);
            $this->productUrlManager->createProductUrl($this->productAbstractTransfer);
        } catch (\Exception $e) {
            $message = sprintf(
                'Tried to create url /en/product-name-enus-%d, but it already exists',
                $idProductAbstract
            );

            $this->assertInstanceOf(UrlExistsException::class, $e);
            $this->assertEquals(
                $message,
                $e->getMessage()
            );
        }
    }

    /**
     * @return void
     */
    public function testUpdateUrlShouldNotThrowExceptionWhenUrlExistsForSameProduct()
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->productUrlManager->createProductUrl($this->productAbstractTransfer);
        $this->productUrlManager->updateProductUrl($this->productAbstractTransfer);
    }

    /**
     * TODO rollback on error
     *
     * @return void
     */
    public function SKIP_testProductUrlShouldBeUnique()
    {
        $this->expectException(UrlExistsException::class);

        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->productUrlManager->updateProductUrl($this->productAbstractTransfer);
        $this->productUrlManager->createProductUrl($this->productAbstractTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteProductUrlCanBeExecutedWhenUrlDoesNotExist()
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->productUrlManager->deleteProductUrl($this->productAbstractTransfer);
    }

    /**
     * @return void
     */
    public function testGetProductUrl()
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        $expectedENUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en/product-name-enus-' . $idProductAbstract);
        $expectedDEUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de/product-name-dede-' . $idProductAbstract);

        $productUrl = $this->productUrlManager->getProductUrl($this->productAbstractTransfer);

        $this->assertInstanceOf(ProductUrlTransfer::class, $productUrl);
        $this->assertProductUrl($productUrl, $expectedENUrl, $expectedDEUrl);
    }

    /**
     * @return void
     */
    public function testTouchProductUrlActiveShouldTouchLogic()
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->productUrlManager->createProductUrl($this->productAbstractTransfer);

        $this->productUrlManager->touchProductUrlActive($this->productAbstractTransfer);

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $urlTransfer = $this->urlFacade->getUrlByIdProductAbstractAndIdLocale(
                $this->productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract(),
                $localeTransfer->getIdLocale()
            );

            $activeTouchEntity = $this->getProductUrlTouchEntry($urlTransfer->getIdUrl());

            $this->assertNotNull($activeTouchEntity);
            $this->assertEquals($urlTransfer->getIdUrl(), $activeTouchEntity->getItemId());
            $this->assertEquals(SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE, $activeTouchEntity->getItemEvent());
            $this->assertEquals('url', $activeTouchEntity->getItemType());
        }
    }

    /**
     * @return void
     */
    public function testTouchProductUrlDeletedShouldTouchLogic()
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->productUrlManager->createProductUrl($this->productAbstractTransfer);

        $this->productUrlManager->touchProductUrlDeleted($this->productAbstractTransfer);

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $urlTransfer = $this->urlFacade->getUrlByIdProductAbstractAndIdLocale(
                $this->productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract(),
                $localeTransfer->getIdLocale()
            );

            $deletedTouchEntity = $this->getProductUrlTouchEntry($urlTransfer->getIdUrl());

            $this->assertNotNull($deletedTouchEntity);
            $this->assertEquals($urlTransfer->getIdUrl(), $deletedTouchEntity->getItemId());
            $this->assertEquals(SpyTouchTableMap::COL_ITEM_EVENT_DELETED, $deletedTouchEntity->getItemEvent());
            $this->assertEquals('url', $deletedTouchEntity->getItemType());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductUrlTransfer $productUrl
     * @param \Generated\Shared\Transfer\LocalizedUrlTransfer $expectedENUrl
     * @param \Generated\Shared\Transfer\LocalizedUrlTransfer $expectedDEUrl
     *
     * @return void
     */
    protected function assertProductUrl(ProductUrlTransfer $productUrl, LocalizedUrlTransfer $expectedENUrl, LocalizedUrlTransfer $expectedDEUrl)
    {
        $urlCollection = new ArrayObject([$expectedENUrl, $expectedDEUrl]);

        $productUrlExpected = (new ProductUrlTransfer())
            ->setAbstractSku(
                $this->productAbstractTransfer->getSku()
            )
            ->setUrls(
                $urlCollection
            );

        $this->assertEquals($productUrlExpected->getAbstractSku(), $productUrl->getAbstractSku());
        $this->assertEquals($urlCollection, $productUrlExpected->getUrls());
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedUrlTransfer $expectedUrl
     *
     * @return void
     */
    protected function assertUrlTransfer(LocalizedUrlTransfer $expectedUrl, LocaleTransfer $expectedLocale)
    {
        $urlTransfer = $this->urlFacade->getUrlByIdProductAbstractAndIdLocale(
            $this->productAbstractTransfer->getIdProductAbstract(),
            $expectedLocale->getIdLocale()
        );

        $this->assertEquals($expectedUrl->getUrl(), $urlTransfer->getUrl());
        $this->assertEquals($expectedUrl->getLocale()->getIdLocale(), $urlTransfer->getFkLocale());

        $this->assertEquals($this->productAbstractTransfer->getIdProductAbstract(), $urlTransfer->getFkProductAbstract());
        $this->assertEquals($this->productAbstractTransfer->getIdProductAbstract(), $urlTransfer->getResourceId());
        $this->assertEquals(ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT, $urlTransfer->getResourceType());
    }

    /**
     * @param int $idUrl
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouch
     */
    protected function getProductUrlTouchEntry($idUrl)
    {
        return SpyTouchQuery::create()
            ->filterByItemType('url')
            ->filterByItemId($idUrl)
            ->findOne();
    }

}
