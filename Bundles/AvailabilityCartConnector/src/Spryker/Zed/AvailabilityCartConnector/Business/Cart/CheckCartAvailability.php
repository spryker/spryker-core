<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Business\Cart;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface;

class CheckCartAvailability implements CheckCartAvailabilityInterface
{
    public const CART_PRE_CHECK_AVAILABILITY_FAILED = 'cart.pre.check.availability.failed';
    public const CART_PRE_CHECK_AVAILABILITY_EMPTY = 'cart.pre.check.availability.failed.empty';
    public const STOCK_TRANSLATION_PARAMETER = '%stock%';
    public const SKU_TRANSLATION_PARAMETER = '%sku%';

    /**
     * @var \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\AvailabilityCartConnectorExtension\Dependency\Plugin\CartItemQuantityCalculatorStrategyPluginInterface[]
     */
    protected $cartItemQuantityCalculatorStrategyPlugins;

    /**
     * @param \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface $availabilityFacade
     * @param \Spryker\Zed\AvailabilityCartConnectorExtension\Dependency\Plugin\CartItemQuantityCalculatorStrategyPluginInterface[] $cartItemQuantityCalculatorStrategyPlugins
     */
    public function __construct(
        AvailabilityCartConnectorToAvailabilityInterface $availabilityFacade,
        array $cartItemQuantityCalculatorStrategyPlugins
    ) {
        $this->availabilityFacade = $availabilityFacade;
        $this->cartItemQuantityCalculatorStrategyPlugins = $cartItemQuantityCalculatorStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartAvailability(CartChangeTransfer $cartChangeTransfer)
    {
        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();
        $cartPreCheckResponseTransfer->setIsSuccess(true);

        $storeTransfer = $this->getStoreTransfer($cartChangeTransfer);
        $itemsInCart = clone $cartChangeTransfer->getQuote()->getItems();

        $messages = new ArrayObject();
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getAmount() !== null) {
                continue;
            }

            $currentItemQuantity = $this->calculateCurrentCartItemQuantity($itemsInCart, $itemTransfer);

            $currentItemQuantity += $itemTransfer->getQuantity();

            $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
                ->fromArray($itemTransfer->toArray(), true);

            $isSellable = $this->availabilityFacade->isProductSellableForStore(
                $itemTransfer->getSku(),
                new Decimal($currentItemQuantity),
                $storeTransfer,
                $productAvailabilityCriteriaTransfer
            );

            if (!$isSellable) {
                $availability = $this->findProductConcreteAvailability($itemTransfer, $storeTransfer, $productAvailabilityCriteriaTransfer);
                $cartPreCheckResponseTransfer->setIsSuccess(false);
                $messages[] = $this->createItemIsNotAvailableMessageTransfer($availability, $itemTransfer->getSku());
            }

            $itemsInCart->append($itemTransfer);
        }

        $cartPreCheckResponseTransfer->setMessages($messages);

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer|int
     */
    protected function calculateCurrentCartItemQuantity(ArrayObject $itemsInCart, ItemTransfer $itemTransfer)
    {
        foreach ($this->cartItemQuantityCalculatorStrategyPlugins as $cartItemQuantityCalculatorStrategyPlugin) {
            if ($cartItemQuantityCalculatorStrategyPlugin->isApplicable($itemsInCart->getArrayCopy(), $itemTransfer)) {
                return $cartItemQuantityCalculatorStrategyPlugin->calculateCartItemQuantity(
                    $itemsInCart->getArrayCopy(),
                    $itemTransfer
                )->getQuantity();
            }
        }

        return $this->calculateCurrentCartQuantityForGivenSku(
            $itemsInCart,
            $itemTransfer->getSku()
        );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $sku
     *
     * @return int
     */
    protected function calculateCurrentCartQuantityForGivenSku(ArrayObject $items, $sku)
    {
        $quantity = 0;
        foreach ($items as $itemTransfer) {
            if ($itemTransfer->getSku() !== $sku) {
                continue;
            }
            $quantity += $itemTransfer->getQuantity();
        }

        return $quantity;
    }

    /**
     * @param \Spryker\DecimalObject\Decimal $availability
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemIsNotAvailableMessageTransfer(Decimal $availability, string $sku): MessageTransfer
    {
        $translationKey = $this->getTranslationKey($availability);

        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($translationKey);
        $messageTransfer->setParameters([
            static::STOCK_TRANSLATION_PARAMETER => $availability->trim()->toString(),
            static::SKU_TRANSLATION_PARAMETER => $sku,
        ]);

        return $messageTransfer;
    }

    /**
     * @param \Spryker\DecimalObject\Decimal $availability
     *
     * @return string
     */
    protected function getTranslationKey(Decimal $availability): string
    {
        $translationKey = static::CART_PRE_CHECK_AVAILABILITY_FAILED;
        if ($availability->lessThanOrEquals(0)) {
            $translationKey = static::CART_PRE_CHECK_AVAILABILITY_EMPTY;
        }

        return $translationKey;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStoreTransfer(CartChangeTransfer $cartChangeTransfer): StoreTransfer
    {
        $cartChangeTransfer
            ->getQuote()
                ->requireStore();

        return $cartChangeTransfer->getQuote()->getStore();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function findProductConcreteAvailability(
        ItemTransfer $itemTransfer,
        StoreTransfer $storeTransfer,
        ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer
    ): Decimal {
        $productConcreteAvailabilityTransfer = $this->availabilityFacade
            ->findOrCreateProductConcreteAvailabilityBySkuForStore($itemTransfer->getSku(), $storeTransfer, $productAvailabilityCriteriaTransfer);

        if ($productConcreteAvailabilityTransfer !== null) {
            return $productConcreteAvailabilityTransfer->getAvailability();
        }

        return new Decimal(0);
    }
}
