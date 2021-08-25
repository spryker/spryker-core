<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Communication\Plugin\Cart;

use ArrayObject;
use Generated\Shared\Transfer\CartItemQuantityTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\AvailabilityCartConnectorExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface as AvailabilityCartConnectorCartItemQuantityCounterStrategyPluginInterface;
use Spryker\Zed\AvailabilityExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOffer\ProductOfferConfig getConfig()
 * @method \Spryker\Zed\ProductOffer\Communication\ProductOfferCommunicationFactory getFactory()
 */
class ProductOfferCartItemQuantityCounterStrategyPlugin extends AbstractPlugin implements CartItemQuantityCounterStrategyPluginInterface, AvailabilityCartConnectorCartItemQuantityCounterStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if item has a merchant reference.
     *
     * @api
     *
     * @phpstan-param \ArrayObject<string, \Generated\Shared\Transfer\ItemTransfer> $itemsInCart
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isApplicable(ArrayObject $itemsInCart, ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getMerchantReference() !== null;
    }

    /**
     * {@inheritDoc}
     * - Finds given item in the cart.
     * - Counts item quantity by item product offer reference.
     * - Returns quantity for the item.
     *
     * @api
     *
     * @phpstan-param \ArrayObject<string, \Generated\Shared\Transfer\ItemTransfer> $itemsInCart
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer
     */
    public function countCartItemQuantity(
        ArrayObject $itemsInCart,
        ItemTransfer $itemTransfer
    ): CartItemQuantityTransfer {
        return $this->getFacade()->countCartItemQuantity($itemsInCart, $itemTransfer);
    }
}
