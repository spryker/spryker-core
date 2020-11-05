<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Communication\Plugin\Availability;

use ArrayObject;
use Generated\Shared\Transfer\CartItemQuantityTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\AvailabilityExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductConfiguration\ProductConfigurationConfig getConfig()
 * @method \Spryker\Zed\ProductConfiguration\Business\ProductConfigurationFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfiguration\Communication\ProductConfigurationCommunicationFactory getFactory()
 */
class ProductConfigurationCartItemQuantityCounterStrategyPlugin extends AbstractPlugin implements CartItemQuantityCounterStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if item has product configuration.
     *
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isApplicable(ArrayObject $itemsInCart, ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getProductConfigurationInstance() !== null;
    }

    /**
     * {@inheritDoc}
     * - Finds given item in the cart.
     * - Counts item quantity by item group key.
     * - Returns quantity for the item.
     *
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer
     */
    public function countCartItemQuantity(ArrayObject $itemsInCart, ItemTransfer $itemTransfer): CartItemQuantityTransfer
    {
        return $this->getFacade()->countCartItemQuantity($itemsInCart, $itemTransfer);
    }
}
