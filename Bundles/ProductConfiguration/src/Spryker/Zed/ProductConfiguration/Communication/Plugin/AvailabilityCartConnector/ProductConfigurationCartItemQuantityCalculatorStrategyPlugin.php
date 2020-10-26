<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Communication\Plugin\AvailabilityCartConnector;

use Generated\Shared\Transfer\CartItemQuantityTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\AvailabilityCartConnectorExtension\Dependency\Plugin\CartItemQuantityCalculatorStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductConfiguration\ProductConfigurationConfig getConfig()
 * @method \Spryker\Zed\ProductConfiguration\Business\ProductConfigurationFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfiguration\Communication\ProductConfigurationCommunicationFactory getFactory()
 */
class ProductConfigurationCartItemQuantityCalculatorStrategyPlugin extends AbstractPlugin implements CartItemQuantityCalculatorStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if item has product configuration.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isApplicable(array $itemsInCart, ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getProductConfigurationInstance() !== null;
    }

    /**
     * {@inheritDoc}
     * Specification:
     * - Calculates item quantity by item group key.
     * - Returns quantity for the item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer
     */
    public function calculateCartItemQuantity(array $itemsInCart, ItemTransfer $itemTransfer): CartItemQuantityTransfer
    {
        return $this->getFacade()->calculateCartItemQuantity($itemsInCart, $itemTransfer);
    }
}
