<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Availability\AvailabilityConfig;

class ProductsAvailableCheckoutPreCondition implements ProductsAvailableCheckoutPreConditionInterface
{
    protected const CHECKOUT_PRODUCT_UNAVAILABLE_TRANSLATION_KEY = 'product.unavailable';

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
        $groupedItemQuantities = $this->groupItemsBySku($quoteTransfer->getItems());

        foreach ($groupedItemQuantities as $sku => $quantity) {
            if ($this->isProductSellable($sku, $quantity, $storeTransfer) === true) {
                continue;
            }
            $this->addAvailabilityErrorToCheckoutResponse($checkoutResponse, $sku);
            $isPassed = false;
        }

        return $isPassed;
    }

    /**
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isProductSellable(string $sku, Decimal $quantity, StoreTransfer $storeTransfer): bool
    {
        return $this->sellable->isProductSellableForStore($sku, $quantity, $storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \Spryker\DecimalObject\Decimal[] [string, \Spryker\DecimalObject\Decimal]
     */
    private function groupItemsBySku(iterable $items): array
    {
        /** @var \Spryker\DecimalObject\Decimal[] $result */
        $result = [];
        foreach ($items as $itemTransfer) {
            $sku = $itemTransfer->getSku();

            if (!isset($result[$sku])) {
                $result[$sku] = new Decimal(0);
            }

            $result[$sku] = $result[$sku]->add($itemTransfer->getQuantity());
        }

        return $result;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer()
    {
        return new CheckoutErrorTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     * @param string $sku
     *
     * @return void
     */
    protected function addAvailabilityErrorToCheckoutResponse(CheckoutResponseTransfer $checkoutResponse, string $sku): void
    {
        $checkoutErrorTransfer = $this->createCheckoutErrorTransfer();
        $checkoutErrorTransfer
            ->setErrorCode($this->availabilityConfig->getProductUnavailableErrorCode())
            ->setMessage(static::CHECKOUT_PRODUCT_UNAVAILABLE_TRANSLATION_KEY)
            ->setErrorType(
                $this->availabilityConfig->getAvailabilityErrorType()
            )
            ->setParameters([
                $this->availabilityConfig->getAvailabilityProductSkuParameter() => $sku,
            ]);

        $checkoutResponse
            ->addError($checkoutErrorTransfer)
            ->setIsSuccess(false);
    }
}
