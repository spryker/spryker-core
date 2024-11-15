<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\DecimalObject\Decimal;

class ItemQuantityCalculator implements ItemQuantityCalculatorInterface
{
    /**
     * @var list<\Spryker\Zed\AvailabilityCartConnectorExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface>
     */
    protected array $cartItemQuantityCounterStrategyPlugins;

    /**
     * @param list<\Spryker\Zed\AvailabilityCartConnectorExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface> $cartItemQuantityCounterStrategyPlugins
     */
    public function __construct(array $cartItemQuantityCounterStrategyPlugins)
    {
        $this->cartItemQuantityCounterStrategyPlugins = $cartItemQuantityCounterStrategyPlugins;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateTotalItemQuantity(ArrayObject $itemsInCart, ItemTransfer $itemTransfer): Decimal
    {
        $currentItemQuantity = $this->calculateCartItemQuantity($itemsInCart, $itemTransfer);
        $currentItemQuantity += $itemTransfer->getQuantity();

        return new Decimal($currentItemQuantity);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    public function calculateCartItemQuantity(ArrayObject $itemsInCart, ItemTransfer $itemTransfer): int
    {
        foreach ($this->cartItemQuantityCounterStrategyPlugins as $cartItemQuantityCounterStrategyPlugin) {
            if ($cartItemQuantityCounterStrategyPlugin->isApplicable($itemsInCart, $itemTransfer)) {
                $cartItemQuantityTransfer = $cartItemQuantityCounterStrategyPlugin->countCartItemQuantity(
                    $itemsInCart,
                    $itemTransfer,
                );

                return $cartItemQuantityTransfer->getQuantity();
            }
        }

        return $this->calculateCartItemQuantityBySku(
            $itemsInCart,
            $itemTransfer->getSku(),
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $items
     * @param string $sku
     *
     * @return int
     */
    protected function calculateCartItemQuantityBySku(ArrayObject $items, string $sku): int
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
}
