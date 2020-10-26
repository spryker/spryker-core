<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnectorExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartItemQuantityTransfer;
use Generated\Shared\Transfer\ItemTransfer;

/**
 * Allows to change default strategy running in AvailabilityCartConnector.
 */
interface CartItemQuantityCalculatorStrategyPluginInterface
{
    /**
     * Specification:
     * - Returns true if strategy needs to be applied.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isApplicable(array $itemsInCart, ItemTransfer $itemTransfer): bool;

    /**
     * Specification:
     * - Calculates quantity for given item.
     * - Returns quantity for given item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer
     */
    public function calculateCartItemQuantity(array $itemsInCart, ItemTransfer $itemTransfer): CartItemQuantityTransfer;
}
