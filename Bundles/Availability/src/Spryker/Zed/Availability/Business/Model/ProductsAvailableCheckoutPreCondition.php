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
use Generated\Shared\Transfer\SellableItemRequestTransfer;
use Generated\Shared\Transfer\SellableItemsRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Availability\AvailabilityConfig;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;

class ProductsAvailableCheckoutPreCondition implements ProductsAvailableCheckoutPreConditionInterface
{
    /**
     * @var string
     */
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
     * @var array<\Spryker\Zed\AvailabilityExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface>
     */
    protected $cartItemQuantityCounterStrategyPlugins;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Availability\Business\Model\SellableInterface $sellable
     * @param \Spryker\Zed\Availability\AvailabilityConfig $availabilityConfig
     * @param array<\Spryker\Zed\AvailabilityExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface> $cartItemQuantityCounterStrategyPlugins
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        SellableInterface $sellable,
        AvailabilityConfig $availabilityConfig,
        array $cartItemQuantityCounterStrategyPlugins,
        AvailabilityToStoreFacadeInterface $storeFacade
    ) {
        $this->sellable = $sellable;
        $this->availabilityConfig = $availabilityConfig;
        $this->cartItemQuantityCounterStrategyPlugins = $cartItemQuantityCounterStrategyPlugins;
        $this->storeFacade = $storeFacade;
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

        $storeTransfer = $quoteTransfer->getStoreOrFail();

        if (!$storeTransfer->getIdStore()) {
            $storeTransfer = $this->storeFacade->getStoreByName($storeTransfer->getNameOrFail());
        }

        $sellableItemsResponseTransfer = $this->sellable->areProductsSellableForStoreWithDefaultBatchStrategy(
            $this->createSellableItemsRequestTransfer($quoteTransfer, $storeTransfer),
        );

        foreach ($sellableItemsResponseTransfer->getSellableItemResponses() as $sellableItemResponseTransfer) {
            if ($sellableItemResponseTransfer->getIsSellable()) {
                continue;
            }

            $this->addAvailabilityErrorToCheckoutResponse($checkoutResponse, $sellableItemResponseTransfer->getSkuOrFail());
            $isPassed = false;
        }

        return $isPassed;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemsRequestTransfer
     */
    protected function createSellableItemsRequestTransfer(QuoteTransfer $quoteTransfer, StoreTransfer $storeTransfer): SellableItemsRequestTransfer
    {
        $sellableItemsRequestTransfer = new SellableItemsRequestTransfer();
        $sellableItemsRequestTransfer->setStore($storeTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getAmount() !== null) {
                continue;
            }

            $sellableItemRequestTransfer = new SellableItemRequestTransfer();
            $quantity = $this->getAccumulatedItemQuantityForGivenItemSku($quoteTransfer, $itemTransfer);
            $sellableItemRequestTransfer->setQuantity($quantity);
            $sellableItemRequestTransfer->setProductAvailabilityCriteria(
                (new ProductAvailabilityCriteriaTransfer())
                    ->fromArray($itemTransfer->toArray(), true),
            );
            $sellableItemRequestTransfer->setSku($itemTransfer->getSku());
            $sellableItemsRequestTransfer->addSellableItemRequest($sellableItemRequestTransfer);
        }

        return $sellableItemsRequestTransfer;
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
                    $itemTransfer,
                );
            }
        }

        return null;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
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
                $this->availabilityConfig->getAvailabilityErrorType(),
            )
            ->setParameters([
                $this->availabilityConfig->getAvailabilityProductSkuParameter() => $sku,
            ]);

        $checkoutResponse
            ->addError($checkoutErrorTransfer)
            ->setIsSuccess(false);
    }
}
