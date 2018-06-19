<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Availability\AvailabilityConfig;

class ProductsAvailableCheckoutPreCondition implements ProductsAvailableCheckoutPreConditionInterface
{
    protected const CHECKOUT_PRODUCT_UNAVAILABLE_TRANSLATION_KEY = 'product.unavailable';
    protected const CHECKOUT_PRODUCT_UNAVAILABLE_PARAMETER_SKU = '%sku%';

    /**
     * @var \Spryker\Zed\Availability\Business\Model\SellableInterface
     */
    protected $sellable;

    /**
     * @var \Spryker\Zed\Availability\AvailabilityConfig
     */
    protected $availabilityConfig;

    /**
     * @param \Spryker\Zed\Availability\Business\Model\SellableInterface $sellable
     * @param \Spryker\Zed\Availability\AvailabilityConfig $availabilityConfig
     */
    public function __construct(SellableInterface $sellable, AvailabilityConfig $availabilityConfig)
    {
        $this->sellable = $sellable;
        $this->availabilityConfig = $availabilityConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return bool
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $quoteTransfer->requireStore();

        $isPassed = true;

        $storeTransfer = $quoteTransfer->getStore();

        foreach ($quoteTransfer->getItems() as $quoteItem) {
            if (!$this->isProductSellable($quoteItem, $storeTransfer)) {
                /**
                 * TODO: This check is needed to exclude flash messages for every single product in product bundle.
                 * TODO: Removing this check will cause flash messages being created for ProductBundle + every product inside.
                 * TODO: This check must be REPLACED with something else, because it makes Availability module depends on ProductBundle.
                 * TODO: Replacing check with method_exists check will be not the best solution also.
                 */
                if (!$quoteItem->getRelatedBundleItemIdentifier()) {
                    $this->addAvailabilityErrorToCheckoutResponse($checkoutResponse, $quoteItem);
                }
                $isPassed = false;
            }
        }

        return $isPassed;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $quoteItem
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isProductSellable(ItemTransfer $quoteItem, StoreTransfer $storeTransfer): bool
    {
        return $this->sellable->isProductSellableForStore(
            $quoteItem->getSku(),
            $quoteItem->getQuantity(),
            $storeTransfer
        );
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
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $quoteItem
     *
     * @return void
     */
    protected function addAvailabilityErrorToCheckoutResponse(CheckoutResponseTransfer $checkoutResponseTransfer, ItemTransfer $quoteItem): void
    {
        $messageTransfer = $this->createMessageTransfer()
            ->setValue(static::CHECKOUT_PRODUCT_UNAVAILABLE_TRANSLATION_KEY)
            ->setParameters([
                static::CHECKOUT_PRODUCT_UNAVAILABLE_PARAMETER_SKU => $quoteItem->getSku(),
            ]);

        $checkoutErrorTransfer = $this->createCheckoutErrorTransfer()
            ->setErrorCode($this->availabilityConfig->getProductUnavailableErrorCode())
            ->setDetailedMessage($messageTransfer);

        $checkoutResponseTransfer
            ->setIsSuccess(false)
            ->addError($checkoutErrorTransfer);
    }
}
