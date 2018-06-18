<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class ProductBundleCheckoutAvailabilityCheck extends BasePreCheck implements ProductBundleCheckoutAvailabilityCheckInterface
{
    protected const CHECKOUT_PRODUCT_BUNDLE_UNAVAILABLE_TRANSLATION_KEY = 'product.unavailable';
    protected const CHECKOUT_PRODUCT_BUNDLE_UNAVAILABLE_PARAMETER_BUNDLE_SKU = '%bundleSku%';
    protected const CHECKOUT_PRODUCT_BUNDLE_UNAVAILABLE_PARAMETER_PRODUCT_SKU = '%productSku%';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCheckoutAvailability(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $checkoutErrorMessages = $this->getCheckoutAvailabilityFailedItems($quoteTransfer);

        if (count($checkoutErrorMessages) === 0) {
            return true;
        }

        $checkoutResponseTransfer->setIsSuccess(false);
        foreach ($checkoutErrorMessages as $checkoutErrorTransfer) {
            $checkoutResponseTransfer->addError($checkoutErrorTransfer);
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject
     */
    protected function getCheckoutAvailabilityFailedItems(QuoteTransfer $quoteTransfer)
    {
        $storeTransfer = $quoteTransfer->getStore();
        $storeTransfer->requireName();

        $storeTransfer = $this->storeFacade->getStoreByName($storeTransfer->getName());

        $checkoutErrorMessages = new ArrayObject();
        $uniqueBundleItems = $this->getUniqueBundleItems($quoteTransfer);
        $itemsInCart = $quoteTransfer->getItems();

        foreach ($uniqueBundleItems as $bundleItemTransfer) {
            $bundledItems = $this->findBundledProducts($bundleItemTransfer->getSku());

            $unavailableItems = $this->getUnavailableCheckoutBundledItems($itemsInCart, $bundledItems, $storeTransfer);
            if (!empty($unavailableItems)) {
                foreach ($unavailableItems as $unavailableItem) {
                    $checkoutErrorMessages[] = $this->createCheckoutResponseTransfer(
                        $bundleItemTransfer,
                        $unavailableItem
                    );
                }
            }
        }

        return $checkoutErrorMessages;
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(): MessageTransfer
    {
        return new MessageTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer(): CheckoutErrorTransfer
    {
        return new CheckoutErrorTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $productItemTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutResponseTransfer(ItemTransfer $bundleItemTransfer, ItemTransfer $productItemTransfer): CheckoutErrorTransfer
    {
        $messageTransfer = $this->createMessageTransfer()
            ->setValue(static::CHECKOUT_PRODUCT_BUNDLE_UNAVAILABLE_TRANSLATION_KEY)
            ->setParameters([
                static::CHECKOUT_PRODUCT_BUNDLE_UNAVAILABLE_PARAMETER_BUNDLE_SKU => $bundleItemTransfer->getSku(),
                static::CHECKOUT_PRODUCT_BUNDLE_UNAVAILABLE_PARAMETER_PRODUCT_SKU => $productItemTransfer->getSku(),
            ]);

        $checkoutErrorTransfer = $this->createCheckoutErrorTransfer()
            ->setDetailedMessage($messageTransfer);

        return $checkoutErrorTransfer;
    }

    /**
     * @param \ArrayObject $currentCartItems
     * @param \Orm\Zed\ProductBundle\Persistence\SpyProductBundle[]|\Propel\Runtime\Collection\ObjectCollection $bundledItems
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundle[]
     */
    protected function getUnavailableCheckoutBundledItems(
        ArrayObject $currentCartItems,
        ObjectCollection $bundledItems,
        StoreTransfer $storeTransfer
    ): array {
        $unavailableProducts = [];

        foreach ($bundledItems as $productBundleEntity) {
            $bundledProductConcreteEntity = $productBundleEntity->getSpyProductRelatedByFkBundledProduct();

            $sku = $bundledProductConcreteEntity->getSku();
            if (!$this->checkIfItemIsSellable($currentCartItems, $sku, $storeTransfer)) {
                $unavailableProducts[] = $productBundleEntity;
            }
        }

        return $unavailableProducts;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getUniqueBundleItems(QuoteTransfer $quoteTransfer)
    {
        $uniqueBundledItems = [];
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            if (!isset($uniqueBundledItems[$bundleItemTransfer->getSku()])) {
                $uniqueBundledItems[$bundleItemTransfer->getSku()] = $bundleItemTransfer;
                continue;
            }
        }

        return $uniqueBundledItems;
    }
}
