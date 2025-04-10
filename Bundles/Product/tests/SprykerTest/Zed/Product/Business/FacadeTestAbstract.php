<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Service\UtilText\UtilTextService;
use Spryker\Zed\Event\Business\EventFacade;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Product\Business\Product\ProductManager;
use Spryker\Zed\Product\Business\Product\Trigger\ProductEventTrigger;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlManager;
use Spryker\Zed\Product\Business\ProductBusinessFactory;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\Product\Dependency\Facade\ProductToEventBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlBridge;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;
use Spryker\Zed\Product\Persistence\ProductRepository;
use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;
use Spryker\Zed\Product\ProductDependencyProvider;
use Spryker\Zed\Touch\Business\TouchFacade;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;
use Spryker\Zed\Url\Business\UrlFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Product
 * @group ProductManagerTest
 */
class FacadeTestAbstract extends Unit
{
    /**
     * @var string
     */
    public const EN_LOCALE = 'en_US';

    /**
     * @var string
     */
    public const DE_LOCALE = 'de_DE';

    /**
     * @var array
     */
    public const PRODUCT_ABSTRACT_NAME = [
        'en_US' => 'Product name en_US',
        'de_DE' => 'Product name de_DE',
    ];

    /**
     * @var array
     */
    public const PRODUCT_CONCRETE_NAME = [
        'en_US' => 'Product concrete name en_US',
        'de_DE' => 'Product concrete name de_DE',
    ];

    /**
     * @var array
     */
    public const UPDATED_PRODUCT_ABSTRACT_NAME = [
        'en_US' => 'Updated Product name en_US',
        'de_DE' => 'Updated Product name de_DE',
    ];

    /**
     * @var array
     */
    public const UPDATED_PRODUCT_CONCRETE_NAME = [
        'en_US' => 'Updated Product concrete name en_US',
        'de_DE' => 'Updated Product concrete name de_DE',
    ];

    /**
     * @var string
     */
    public const ABSTRACT_SKU = 'foo';

    /**
     * @var string
     */
    public const CONCRETE_SKU = 'foo-concrete';

    /**
     * @var array<\Generated\Shared\Transfer\LocaleTransfer>
     */
    protected $locales;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

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
     * @var \Spryker\Service\UtilText\UtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @var \Spryker\Zed\Touch\Business\TouchFacadeInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Event\Business\EventFacade
     */
    protected $eventFacade;

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
     * @var \Spryker\Zed\Product\Business\Product\Trigger\ProductEventTrigger
     */
    protected $productEventTrigger;

    /**
     * @var \SprykerTest\Zed\Product\ProductBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
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
        $this->utilTextService = new UtilTextService();
        $this->productQueryContainer = new ProductQueryContainer();
        $this->productRepository = new ProductRepository();
        $this->touchQueryContainer = new TouchQueryContainer();
        $this->eventFacade = new EventFacade();

        $this->productFacade->setFactory($productBusinessFactory);

        $urlBridge = new ProductToUrlBridge($this->urlFacade);
        $touchBridge = new ProductToTouchBridge($this->touchFacade);
        $localeBridge = new ProductToLocaleBridge($this->localeFacade);
        $eventBridge = new ProductToEventBridge($this->eventFacade);

        $this->productEventTrigger = new ProductEventTrigger($eventBridge);

        $this->productConcreteManager = $productBusinessFactory->createProductConcreteManager();

        $this->productAbstractManager = $productBusinessFactory->createProductAbstractManager();

        $urlGenerator = $productBusinessFactory->createProductUrlGenerator();

        $this->productUrlManager = new ProductUrlManager(
            $urlBridge,
            $touchBridge,
            $localeBridge,
            $this->productQueryContainer,
            $urlGenerator,
            $this->productEventTrigger,
            $this->productRepository,
        );

        $this->productManager = new ProductManager(
            $this->productAbstractManager,
            $this->productConcreteManager,
            $this->productQueryContainer,
            $this->productEventTrigger,
        );
    }

    /**
     * @return void
     */
    protected function setupLocales(): void
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
    protected function setupProductAbstract(): void
    {
        $this->productAbstractTransfer = new ProductAbstractTransfer();
        $this->productAbstractTransfer
            ->setSku(static::ABSTRACT_SKU);

        $localizedAttribute = new LocalizedAttributesTransfer();
        $localizedAttribute
            ->setName(static::PRODUCT_ABSTRACT_NAME['de_DE'])
            ->setLocale($this->locales['de_DE']);

        $this->productAbstractTransfer->addLocalizedAttributes($localizedAttribute);

        $localizedAttribute = new LocalizedAttributesTransfer();
        $localizedAttribute
            ->setName(static::PRODUCT_ABSTRACT_NAME['en_US'])
            ->setLocale($this->locales['en_US']);

        $this->productAbstractTransfer->addLocalizedAttributes($localizedAttribute);
    }

    /**
     * @return void
     */
    protected function setupProductConcrete(): void
    {
        $this->productConcreteTransfer = new ProductConcreteTransfer();
        $this->productConcreteTransfer
            ->setSku(static::CONCRETE_SKU)
            ->setIsActive(true);

        $localizedAttribute = new LocalizedAttributesTransfer();
        $localizedAttribute
            ->setName(static::PRODUCT_CONCRETE_NAME['de_DE'])
            ->setLocale($this->locales['de_DE']);

        $this->productConcreteTransfer->addLocalizedAttributes($localizedAttribute);

        $localizedAttribute = new LocalizedAttributesTransfer();
        $localizedAttribute
            ->setName(static::PRODUCT_CONCRETE_NAME['en_US'])
            ->setLocale($this->locales['en_US']);

        $this->productConcreteTransfer->addLocalizedAttributes($localizedAttribute);
    }

    /**
     * @return void
     */
    protected function setupDefaultProducts(): void
    {
        $this->productManager->addProduct($this->productAbstractTransfer, [$this->productConcreteTransfer]);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract|null
     */
    protected function getProductAbstractEntityById(int $idProductAbstract): ?SpyProductAbstract
    {
        return $this->productQueryContainer
            ->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
            ->findOne();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct|null
     */
    protected function getProductConcreteEntityByAbstractId(int $idProductAbstract): ?SpyProduct
    {
        return $this->productQueryContainer
            ->queryProduct()
            ->filterByFkProductAbstract($idProductAbstract)
            ->findOne();
    }
}
