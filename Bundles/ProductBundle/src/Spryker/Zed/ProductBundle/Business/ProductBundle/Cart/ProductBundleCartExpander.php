<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleInterface;

class ProductBundleCartExpander
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
        $cartChangeItems = new \ArrayObject();
        $quoteTransfer = $cartChangeTransfer
            ->requireQuote()
            ->getQuote();

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {

            $bundledProducts = $this->productBundleQueryContainer
                ->queryBundleProduct($itemTransfer->getId())
                ->find();

            if (count($bundledProducts) == 0) {
                $cartChangeItems->append($itemTransfer);
                continue;
            };

            $itemTransfer->requireUnitGrossPrice()
                ->requireQuantity();

            for ($i = 0; $i < $itemTransfer->getQuantity(); $i++) {

                $bundleItemTransfer = clone $itemTransfer;
                $bundleItemTransfer->setQuantity(1);

                $bundleItemIdentifier = $this->buildBundleIdentifier($bundleItemTransfer, $quoteTransfer->getBundleItems());

                $bundleItemTransfer->setBundleItemIdentifier($bundleItemIdentifier);
                $bundleItemTransfer->setGroupKey($bundleItemIdentifier);

                $quoteTransfer->addBundleItem($bundleItemTransfer);

                $bundledItems = $this->createBundledItems($bundledProducts, $bundleItemIdentifier);

                $this->distributeBundlePriceAmount($bundledItems, $itemTransfer->getUnitGrossPrice());

                foreach ($bundledItems as $bundledItemTransfer) {
                    $cartChangeItems->append($bundledItemTransfer);
                }

            }

        }

        $cartChangeTransfer->setItems($cartChangeItems);
        $cartChangeTransfer->setQuote($quoteTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @param ObjectCollection $bundledProducts
     * @param string $bundleItemIdentifier
     *
     * @return array
     */
    protected function createBundledItems(ObjectCollection $bundledProducts, $bundleItemIdentifier)
    {
        $bundledItems = [];
        foreach ($bundledProducts as $productBundleEntity) {
            for ($i = 0; $i < $productBundleEntity->getQuantity(); $i++) {
                $bundledItems[] = $this->createBundledItemTransfer($productBundleEntity, $bundleItemIdentifier);
            }
        }
        return $bundledItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \ArrayObject|ItemTransfer[] $bundleItems
     *
     * @return string
     */
    protected function buildBundleIdentifier(ItemTransfer $itemTransfer, \ArrayObject $bundleItems)
    {
        if (!$bundleItems->count()) {
            return $itemTransfer->getSku();
        }

        return $itemTransfer->getSku() . '_' . time() . rand(1, 999);
    }

    /**
     * @param ItemTransfer[] $bundledProducts
     * @param int $bundleUnitPrice
     *
     * @return void
     */
    protected function distributeBundlePriceAmount(array $bundledProducts, $bundleUnitPrice)
    {
        $totalBundleItemAmount = array_reduce($bundledProducts, function($total, ItemTransfer $itemTransfer) {
            $total += $itemTransfer->getUnitGrossPrice();
            return $total;
        });

        $roundingError = 0;
        $priceRatio = $bundleUnitPrice / $totalBundleItemAmount;
        foreach ($bundledProducts as $itemTransfer) {

            $unitDistributedPrice = 0;
            $priceBefore = (($itemTransfer->getUnitGrossPrice()) * $priceRatio) + $roundingError;
            $priceRounded = (int)round($priceBefore);
            $roundingError = $priceBefore - $priceRounded;

            $unitDistributedPrice += $priceRounded;

            $itemTransfer->setUnitGrossPrice($unitDistributedPrice);
        }
    }

    /**
     * @param SpyProductBundle $bundleProductEntity
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


}
