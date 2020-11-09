<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnectorExtension\Dependency\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartItemQuantityTransfer;
use Generated\Shared\Transfer\ItemTransfer;

/**
 * Allows changing the cart item quantity counter strategy during cart price resolving.
 */
interface CartItemQuantityCounterStrategyPluginInterface
{
    /**
     * Specification:
     * - Returns true if strategy needs to be applied.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isApplicable(CartChangeTransfer $cartChangeTransfer, ItemTransfer $itemTransfer): bool;

    /**
     * Specification:
     * - Finds given item in the cart change.
     * - Counts quantity for the given item.
     * - Returns counted quantity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer
     */
    public function countCartItemQuantity(CartChangeTransfer $cartChangeTransfer, ItemTransfer $itemTransfer): CartItemQuantityTransfer;
}
