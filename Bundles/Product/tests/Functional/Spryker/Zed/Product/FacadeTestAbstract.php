<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Product;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Product\Business\Attribute\AttributeManager;
use Spryker\Zed\Product\Business\ProductBusinessFactory;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\Product\Business\Product\Plugin\PluginAbstractManager;
use Spryker\Zed\Product\Business\Product\Plugin\PluginConcreteManager;
use Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertion;
use Spryker\Zed\Product\Business\Product\ProductAbstractManager;
use Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertion;
use Spryker\Zed\Product\Business\Product\ProductConcreteManager;
use Spryker\Zed\Product\Business\Product\ProductManager;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlGenerator;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlManager;
use Spryker\Zed\Product\Business\Product\Sku\SkuGenerator;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToUtilBridge;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;
use Spryker\Zed\Product\ProductDependencyProvider;
use Spryker\Zed\Touch\Business\TouchFacade;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;
use Spryker\Zed\Url\Business\UrlFacade;
use Spryker\Zed\Util\Business\UtilFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Product
 * @group ProductManagerTest
 */
class FacadeTestAbstract extends Test
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
     * @var \Spryker\Zed\Util\Business\UtilFacadeInterface
     */
    protected $utilFacade;

    /**
     * @var \Spryker\Zed\Touch\Business\TouchFacadeInterface
     */
    protected $touchFacade;

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
     * @var \Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface
     */
    protected $productUrlManager;

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

        $container = new Container();
        $dependencyProvider = new ProductDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $dependencyProvider->provideCommunicationLayerDependencies($container);
        $dependencyProvider->providePersistenceLayerDependencies($container);

        $productBusinessFactory = new ProductBusinessFactory();
        $productBusinessFactory->setContainer($container);

        $this->localeFacade = new LocaleFacade();
        $this->productFacade = new ProductFacade();
        $this->urlFacade = new UrlFacade();
        $this->touchFacade = new TouchFacade();
        $this->utilFacade = new UtilFacade();
        $this->productQueryContainer = new ProductQueryContainer();
        $this->touchQueryContainer = new TouchQueryContainer();

        $this->productFacade->setFactory($productBusinessFactory);

        $urlBridge = new ProductToUrlBridge($this->urlFacade);
        $touchBridge = new ProductToTouchBridge($this->touchFacade);
        $localeBridge = new ProductToLocaleBridge($this->localeFacade);
        $utilBridge = new ProductToUtilBridge($this->utilFacade);

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
            $touchBridge,
            $urlBridge,
            $localeBridge,
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
            $touchBridge,
            $urlBridge,
            $localeBridge,
            $this->productConcreteManager,
            $productAbstractAssertion,
            $abstractPluginManager,
            new SkuGenerator($utilBridge)
        );

        $urlGenerator = new ProductUrlGenerator(
            $this->productAbstractManager,
            $localeBridge,
            $utilBridge
        );

        $this->productUrlManager = new ProductUrlManager(
            $urlBridge,
            $touchBridge,
            $localeBridge,
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

    protected function setupDefaultProducts()
    {
        $this->productManager->addProduct($this->productAbstractTransfer, [$this->productConcreteTransfer]);
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
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function getProductConcreteEntityByAbstractId($idProductAbstract)
    {
        return $this->productQueryContainer
            ->queryProduct()
            ->filterByFkProductAbstract($idProductAbstract)
            ->findOne();
    }

}
