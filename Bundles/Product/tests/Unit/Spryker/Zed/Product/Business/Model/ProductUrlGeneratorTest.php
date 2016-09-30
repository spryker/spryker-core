<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Product;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\LocalizedUrlTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;
use Spryker\Zed\Product\Business\Product\ProductManager;
use Spryker\Zed\Product\Business\Product\ProductManagerInterface;
use Spryker\Zed\Product\Business\Product\ProductUrlGenerator;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Product
 * @group ProductUrlGeneratorTest
 */
class ProductUrlGeneratorTest extends Test
{

    const PRODUCT_NAME = [
        'en_US' => 'Product name en_US',
        'de_DE' => 'Product name de_DE',
    ];

    const ID_PRODUCT_ABSTRACT = 1;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $productManager;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainer
     */
    protected $productQueryContainer;

    /**
     * @var ProductAbstractTransfer
     */
    protected $productAbstractTransfer;

    /**
     * @var LocaleTransfer[]
     */
    protected $locales;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->setupLocales();
        $this->setupProductAbstract();

        $this->localeFacade = $this->getMock(ProductToLocaleBridge::class, [], [], '', false);

        $availableLocalesCollection = [
            $this->locales['de_DE']->getIdLocale() => $this->locales['de_DE'],
            $this->locales['en_US']->getIdLocale() => $this->locales['en_US'],
        ];

        $this->localeFacade
            ->expects($this->once())
            ->method('getAvailableLocales')
            ->willReturn($availableLocalesCollection);


        $this->productManager = $this->getMock(ProductManager::class, [], [], '', false);

        $this->productManager
            ->expects($this->at(0))
            ->method('getLocalizedProductAbstractName')
            ->with($this->productAbstractTransfer, $this->locales['de_DE'])
            ->willReturn(self::PRODUCT_NAME['de_DE']);

        $this->productManager
            ->expects($this->at(1))
            ->method('getLocalizedProductAbstractName')
            ->with($this->productAbstractTransfer, $this->locales['en_US'])
            ->willReturn(self::PRODUCT_NAME['en_US']);
    }

    /**
     * @return void
     */
    protected function setupLocales()
    {
        $this->locales['de_DE'] = new LocaleTransfer();
        $this->locales['de_DE']
            ->setIdLocale(46)
            ->setLocaleName('de_DE');

        $this->locales['en_US'] = new LocaleTransfer();
        $this->locales['en_US']
            ->setIdLocale(66)
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

    public function testGetProductUrlShouldReturnTransfer()
    {
        $expectedDEUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de/product-name-dede-1');

        $expectedENUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en/product-name-enus-1');

        $productUrlExpected = (new ProductUrlTransfer())
            ->setAbstractSku(
                $this->productAbstractTransfer->getSku()
            )
            ->setUrls(
                new ArrayObject([$expectedDEUrl, $expectedENUrl])
            );

        $urlGenerator = new ProductUrlGenerator($this->productManager, $this->localeFacade);
        $productUrl = $urlGenerator->getProductUrl($this->productAbstractTransfer);

        $this->assertEquals($productUrlExpected->getAbstractSku(), $productUrl->getAbstractSku());
        $this->assertEquals($productUrlExpected, $productUrl);
    }

}
