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
use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Product\Business\Attribute\AttributeManager;
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

    const ID_PRODUCT_ABSTRACT = 1;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected $locales;

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
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainer
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductUrlManagerInterface
     */
    protected $productUrlManager;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransfer;

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

        $attributeManager = new AttributeManager(
            $this->productQueryContainer
        );

        $productAbstractAssertion = new ProductAbstractAssertion(
            $this->productQueryContainer
        );

        $productConcreteAssertion = new ProductConcreteAssertion(
            $this->productQueryContainer
        );

        $productConcreteManager = new ProductConcreteManager(
            $attributeManager,
            $this->productQueryContainer,
            new ProductToTouchBridge($this->touchFacade),
            new ProductToUrlBridge($this->urlFacade),
            new ProductToLocaleBridge($this->localeFacade),
            new ProductToPriceBridge($this->priceFacade),
            $productAbstractAssertion,
            $productConcreteAssertion,
            [],
            [],
            []
        );

        $productAbstractManager = new ProductAbstractManager(
            $attributeManager,
            $this->productQueryContainer,
            new ProductToTouchBridge($this->touchFacade),
            new ProductToUrlBridge($this->urlFacade),
            new ProductToLocaleBridge($this->localeFacade),
            new ProductToPriceBridge($this->priceFacade),
            $productConcreteManager,
            $productAbstractAssertion,
            [],
            [],
            []
        );

        $urlGenerator = new ProductUrlGenerator(
            $productAbstractManager,
            new ProductToLocaleBridge($this->localeFacade)
        );

        $this->productUrlManager = new ProductUrlManager(
            new ProductToUrlBridge($this->urlFacade),
            new ProductToTouchBridge($this->touchFacade),
            new ProductToLocaleBridge($this->localeFacade),
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
            ->setSku('foo')
            ->setIdProductAbstract(self::ID_PRODUCT_ABSTRACT);

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
        $expectedENUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en/product-name-enus-1');
        $expectedDEUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de/product-name-dede-1');

        $productUrl = $this->productUrlManager->createProductUrl($this->productAbstractTransfer);

        $this->assertProductUrl($productUrl, $expectedENUrl, $expectedDEUrl);
        $this->assertUrlTransferEN($expectedENUrl);
        $this->assertUrlTransferDE($expectedDEUrl);
    }

    /**
     * @return void
     */
    public function testUpdateProductUrlShouldSaveUrlForProductAbstract()
    {
        $expectedENUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en/new-product-name-enus-1');
        $expectedDEUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de/new-product-name-dede-1');

        foreach ($this->productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $localizedAttribute->setName('New ' . $localizedAttribute->getName());
        }

        $productUrl = $this->productUrlManager->updateProductUrl($this->productAbstractTransfer);

        $this->assertProductUrl($productUrl, $expectedENUrl, $expectedDEUrl);
        $this->assertUrlTransferEN($expectedENUrl);
        $this->assertUrlTransferDE($expectedDEUrl);
    }

    /**
     * @return void
     */
    public function testDeleteProductUrlShouldDeleteUrlForProductAbstract()
    {
        $productUrl = $this->productUrlManager->createProductUrl($this->productAbstractTransfer);
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
     * @param \Generated\Shared\Transfer\ProductUrlTransfer $productUrl
     * @param \Generated\Shared\Transfer\LocalizedUrlTransfer $expectedENUrl
     * @param \Generated\Shared\Transfer\LocalizedUrlTransfer $expectedDEUrl
     *
     * @return void
     */
    protected function assertProductUrl(ProductUrlTransfer $productUrl, LocalizedUrlTransfer $expectedENUrl, LocalizedUrlTransfer $expectedDEUrl)
    {
        $productUrlExpected = (new ProductUrlTransfer())
            ->setAbstractSku(
                $this->productAbstractTransfer->getSku()
            )
            ->setUrls(
                new ArrayObject([$expectedENUrl, $expectedDEUrl])
            );

        $this->assertEquals($productUrlExpected->getAbstractSku(), $productUrl->getAbstractSku());
        $this->assertEquals($productUrlExpected->getUrls()[0], $expectedENUrl);
        $this->assertEquals($productUrlExpected->getUrls()[1], $expectedDEUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedUrlTransfer $expectedUrl
     *
     * @return void
     */
    protected function assertUrlTransferEN(LocalizedUrlTransfer $expectedUrl)
    {
        $urlENTransfer = $this->urlFacade->getUrlByIdProductAbstractAndIdLocale(
            $this->productAbstractTransfer->getIdProductAbstract(),
            $this->locales['en_US']->getIdLocale()
        );

        $this->assertEquals($expectedUrl->getUrl(), $urlENTransfer->getUrl());
        $this->assertEquals($expectedUrl->getLocale()->getIdLocale(), $urlENTransfer->getFkLocale());

        $this->assertEquals(self::ID_PRODUCT_ABSTRACT, $urlENTransfer->getFkProductAbstract());
        $this->assertEquals(self::ID_PRODUCT_ABSTRACT, $urlENTransfer->getResourceId());
        $this->assertEquals(ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT, $urlENTransfer->getResourceType());
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedUrlTransfer $expectedUrl
     *
     * @return void
     */
    protected function assertUrlTransferDE(LocalizedUrlTransfer $expectedUrl)
    {
        $urlDETransfer = $this->urlFacade->getUrlByIdProductAbstractAndIdLocale(
            $this->productAbstractTransfer->getIdProductAbstract(),
            $this->locales['de_DE']->getIdLocale()
        );

        $this->assertEquals($expectedUrl->getUrl(), $urlDETransfer->getUrl());
        $this->assertEquals($expectedUrl->getLocale()->getIdLocale(), $urlDETransfer->getFkLocale());

        $this->assertEquals(self::ID_PRODUCT_ABSTRACT, $urlDETransfer->getFkProductAbstract());
        $this->assertEquals(self::ID_PRODUCT_ABSTRACT, $urlDETransfer->getResourceId());
        $this->assertEquals(ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT, $urlDETransfer->getResourceType());
    }

}
