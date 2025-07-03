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
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;

class ProductBundleCheckoutAvailabilityCheck extends BasePreCheck implements ProductBundleCheckoutAvailabilityCheckInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param array<string, string> $skusToSkipIsActiveValidation
     *
     * @return bool
     */
    public function checkCheckoutAvailability(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer,
        array $skusToSkipIsActiveValidation = []
    ) {
        $checkoutErrorMessages = $this->getAvailabilityErrorMessages(
            $checkoutResponseTransfer->getErrors(),
            $this->getCheckoutAvailabilityFailedItems($quoteTransfer, $skusToSkipIsActiveValidation),
        );

        if (count($checkoutErrorMessages) === 0) {
            return true;
        }

        $checkoutResponseTransfer->setIsSuccess(false);
        $checkoutResponseTransfer->setErrors($checkoutErrorMessages);

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<string, string> $skusToSkipIsActiveValidation
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CheckoutErrorTransfer>
     */
    protected function getCheckoutAvailabilityFailedItems(QuoteTransfer $quoteTransfer, array $skusToSkipIsActiveValidation = [])
    {
        $storeTransfer = $quoteTransfer->getStore();
        $storeTransfer->requireName();

        $storeTransfer = $this->storeFacade->getStoreByName($storeTransfer->getName());

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\CheckoutErrorTransfer> $checkoutErrorMessages */
        $checkoutErrorMessages = new ArrayObject();
        $uniqueBundleItems = $this->getUniqueBundleItems($quoteTransfer);
        $itemsInCart = $quoteTransfer->getItems();

        foreach ($uniqueBundleItems as $bundleItemTransfer) {
            $unavailableCheckoutBundledItems = $this->getUnavailableCheckoutBundledItems(
                $itemsInCart,
                $bundleItemTransfer,
                $storeTransfer,
                $quoteTransfer,
                $skusToSkipIsActiveValidation,
            );

            if ($unavailableCheckoutBundledItems) {
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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $currentCartItems
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<string, string> $skusToSkipIsActiveValidation
     *
     * @return array
     */
    protected function getUnavailableCheckoutBundledItems(
        ArrayObject $currentCartItems,
        ItemTransfer $itemTransfer,
        StoreTransfer $storeTransfer,
        QuoteTransfer $quoteTransfer,
        array $skusToSkipIsActiveValidation = []
    ) {
        $unavailableCheckoutBundledItems = [];
        $bundledItems = $this->findBundledProducts($itemTransfer->getSku());

        foreach ($bundledItems as $productBundleEntity) {
            if (
                $this->isProductBundleAvailable(
                    $currentCartItems,
                    $storeTransfer,
                    $productBundleEntity,
                    $quoteTransfer,
                    $skusToSkipIsActiveValidation,
                )
            ) {
                continue;
            }
            $unavailableCheckoutBundledItems[] = [
                static::ERROR_BUNDLE_ITEM_UNAVAILABLE_PARAMETER_BUNDLE_SKU => $itemTransfer->getSku(),
                static::ERROR_BUNDLE_ITEM_UNAVAILABLE_PARAMETER_PRODUCT_SKU => $productBundleEntity->getSpyProductRelatedByFkBundledProduct()->getSku(),
            ];
        }

        return $unavailableCheckoutBundledItems;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ItemTransfer>
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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CheckoutErrorTransfer> $availabilityErrorMessages
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CheckoutErrorTransfer> $productBundleErrorMessages
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CheckoutErrorTransfer>
     */
    protected function getAvailabilityErrorMessages(ArrayObject $availabilityErrorMessages, ArrayObject $productBundleErrorMessages): ArrayObject
    {
        $processedErrorMessages = [];

        foreach ($availabilityErrorMessages as $availabilityErrorMessage) {
            if (
                !$this->hasRelatedAvailabilityErrorMessage($availabilityErrorMessage, $productBundleErrorMessages)
                || $this->isAvailabilityErrorMessage($availabilityErrorMessage)
            ) {
                $processedErrorMessages[] = $availabilityErrorMessage;
            }
        }

        $processedErrorMessages = array_merge(
            $processedErrorMessages,
            $productBundleErrorMessages->getArrayCopy(),
        );

        return new ArrayObject($processedErrorMessages);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $availabilityErrorMessage
     *
     * @return bool
     */
    protected function isAvailabilityErrorMessage(CheckoutErrorTransfer $availabilityErrorMessage): bool
    {
        return $availabilityErrorMessage->getErrorType() === $this->productBundleConfig->getAvailabilityErrorType();
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $availabilityErrorMessage
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CheckoutErrorTransfer> $productBundleErrorMessages
     *
     * @return bool
     */
    protected function hasRelatedAvailabilityErrorMessage(
        CheckoutErrorTransfer $availabilityErrorMessage,
        ArrayObject $productBundleErrorMessages
    ): bool {
        $availabilityErrorMessageSku = $this->findAvailabilityErrorMessageSku($availabilityErrorMessage);
        if ($availabilityErrorMessageSku === null) {
            return false;
        }

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
     * @return string|null
     */
    protected function findAvailabilityErrorMessageSku(CheckoutErrorTransfer $availabilityErrorMessage): ?string
    {
        $availabilityErrorMessageParameters = $availabilityErrorMessage->getParameters();
        $availabilityProductSkuParameter = $this->productBundleConfig->getAvailabilityProductSkuParameter();

        return $availabilityErrorMessageParameters[$availabilityProductSkuParameter] ?? null;
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

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $currentCartItems
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Orm\Zed\ProductBundle\Persistence\SpyProductBundle $productBundleEntity
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<string, string> $skusToSkipIsActiveValidation
     *
     * @return bool
     */
    protected function isProductBundleAvailable(
        ArrayObject $currentCartItems,
        StoreTransfer $storeTransfer,
        SpyProductBundle $productBundleEntity,
        QuoteTransfer $quoteTransfer,
        array $skusToSkipIsActiveValidation = []
    ): bool {
        $productBundleConcreteEntity = $productBundleEntity->getSpyProductRelatedByFkBundledProduct();

        $isItemToSkipIsActiveValidation = isset($skusToSkipIsActiveValidation[$productBundleConcreteEntity->getSku()]);

        return $this->checkIfItemIsSellable($currentCartItems, $productBundleConcreteEntity->getSku(), $storeTransfer, $quoteTransfer)
            && ($productBundleConcreteEntity->getIsActive() || $isItemToSkipIsActiveValidation)
            && ($productBundleEntity->getSpyProductRelatedByFkProduct()->getIsActive() || $isItemToSkipIsActiveValidation);
    }
}
