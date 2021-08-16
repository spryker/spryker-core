<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationCart\Communication\Plugin\PriceCartConnector;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartItemQuantityTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceCartConnectorExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationCart\ProductConfigurationCartConfig getConfig()
 * @method \Spryker\Zed\ProductConfigurationCart\Business\ProductConfigurationCartFacadeInterface getFacade()
 */
class ProductConfigurationCartItemQuantityCounterStrategyPlugin extends AbstractPlugin implements CartItemQuantityCounterStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if item has product configuration.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isApplicable(CartChangeTransfer $cartChangeTransfer, ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getProductConfigurationInstance() !== null;
    }

    /**
     * {@inheritDoc}
     * - Finds given item in the cart change.
     * - Counts item quantity by item SKU and product configuration instance in add and subtract directions.
     * - Returns quantity for the item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer
     */
    public function countCartItemQuantity(CartChangeTransfer $cartChangeTransfer, ItemTransfer $itemTransfer): CartItemQuantityTransfer
    {
        return $this->getFacade()->countItemQuantity($cartChangeTransfer, $itemTransfer);
    }
}
