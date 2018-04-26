<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Cart;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use PHPUnit_Framework_MockObject_MockObject;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Price\PriceConfig;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpander;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceProductFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Cart
 * @group ProductBundleCartExpanderTest
 * Add your own group annotations below this line
 */
class ProductBundleCartExpanderTest extends Unit
{
    /**
     * @var array
     */
    protected $fixtures = [
        'idProductConcrete' => 1,
        'bundledProductSku' => 'sku-123',
        'fkBundledProduct' => 2,
        'bundledProductQuantity' => 2,
        'idProductBundle' => 1,
    ];

    /**
     * @return void
     */
    public function testExpandBundleItemsShouldExtractBundledItemsAndDistributeBundlePrice()
    {
        $productExpanderMock = $this->setupProductExpander();

        $this->setupFindBundledItemsByIdProductConcrete($this->fixtures, $productExpanderMock);

        $cartChangeTransfer = $this->createCartChangeTransfer();

        $updatedCartChangeTransfer = $productExpanderMock->expandBundleItems($cartChangeTransfer);

        $bundleItems = $updatedCartChangeTransfer->getQuote()->getBundleItems();
        $updatedAddToCartItems = $updatedCartChangeTransfer->getItems();

        $this->assertCount(4, $updatedAddToCartItems);
        $this->assertCount(2, $bundleItems);
        $this->assertCount(0, $updatedCartChangeTransfer->getQuote()->getItems());

        $bundleItemTransfer = $bundleItems[0];

        $bundledItemTransfer = $updatedAddToCartItems[0];
        $this->assertSame(150, $bundledItemTransfer->getUnitGrossPrice());
        $this->assertSame(1, $bundledItemTransfer->getQuantity());
        $this->assertEquals($bundleItemTransfer->getBundleItemIdentifier(), $bundledItemTransfer->getRelatedBundleItemIdentifier());

        $bundledItemTransfer = $updatedAddToCartItems[1];
        $this->assertSame(150, $bundledItemTransfer->getUnitGrossPrice());
        $this->assertSame(1, $bundledItemTransfer->getQuantity());
        $this->assertEquals($bundleItemTransfer->getBundleItemIdentifier(), $bundledItemTransfer->getRelatedBundleItemIdentifier());

        $bundleItemTransfer = $bundleItems[1];

        $bundledItemTransfer = $updatedAddToCartItems[2];
        $this->assertSame(150, $bundledItemTransfer->getUnitGrossPrice());
        $this->assertSame(1, $bundledItemTransfer->getQuantity());
        $this->assertEquals($bundleItemTransfer->getBundleItemIdentifier(), $bundledItemTransfer->getRelatedBundleItemIdentifier());

        $bundledItemTransfer = $updatedAddToCartItems[3];
        $this->assertSame(150, $bundledItemTransfer->getUnitGrossPrice());
        $this->assertSame(1, $bundledItemTransfer->getQuantity());
        $this->assertEquals($bundleItemTransfer->getBundleItemIdentifier(), $bundledItemTransfer->getRelatedBundleItemIdentifier());
    }

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceProductFacadeInterface|null $priceProductFacadeMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface|null $productFacadeMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleInterface|null $localeFacadeMock
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface|null $productBundleQueryContainerMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpander
     */
    protected function createProductExpanderMock(
        ?ProductBundleToPriceProductFacadeInterface $priceProductFacadeMock = null,
        ?ProductBundleToProductInterface $productFacadeMock = null,
        ?ProductBundleToLocaleInterface $localeFacadeMock = null,
        ?ProductBundleQueryContainerInterface $productBundleQueryContainerMock = null
    ) {

        if ($productBundleQueryContainerMock === null) {
            $productBundleQueryContainerMock = $this->createProductBundleQueryContainerMock();
        }

        if ($priceProductFacadeMock === null) {
            $priceProductFacadeMock = $this->createPriceProductFacadeMock();
        }

        if ($productFacadeMock === null) {
            $productFacadeMock = $this->createProductFacadeMock();
        }

        if ($localeFacadeMock === null) {
            $localeFacadeMock = $this->createLocaleFacadeMock();
        }

        if ($localeFacadeMock === null) {
            $localeFacadeMock = $this->createLocaleFacadeMock();
        }

        $priceFacadeMock = $this->createPriceFacadeMock();

        return $this->getMockBuilder(ProductBundleCartExpander::class)
            ->setConstructorArgs([
                $productBundleQueryContainerMock,
                $priceProductFacadeMock,
                $productFacadeMock,
                $localeFacadeMock,
                $priceFacadeMock,
            ])
            ->setMethods(['findBundledItemsByIdProductConcrete'])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpander
     */
    protected function setupProductExpander()
    {
        $productFacadeMock = $this->createProductFacadeMock();

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productFacadeMock->method('getProductConcrete')->willReturn($productConcreteTransfer);
        $productFacadeMock->method('getLocalizedProductConcreteName')->willReturn('Localized product name');

        $localeFacadeMock = $this->createLocaleFacadeMock();

        $localeTransfer = new LocaleTransfer();
        $localeFacadeMock->method('getCurrentLocale')->willReturn($localeTransfer);

        $priceFacadeMock = $this->createPriceProductFacadeMock();
        $priceFacadeMock->method('findPriceFor')->willReturn(25);

        $productExpanderMock = $this->createProductExpanderMock($priceFacadeMock, $productFacadeMock, $localeFacadeMock);

        return $productExpanderMock;
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer()
    {
        $cartChangeTransfer = new CartChangeTransfer();

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setPriceMode(PriceConfig::PRICE_MODE_GROSS);

        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode('EUR');

        $quoteTransfer->setCurrency($currencyTransfer);
        $cartChangeTransfer->setQuote($quoteTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(2);
        $itemTransfer->setUnitGrossPrice(300);
        $itemTransfer->setUnitPrice(300);
        $itemTransfer->setUnitNetPrice(300);
        $itemTransfer->setSku('sku-123');
        $itemTransfer->setId(1);

        $cartChangeTransfer->addItem($itemTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @param array $fixtures
     * @param \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpander $productExpanderMock
     *
     * @return void
     */
    protected function setupFindBundledItemsByIdProductConcrete(
        array $fixtures,
        PHPUnit_Framework_MockObject_MockObject $productExpanderMock
    ) {

        $productBundleEntity = new SpyProductBundle();
        $productBundleEntity->setIdProductBundle($fixtures['idProductConcrete']);
        $productBundleEntity->setQuantity($fixtures['bundledProductQuantity']);

        $productEntity = new SpyProduct();
        $productEntity->setIdProduct($fixtures['fkBundledProduct']);
        $productEntity->setSku($fixtures['bundledProductSku']);

        $productBundleEntity->setSpyProductRelatedByFkBundledProduct($productEntity);
        $productBundleEntity->setFkBundledProduct($fixtures['fkBundledProduct']);

        $bundledProducts = new ObjectCollection();
        $bundledProducts->append($productBundleEntity);

        $productExpanderMock->method('findBundledItemsByIdProductConcrete')->willReturn($bundledProducts);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected function createProductBundleQueryContainerMock()
    {
        return $this->getMockBuilder(ProductBundleQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceProductFacadeInterface
     */
    protected function createPriceProductFacadeMock()
    {
         return $this->getMockBuilder(ProductBundleToPriceProductFacadeInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface
     */
    protected function createProductFacadeMock()
    {
        return $this->getMockBuilder(ProductBundleToProductInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleInterface
     */
    protected function createLocaleFacadeMock()
    {
        return $this->getMockBuilder(ProductBundleToLocaleInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceInterface
     */
    protected function createPriceFacadeMock()
    {
        return $this->getMockBuilder(ProductBundleToPriceInterface::class)->getMock();
    }
}
