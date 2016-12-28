<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class ProductBundleCartExpander implements ProductBundleCartExpanderInterface
{

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

        $cartChangeItems = new \ArrayObject();
        $quoteTransfer = $cartChangeTransfer->getQuote();

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {

            $itemTransfer->requireId();

            $bundledProducts = $this->productBundleQueryContainer
                ->queryBundleProduct($itemTransfer->getId())
                ->find();

            if ($bundledProducts->count() == 0) {
                $cartChangeItems->append($itemTransfer);
                continue;
            };

            $itemTransfer->requireUnitGrossPrice()
                ->requireQuantity();

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
    protected function buildBundle(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer, ObjectCollection $bundledProducts)
    {
        $addToCartItems = [];
        $quantity = $itemTransfer->getQuantity();
        for ($i = 0; $i < $quantity; $i++) {

            $bundleItemTransfer = clone $itemTransfer;
            $bundleItemTransfer->setQuantity(1);

            $bundleItemIdentifier = $this->buildBundleIdentifier($bundleItemTransfer, $quoteTransfer->getBundleItems());

            $bundleItemTransfer->setBundleItemIdentifier($bundleItemIdentifier);
            $bundleItemTransfer->setGroupKey($bundleItemIdentifier);

            $quoteTransfer->addBundleItem($bundleItemTransfer);

            $bundledItems = $this->createBundledItemsTransferCollection($bundledProducts, $bundleItemIdentifier);

            $this->distributeBundlePriceAmount($bundledItems, $itemTransfer->getUnitGrossPrice());

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
        foreach ($bundledProducts as $productBundleEntity) {
            $quantity = $productBundleEntity->getQuantity();
            for ($i = 0; $i < $quantity; $i++) {
                $bundledItems[] = $this->createBundledItemTransfer($productBundleEntity, $bundleItemIdentifier);
            }
        }
        return $bundledItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $bundleItems
     *
     * @return string
     */
    protected function buildBundleIdentifier(ItemTransfer $itemTransfer, \ArrayObject $bundleItems)
    {
        if (!$bundleItems->count()) {
            return $itemTransfer->getSku();
        }

        return $itemTransfer->getSku() . '_' . time() . rand(1, 901);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $bundledProducts
     * @param int $bundleUnitPrice
     *
     * @return void
     */
    protected function distributeBundlePriceAmount(array $bundledProducts, $bundleUnitPrice)
    {
        $totalBundleItemAmount = $this->calculateTotalBundledAmount($bundledProducts);

        $roundingError = 0;
        $priceRatio = $bundleUnitPrice / $totalBundleItemAmount;
        foreach ($bundledProducts as $itemTransfer) {

            $itemTransfer->requireUnitGrossPrice();

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

        $productConcreteTransfer = $this->productFacade->getProductConcrete(
            $bundledConcreteProductEntity->getSku()
        );

        $localizedProductName = $this->productFacade->getLocalizedProductConcreteName(
            $productConcreteTransfer,
            $this->localeFacade->getCurrentLocale()
        );

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setId($productConcreteTransfer->getIdProductConcrete())
            ->setSku($productConcreteTransfer->getSku())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setAbstractSku($productConcreteTransfer->getAbstractSku())
            ->setName($localizedProductName)
            ->setUnitGrossPrice(
                $this->priceFacade->getPriceBySku($bundledConcreteProductEntity->getSku())
            )
            ->setQuantity(1)
            ->setRelatedBundleItemIdentifier($bundleItemIdentifier);

        return $itemTransfer;
    }

    /**
     * @param array|\Generated\Shared\Transfer\ItemTransfer[] $bundledProducts
     *
     * @return int
     */
    protected function calculateTotalBundledAmount(array $bundledProducts)
    {
        $totalBundleItemAmount = (int)array_reduce($bundledProducts, function ($total, ItemTransfer $itemTransfer) {
            $total += $itemTransfer->getUnitGrossPrice();
            return $total;
        });

        return $totalBundleItemAmount;
    }

}
