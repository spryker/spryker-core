<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Cart;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use OutOfBoundsException;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class ProductBundleCartExpander implements ProductBundleCartExpanderInterface
{

    const BUNDLE_IDENTIFIER_DELIMITER = '_';

    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected $productBundleQueryContainer;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var array
     */
    protected static $productConcreteCache = [];

    /**
     * @var array
     */
    protected static $localizedProductNameCache = [];

    /**
     * @var array
     */
    protected static $productPriceCache = [];

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceInterface $priceFacade
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface $productFacade
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleInterface $localeFacade
     */
    public function __construct(
        ProductBundleQueryContainerInterface $productBundleQueryContainer,
        ProductBundleToPriceInterface $priceFacade,
        ProductBundleToProductInterface $productFacade,
        ProductBundleToLocaleInterface $localeFacade
    ) {
        $this->productBundleQueryContainer = $productBundleQueryContainer;
        $this->priceFacade = $priceFacade;
        $this->productFacade = $productFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandBundleItems(CartChangeTransfer $cartChangeTransfer)
    {
        $cartChangeTransfer->requireQuote()
            ->requireItems();

        $cartChangeItems = new ArrayObject();
        $quoteTransfer = $cartChangeTransfer->getQuote();

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier()) {
                $cartChangeItems->append($itemTransfer);
                continue;
            }

            $itemTransfer->requireId()
                ->requireUnitGrossPrice()
                ->requireQuantity();

            $bundledProducts = $this->findBundledItemsByIdProductConcrete($itemTransfer->getId());

            if ($bundledProducts->count() == 0) {
                $cartChangeItems->append($itemTransfer);
                continue;
            };

            $addToCartItems = $this->buildBundle($itemTransfer, $quoteTransfer, $bundledProducts);

            foreach ($addToCartItems as $bundledItemTransfer) {
                $cartChangeItems->append($bundledItemTransfer);
            }
        }

        $cartChangeTransfer->setItems($cartChangeItems);
        $cartChangeTransfer->setQuote($quoteTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Propel\Runtime\Collection\ObjectCollection $bundledProducts
     *
     * @return array
     */
    protected function buildBundle(
        ItemTransfer $itemTransfer,
        QuoteTransfer $quoteTransfer,
        ObjectCollection $bundledProducts
    ) {
        $addToCartItems = [];
        $quantity = $itemTransfer->getQuantity();

        $productOptions = $itemTransfer->getProductOptions();
        for ($i = 0; $i < $quantity; $i++) {

            $bundleItemTransfer = clone $itemTransfer;
            $bundleItemTransfer->setQuantity(1);

            $bundleItemIdentifier = $this->buildBundleIdentifier($bundleItemTransfer);
            $bundleItemTransfer->setBundleItemIdentifier($bundleItemIdentifier);
            $bundleItemTransfer->setGroupKey($bundleItemIdentifier);

            $quoteTransfer->addBundleItem($bundleItemTransfer);

            $bundledItems = $this->createBundledItemsTransferCollection($bundledProducts, $bundleItemIdentifier);

            $lastBundledItemTransfer = $bundledItems[count($bundledItems) - 1];
            $lastBundledItemTransfer->setProductOptions($productOptions);

            $this->distributeBundleUnitGrossPrice($bundledItems, $itemTransfer->getUnitGrossPrice());

            $addToCartItems = array_merge($addToCartItems, $bundledItems);
        }

        return $addToCartItems;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $bundledProducts
     * @param string $bundleItemIdentifier
     *
     * @return array
     */
    protected function createBundledItemsTransferCollection(ObjectCollection $bundledProducts, $bundleItemIdentifier)
    {
        $bundledItems = [];
        foreach ($bundledProducts as $index => $productBundleEntity) {
            $quantity = $productBundleEntity->getQuantity();
            for ($i = 0; $i < $quantity; $i++) {
                $bundledItems[] = $this->createBundledItemTransfer($productBundleEntity, $bundleItemIdentifier);
            }
        }
        return $bundledItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function buildBundleIdentifier(ItemTransfer $itemTransfer)
    {
        $itemTransfer->requireSku();

        return $itemTransfer->getSku() . static::BUNDLE_IDENTIFIER_DELIMITER . uniqid(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $bundledProducts
     * @param int $bundleUnitGrossPrice
     *
     * @throws \OutOfBoundsException
     *
     * @return void
     */
    protected function distributeBundleUnitGrossPrice(array $bundledProducts, $bundleUnitGrossPrice)
    {
        $totalBundledItemUnitGrossPrice = $this->calculateBundleTotalUnitGrossPrice($bundledProducts);

        $roundingError = 0;
        $priceRatio = $bundleUnitGrossPrice / $totalBundledItemUnitGrossPrice;
        foreach ($bundledProducts as $itemTransfer) {

            $itemTransfer->requireUnitGrossPrice();

            if ($itemTransfer->getUnitGrossPrice() <= 0) {
                throw new OutOfBoundsException("Invalid ItemTransfer:unitGrossPrice given, natural integer expected.");
            }

            $priceBeforeRound = (($itemTransfer->getUnitGrossPrice()) * $priceRatio) + $roundingError;
            $priceRounded = (int)round($priceBeforeRound);
            $roundingError = $priceBeforeRound - $priceRounded;

            $itemTransfer->setUnitGrossPrice($priceRounded);
        }
    }

    /**
     * @param \Orm\Zed\ProductBundle\Persistence\SpyProductBundle $bundleProductEntity
     * @param string $bundleItemIdentifier
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createBundledItemTransfer(SpyProductBundle $bundleProductEntity, $bundleItemIdentifier)
    {
        $bundledConcreteProductEntity = $bundleProductEntity->getSpyProductRelatedByFkBundledProduct();

        $productConcreteTransfer = $this->getProductConcreteTransfer(
            $bundledConcreteProductEntity->getSku()
        );

        $localizedProductName = $this->getLocalizedProductName(
            $productConcreteTransfer,
            $this->localeFacade->getCurrentLocale()
        );

        $unitGrossPrice = $this->getProductPrice($bundledConcreteProductEntity->getSku());

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setId($productConcreteTransfer->getIdProductConcrete())
            ->setSku($productConcreteTransfer->getSku())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setAbstractSku($productConcreteTransfer->getAbstractSku())
            ->setName($localizedProductName)
            ->setUnitGrossPrice($unitGrossPrice)
            ->setQuantity(1)
            ->setRelatedBundleItemIdentifier($bundleItemIdentifier);

        return $itemTransfer;
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    protected function getProductPrice($sku)
    {
        if (!isset(static::$productPriceCache[$sku])) {
            static::$productPriceCache[$sku] = $this->priceFacade->getPriceBySku($sku);
        }

         return static::$productPriceCache[$sku];
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function getProductConcreteTransfer($sku)
    {
        if (!isset(static::$productConcreteCache[$sku])) {
            static::$productConcreteCache[$sku] = $this->productFacade->getProductConcrete($sku);
        }

        return static::$productConcreteCache[$sku];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $currentLocaleTransfer
     *
     * @return string
     */
    protected function getLocalizedProductName(
        ProductConcreteTransfer $productConcreteTransfer,
        LocaleTransfer $currentLocaleTransfer
    ) {

        $localeMapKey = $currentLocaleTransfer->getLocaleName() . $productConcreteTransfer->getIdProductConcrete();

        if (!isset(static::$localizedProductNameCache[$localeMapKey])) {
            static::$localizedProductNameCache[$localeMapKey] = $this->productFacade->getLocalizedProductConcreteName(
                $productConcreteTransfer,
                $this->localeFacade->getCurrentLocale()
            );
        }

        return static::$localizedProductNameCache[$localeMapKey];

    }

    /**
     * @param array|\Generated\Shared\Transfer\ItemTransfer[] $bundledProducts
     *
     * @return int
     */
    protected function calculateBundleTotalUnitGrossPrice(array $bundledProducts)
    {
        $totalBundleItemAmount = (int)array_reduce($bundledProducts, function ($total, ItemTransfer $itemTransfer) {
            $total += $itemTransfer->getUnitGrossPrice();
            return $total;
        });

        return $totalBundleItemAmount;
    }

    /**
     * @param int $idProductConrete
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundle[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findBundledItemsByIdProductConcrete($idProductConrete)
    {
        return $this->productBundleQueryContainer
            ->queryBundleProduct($idProductConrete)
            ->find();
    }

}
