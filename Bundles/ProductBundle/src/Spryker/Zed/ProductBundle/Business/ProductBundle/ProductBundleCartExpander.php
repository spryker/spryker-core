<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
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
        $quoteTransfer = $cartChangeTransfer->getQuote();
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {

            $bundledProducts = $this->productBundleQueryContainer
                ->queryBundledProduct($itemTransfer->getId())
                ->find();

            if (count($bundledProducts) == 0) {
                $cartChangeItems->append($itemTransfer);
                continue;
            };

            $itemTransfer->requireUnitGrossPrice();

            $bundleItemTransfer = clone $itemTransfer;

            $relatedBundleIdentifier = $this->buildBundleIdentifier($bundleItemTransfer, $quoteTransfer->getBundleProducts());

            $bundleItemTransfer->setBundleItemIdentifier($relatedBundleIdentifier);
            $bundleItemTransfer->setGroupKey($relatedBundleIdentifier);

            $quoteTransfer->addBundleProduct($bundleItemTransfer);

            $bundleUnitPrice = $itemTransfer->getUnitGrossPrice();

            $bundleProducts = [];
            $totalBundleItemAmount = 0;
            foreach ($bundledProducts as $productBundleEntity) {

                $productEntity = $productBundleEntity->getSpyProductRelatedByFkBundledProduct();

                for ($i = 0; $i < $productBundleEntity->getQuantity(); $i++) {

                    $productConcreteTransfer = $this->productFacade->getProductConcrete($productEntity->getSku());

                    $localizedProductName = $this->productFacade->getLocalizedProductConcreteName(
                        $productConcreteTransfer,
                        $this->localeFacade->getCurrentLocale()
                    );

                    $itemTransfer = new ItemTransfer();
                    $itemTransfer->setId($productConcreteTransfer->getIdProductConcrete())
                        ->setSku($productConcreteTransfer->getSku())
                        ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
                        ->setAbstractSku($productConcreteTransfer->getAbstractSku())
                        ->setName($localizedProductName);

                    if ($i == 0) {
                        $itemTransfer->setProductOptions($bundleItemTransfer->getProductOptions());
                    }

                    $itemTransfer->setUnitGrossPrice(
                        $this->priceFacade->getPriceBySku($productEntity->getSku())
                    );
                    $itemTransfer->setQuantity(1);
                    $itemTransfer->setRelatedBundleItemIdentifier($relatedBundleIdentifier);

                    $totalBundleItemAmount += $itemTransfer->getUnitGrossPrice();

                    $cartChangeItems->append($itemTransfer);
                    $bundleProducts[] = $itemTransfer;
                }
            }

            $roundingError = 0;
            $priceRatio = $bundleUnitPrice / $totalBundleItemAmount;
            foreach ($bundleProducts as $itemTransfer) {

                $unitDistributedPrice = 0;
                $priceBefore = (($itemTransfer->getUnitGrossPrice()) * $priceRatio) + $roundingError;
                $priceRounded = round($priceBefore);
                $roundingError = $priceBefore - $priceRounded;

                $unitDistributedPrice += $priceRounded;

                $itemTransfer->setUnitGrossPrice($unitDistributedPrice);
            }

        }

        $cartChangeTransfer->setItems($cartChangeItems);
        $cartChangeTransfer->setQuote($quoteTransfer);

        return $cartChangeTransfer;
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
}
