<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ProductBundle\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartItemGroupKeyExpander;
use Spryker\Zed\ProductBundle\Business\ProductBundleFacade;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductBridge;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group ProductBundleFacadeTest
 */
class ProductBundleFacadeTest extends Test
{

    /**
     * @return void
     */
    public function testExpandBundleItemsShouldCreateBundleItemsAndCalculateSplitPrice()
    {
        $bundlePrice = 100;

        $productConcreteTransfer = $this->createProductBundle($bundlePrice);

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote(new QuoteTransfer());

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(1);
        $itemTransfer->setUnitGrossPrice($productConcreteTransfer->getPrice()->getPrice());
        $itemTransfer->setId($productConcreteTransfer->getIdProductConcrete());
        $itemTransfer->setSku($productConcreteTransfer->getSku());

        $cartChangeTransfer->addItem($itemTransfer);

        $productBundleFacade = $this->createProductBundleFacade();
        $cartChangeTransfer = $productBundleFacade->expandBundleItems($cartChangeTransfer);

        $quoteTransfer = $cartChangeTransfer->getQuote();

        $this->assertCount(3, $cartChangeTransfer->getItems());
        $this->assertCount(1, $quoteTransfer->getBundleItems());

        $bundledItemTransfer = $quoteTransfer->getBundleItems()[0];
        $bundleItemIdentifier = $bundledItemTransfer->getBundleItemIdentifier();

        $this->assertSame($bundlePrice, $bundledItemTransfer->getUnitGrossPrice());

        $itemTransfer = $cartChangeTransfer->getItems()[0];
        $this->assertSame(20, $itemTransfer->getUnitGrossPrice());
        $this->assertSame($bundleItemIdentifier, $itemTransfer->getRelatedBundleItemIdentifier());

        $itemTransfer = $cartChangeTransfer->getItems()[1];
        $itemTransfer->getUnitGrossPrice();
        $this->assertSame(40, $itemTransfer->getUnitGrossPrice());
        $this->assertSame($bundleItemIdentifier, $itemTransfer->getRelatedBundleItemIdentifier());

        $itemTransfer = $cartChangeTransfer->getItems()[2];
        $itemTransfer->getUnitGrossPrice();
        $this->assertSame(40, $itemTransfer->getUnitGrossPrice());
        $this->assertSame($bundleItemIdentifier, $itemTransfer->getRelatedBundleItemIdentifier());

    }

    /**
     * @return void
     */
    public function testExpandCartItemGroupKeyShouldAppendBundleToKey()
    {
        $productBundleFacade = $this->createProductBundleFacade();

        $cartChangeTransfer = new CartChangeTransfer();

        $groupKeyBefore = 'test1';
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setGroupKey($groupKeyBefore);
        $itemTransfer->setRelatedBundleItemIdentifier('related1');

        $cartChangeTransfer->addItem($itemTransfer);

        $cartChangeTransfer = $productBundleFacade->expandBundleCartItemGroupKey($cartChangeTransfer);

        $itemTransfer = $cartChangeTransfer->getItems()[0];

        $this->assertEquals(
            $groupKeyBefore . ProductBundleCartItemGroupKeyExpander::GROUP_KEY_DELIMITER . $itemTransfer->getRelatedBundleItemIdentifier() ,
            $itemTransfer->getGroupKey()
        );

    }

    /**
     * @return void
     */
    public function testPostSaveCartUpdateWhenBundleRemoveShouldReturnQuoteWithouBundles()
    {
        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setBundleItemIdentifier('bundleid');

        $quoteTransfer->addBundleItem($itemTransfer);

        $productBundleFacade = $this->createProductBundleFacade();
        $quoteTransfer = $productBundleFacade->postSaveCartUpdateBundles($quoteTransfer);

        $this->assertCount(0, $quoteTransfer->getBundleItems());

    }

    /**
     * @return void
     */
    public function testPreCheckCartAvailability()
    {
        $productConcreteTransfer = $this->createProductBundle(100);

        $productBundleFacade = $this->createProductBundleFacade();

        $cartChangeTransfer = new CartChangeTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($productConcreteTransfer->getSku());
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem($itemTransfer);

        $cartChangeTransfer->setQuote($quoteTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($productConcreteTransfer->getSku());
        $itemTransfer->setQuantity(1);

        $cartChangeTransfer->addItem($itemTransfer);

        $productBundleFacade->preCheckCartAvailability($cartChangeTransfer);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundleFacade
     */
    protected function createProductBundleFacade()
    {
        return new ProductBundleFacade();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductBridge
     */
    protected function createProductBundleToProductBridge()
    {
        return new ProductBundleToProductBridge($this->getLocator()->product()->facade());
    }

    /**
     * @return \Generated\Zed\Ide\AutoCompletion|\Spryker\Shared\Kernel\LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @param int $price
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProduct($price)
    {
        $productFacade = $this->createProductBundleToProductBridge();
        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setSku(123 . rand(1, 9999));

        $priceProductTransfer = new PriceProductTransfer();
        $priceProductTransfer->setPrice($price);

        $productAbstractTransfer->setPrice($priceProductTransfer);
        $productAbstractTransfer->setAttributes([]);

        $concreteProductCollection = [];
        $productConcreteTransfer = new ProductConcreteTransfer();

        $stockProductTransfer = new StockProductTransfer();
        $stockProductTransfer->setSku($productAbstractTransfer->getSku());
        $stockProductTransfer->setQuantity(10);
        $stockProductTransfer->setStockType('Warehouse1');
        $productConcreteTransfer->addStock($stockProductTransfer);

        $productConcreteTransfer->setSku($productAbstractTransfer->getSku());
        $productConcreteTransfer->setIsActive(false);
        $productConcreteTransfer->setPrice($productAbstractTransfer->getPrice());
        $productConcreteTransfer->setLocalizedAttributes($productAbstractTransfer->getLocalizedAttributes());
        $concreteProductCollection[] = $productConcreteTransfer;

        $productFacade->addProduct($productAbstractTransfer, $concreteProductCollection);

        return $productConcreteTransfer;
    }

    /**
     * @param int $bundlePrice
     *
     * @return ProductConcreteTransfer
     */
    protected function createProductBundle($bundlePrice)
    {
        $productConcreteTransferToAssign1 = $this->createProduct(50);
        $productConcreteTransferToAssign2 = $this->createProduct(100);

        $productBundleTransfer = new ProductBundleTransfer();

        $bundledProductTransfer = new ProductForBundleTransfer();
        $bundledProductTransfer->setQuantity(1);
        $bundledProductTransfer->setIdProductConcrete($productConcreteTransferToAssign1->getIdProductConcrete());
        $productBundleTransfer->addBundledProduct($bundledProductTransfer);

        $bundledProductTransfer = new ProductForBundleTransfer();
        $bundledProductTransfer->setQuantity(2);
        $bundledProductTransfer->setIdProductConcrete($productConcreteTransferToAssign2->getIdProductConcrete());
        $productBundleTransfer->addBundledProduct($bundledProductTransfer);

        $productConcreteTransfer = $this->createProduct($bundlePrice);
        $productConcreteTransfer->setProductBundle($productBundleTransfer);

        $productBundleFacade = $this->createProductBundleFacade();
        $productBundleFacade->saveBundledProducts($productConcreteTransfer);

        return $productConcreteTransfer;
    }

}

