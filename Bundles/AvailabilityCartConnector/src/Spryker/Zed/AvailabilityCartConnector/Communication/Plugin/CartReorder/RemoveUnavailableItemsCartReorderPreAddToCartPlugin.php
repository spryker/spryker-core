<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderPreAddToCartPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AvailabilityCartConnector\Business\AvailabilityCartConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\AvailabilityCartConnector\Communication\AvailabilityCartConnectorCommunicationFactory getFactory()
 */
class RemoveUnavailableItemsCartReorderPreAddToCartPlugin extends AbstractPlugin implements CartReorderPreAddToCartPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CartChangeTransfer.quote.store` to be set.
     * - Calculates items quantity for each item in the cart.
     * - Executes a stack of {@link \Spryker\Zed\AvailabilityCartConnectorExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface} plugins.
     * - Ignores items with `ItemTransfer.amount` defined.
     * - Filters out items from `CartChangeTransfer` that are not sellable.
     * - Adds a message for each unique item that is not sellable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function preAddToCart(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFacade()->filterOutUnavailableCartChangeItems($cartChangeTransfer);
    }
}
