<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Generated\Shared\Transfer\CartItemQuantityTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
     * @var \Spryker\Zed\AvailabilityExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface[]
     */
    protected $cartItemQuantityCounterStrategyPlugins;

    /**
     * @param \Spryker\Zed\Availability\Business\Model\SellableInterface $sellable
     * @param \Spryker\Zed\Availability\AvailabilityConfig $availabilityConfig
     * @param \Spryker\Zed\AvailabilityExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface[] $cartItemQuantityCounterStrategyPlugins
     */
    public function __construct(
        SellableInterface $sellable,
        AvailabilityConfig $availabilityConfig,
        array $cartItemQuantityCounterStrategyPlugins
    ) {
        $this->sellable = $sellable;
        $this->availabilityConfig = $availabilityConfig;
        $this->cartItemQuantityCounterStrategyPlugins = $cartItemQuantityCounterStrategyPlugins;
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
        /** @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer */
        $storeTransfer = $quoteTransfer->requireStore()->getStore();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $quantity = $this->getAccumulatedItemQuantityForGivenItemSku($quoteTransfer, $itemTransfer);

            $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
                ->fromArray($itemTransfer->toArray(), true);

            /** @var string $sku */
            $sku = $itemTransfer->requireSku()->getSku();

            if ($this->sellable->isProductSellableForStore($sku, $quantity, $storeTransfer, $productAvailabilityCriteriaTransfer)) {
                continue;
            }

            $this->addAvailabilityErrorToCheckoutResponse($checkoutResponse, $sku);
            $isPassed = false;
        }

        return $isPassed;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function getAccumulatedItemQuantityForGivenItemSku(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer
    ): Decimal {
        $cartItemQuantity = $this->executeCartItemQuantityCounterStrategyPlugin($quoteTransfer, $itemTransfer);

        if ($cartItemQuantity) {
            /** @var int $quantity */
            $quantity = $cartItemQuantity->getQuantity();

            return (new Decimal(0))->add($quantity);
        }

        /** @var string $sku */
        $sku = $itemTransfer->requireSku()->getSku();

        return $this->calculateCurrentCartQuantityForGivenSku($quoteTransfer, $sku);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateCurrentCartQuantityForGivenSku(QuoteTransfer $quoteTransfer, string $sku): Decimal
    {
        $quantity = new Decimal(0);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() !== $sku) {
                continue;
            }

            /** @var int $itemQuantity */
            $itemQuantity = $itemTransfer->getQuantity();

            $quantity = $quantity->add($itemQuantity);
        }

        return $quantity;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer|null
     */
    protected function executeCartItemQuantityCounterStrategyPlugin(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer
    ): ?CartItemQuantityTransfer {
        foreach ($this->cartItemQuantityCounterStrategyPlugins as $cartItemQuantityCounterStrategyPlugin) {
            if ($cartItemQuantityCounterStrategyPlugin->isApplicable($quoteTransfer->getItems(), $itemTransfer)) {
                return $cartItemQuantityCounterStrategyPlugin->countCartItemQuantity(
                    $quoteTransfer->getItems(),
                    $itemTransfer
                );
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function filterItemsWithAmount(array $itemTransfers): array
    {
        return array_filter($itemTransfers, function (ItemTransfer $itemTransfer) {
            return $itemTransfer->getAmount() === null;
        });
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
