<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class ProductBundleCheckoutAvailabilityCheck extends BasePreCheck implements ProductBundleCheckoutAvailabilityCheckInterface
{
    const CHECKOUT_PRODUCT_UNAVAILABLE_TRANSLATION_KEY = 'product.unavailable';

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
     * @return array|\ArrayObject
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
            if (!$this->isAllCheckoutBundledItemsAvailable($itemsInCart, $bundledItems, $storeTransfer)) {
                $checkoutErrorMessages[] = $this->createCheckoutResponseTransfer();
            }
        }
        return $checkoutErrorMessages;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutResponseTransfer()
    {
        $checkoutErrorTransfer = new CheckoutErrorTransfer();
        $checkoutErrorTransfer->setMessage(static::CHECKOUT_PRODUCT_UNAVAILABLE_TRANSLATION_KEY);

        return $checkoutErrorTransfer;
    }

    /**
     * @param \ArrayObject $currentCartItems
     * @param mixed|mixed[]|\Orm\Zed\ProductBundle\Persistence\SpyProductBundle[]|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection $bundledItems
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isAllCheckoutBundledItemsAvailable(
        ArrayObject $currentCartItems,
        ObjectCollection $bundledItems,
        StoreTransfer $storeTransfer
    ) {
        foreach ($bundledItems as $productBundleEntity) {
            $bundledProductConcreteEntity = $productBundleEntity->getSpyProductRelatedByFkBundledProduct();

            $sku = $bundledProductConcreteEntity->getSku();
            if (!$this->checkIfItemIsSellable($currentCartItems, $sku, $storeTransfer)) {
                return false;
            }
        }

        return true;
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
