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
use Spryker\Shared\Availability\AvailabilityConstants;

class ProductBundleCheckoutAvailabilityCheck extends BasePreCheck implements ProductBundleCheckoutAvailabilityCheckInterface
{
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
        $checkoutErrorMessages = $this->checkAvailabilityErrorMessages(
            $checkoutResponseTransfer->getErrors(),
            $this->getCheckoutAvailabilityFailedItems($quoteTransfer)
        );

        if (count($checkoutErrorMessages) === 0) {
            return true;
        }

        $checkoutResponseTransfer
            ->setIsSuccess(false)
            ->setErrors($checkoutErrorMessages);

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
            $unavailableCheckoutBundledItems = $this->getUnavailableCheckoutBundledItems($itemsInCart, $bundleItemTransfer, $storeTransfer);

            if (!empty($unavailableCheckoutBundledItems)) {
                foreach ($unavailableCheckoutBundledItems as $unavailableCheckoutBundledItem) {
                    $checkoutErrorMessages[] = $this->createCheckoutResponseTransfer($unavailableCheckoutBundledItem);
                }
            }
        }
        return $checkoutErrorMessages;
    }

    /**
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutResponseTransfer(array $parameters)
    {
        $checkoutErrorTransfer = new CheckoutErrorTransfer();
        $checkoutErrorTransfer->setMessage(static::ERROR_BUNDLE_ITEM_UNAVAILABLE_TRANSLATION_KEY);
        $checkoutErrorTransfer->setParameters($parameters);

        return $checkoutErrorTransfer;
    }

    /**
     * @param \ArrayObject $currentCartItems
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array
     */
    protected function getUnavailableCheckoutBundledItems(
        ArrayObject $currentCartItems,
        ItemTransfer $itemTransfer,
        StoreTransfer $storeTransfer
    ) {
        $unavailableCheckoutBundledItems = [];
        $bundledItems = $this->findBundledProducts($itemTransfer->getSku());

        foreach ($bundledItems as $productBundleEntity) {
            $bundledProductConcreteEntity = $productBundleEntity->getSpyProductRelatedByFkBundledProduct();

            $sku = $bundledProductConcreteEntity->getSku();
            if (!$this->checkIfItemIsSellable($currentCartItems, $sku, $storeTransfer)) {
                $unavailableCheckoutBundledItems[] = [
                    static::ERROR_BUNDLE_ITEM_UNAVAILABLE_PARAMETER_BUNDLE_SKU => $itemTransfer->getSku(),
                    static::ERROR_BUNDLE_ITEM_UNAVAILABLE_PARAMETER_PRODUCT_SKU => $sku,
                ];
            }
        }

        return $unavailableCheckoutBundledItems;
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

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CheckoutErrorTransfer[] $availabilityErrorMessages
     * @param \ArrayObject|\Generated\Shared\Transfer\CheckoutErrorTransfer[] $productBundleErrorMessages
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CheckoutErrorTransfer[]
     */
    protected function checkAvailabilityErrorMessages(ArrayObject $availabilityErrorMessages, ArrayObject $productBundleErrorMessages): ArrayObject
    {
        $processedErrorMessages = [];

        foreach ($availabilityErrorMessages as $availabilityErrorMessage) {
            if ($availabilityErrorMessage->getErrorType() !== AvailabilityConstants::ERROR_TYPE_AVAILABILITY
            || !$this->hasRelatedAvailabilityErrorMessage($availabilityErrorMessage, $productBundleErrorMessages)) {
                $processedErrorMessages[] = $availabilityErrorMessage;
            }
        }

        $processedErrorMessages = array_merge(
            $processedErrorMessages,
            $productBundleErrorMessages->getArrayCopy()
        );

        return new ArrayObject($processedErrorMessages);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $availabilityErrorMessage
     * @param \ArrayObject|\Generated\Shared\Transfer\CheckoutErrorTransfer[] $productBundleErrorMessages
     *
     * @return bool
     */
    protected function hasRelatedAvailabilityErrorMessage(
        CheckoutErrorTransfer $availabilityErrorMessage,
        ArrayObject $productBundleErrorMessages
    ): bool {
        $availabilityErrorMessageSku = $this->getAvailabilityErrorMessageSku($availabilityErrorMessage);
        foreach ($productBundleErrorMessages as $productBundleErrorMessage) {
            $productBundleErrorMessageSku = $this->getProductBundleErrorMessageProductSku($productBundleErrorMessage);

            if ($availabilityErrorMessageSku === $productBundleErrorMessageSku) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $availabilityErrorMessage
     *
     * @return string
     */
    protected function getAvailabilityErrorMessageSku(CheckoutErrorTransfer $availabilityErrorMessage): string
    {
        $availabilityErrorMessageParameters = $availabilityErrorMessage->getParameters();

        return $availabilityErrorMessageParameters[AvailabilityConstants::CHECKOUT_PRODUCT_UNAVAILABLE_PARAMETER_SKU];
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $productBundleErrorMessage
     *
     * @return string
     */
    protected function getProductBundleErrorMessageProductSku(CheckoutErrorTransfer $productBundleErrorMessage): string
    {
        $productBundleErrorMessageParameters = $productBundleErrorMessage->getParameters();

        return $productBundleErrorMessageParameters[static::ERROR_BUNDLE_ITEM_UNAVAILABLE_PARAMETER_PRODUCT_SKU];
    }
}
