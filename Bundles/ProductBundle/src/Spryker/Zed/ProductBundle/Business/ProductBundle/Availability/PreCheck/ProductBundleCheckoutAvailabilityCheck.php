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
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Propel\Runtime\Collection\ObjectCollection;

class ProductBundleCheckoutAvailabilityCheck extends BasePreCheck implements ProductBundleCheckoutAvailabilityCheckInterface
{
    protected const CHECKOUT_PRODUCT_BUNDLE_UNAVAILABLE_TRANSLATION_KEY = 'product_bundle.unavailable';
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

        $this->addErrorMessagesToCheckoutResponseTransfer(
            $checkoutErrorMessages,
            $checkoutResponseTransfer
        )->setIsSuccess(false);

        return false;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CheckoutErrorTransfer[] $checkoutErrorMessages
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function addErrorMessagesToCheckoutResponseTransfer(
        ArrayObject $checkoutErrorMessages,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): CheckoutResponseTransfer {
        foreach ($checkoutErrorMessages as $checkoutErrorTransfer) {
            $checkoutResponseTransfer->addError($checkoutErrorTransfer);
        }

        return $checkoutResponseTransfer;
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

        foreach ($uniqueBundleItems as $bundleItemTransfer) {
            $unavailableProductEntities = $this->getUnavailableCheckoutBundledItems(
                $quoteTransfer->getItems(),
                $this->findBundledProducts($bundleItemTransfer->getSku()),
                $storeTransfer
            );

            if (!empty($unavailableProductEntities)) {
                $this->addBundledItemErrors($unavailableProductEntities, $bundleItemTransfer, $checkoutErrorMessages);
            }
        }

        return $checkoutErrorMessages;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct[] $unavailableProductEntities
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\CheckoutErrorTransfer[] $checkoutErrorMessages
     *
     * @return void
     */
    protected function addBundledItemErrors(
        array $unavailableProductEntities,
        ItemTransfer $bundleItemTransfer,
        ArrayObject $checkoutErrorMessages
    ): void {
        foreach ($unavailableProductEntities as $unavailableProductEntity) {
            $checkoutErrorMessages[] = $this->createCheckoutResponseTransfer(
                $bundleItemTransfer,
                $unavailableProductEntity
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutResponseTransfer(ItemTransfer $bundleItemTransfer, SpyProduct $productEntity): CheckoutErrorTransfer
    {
        return (new CheckoutErrorTransfer())
            ->setMessage(static::CHECKOUT_PRODUCT_BUNDLE_UNAVAILABLE_TRANSLATION_KEY)
            ->setParameters([
                static::CHECKOUT_PRODUCT_BUNDLE_UNAVAILABLE_PARAMETER_BUNDLE_SKU => $bundleItemTransfer->getSku(),
                static::CHECKOUT_PRODUCT_BUNDLE_UNAVAILABLE_PARAMETER_PRODUCT_SKU => $productEntity->getSku(),
            ]);
    }

    /**
     * @param \ArrayObject $currentCartItems
     * @param \Orm\Zed\ProductBundle\Persistence\SpyProductBundle[]|\Propel\Runtime\Collection\ObjectCollection $bundledItems
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct[]
     */
    protected function getUnavailableCheckoutBundledItems(
        ArrayObject $currentCartItems,
        ObjectCollection $bundledItems,
        StoreTransfer $storeTransfer
    ): array {
        $unavailableProductEntities = [];

        foreach ($bundledItems as $productBundleEntity) {
            $bundledProductConcreteEntity = $productBundleEntity->getSpyProductRelatedByFkBundledProduct();

            $sku = $bundledProductConcreteEntity->getSku();
            if (!$this->checkIfItemIsSellable($currentCartItems, $sku, $storeTransfer)) {
                $unavailableProductEntities[] = $bundledProductConcreteEntity;
            }
        }

        return $unavailableProductEntities;
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
