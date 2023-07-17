<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;

interface ProductImageCartConnectorFacadeInterface
{
    /**
     * Specification:
     * - Reads a persisted concrete product from database.
     * - Expands the items of the CartChangeTransfer with the concrete product's data.
     * - Returns the expanded CartChangeTransfer.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductImageCartConnector\Business\ProductImageCartConnectorFacadeInterface::expandCartChangeItems()} instead.
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer);

    /**
     * Specification:
     * - Requires `ItemTransfer.id` and `ItemTransfer.productAbstractId` to be set for each `CartChangeTransfer.items`.
     * - Gets product image sets by concrete product IDs.
     * - If product image sets less than cart items - gets more product image sets by abstract product IDs.
     * - Expands `CartChangeTransfer.items` with product image sets.
     * - Returns the expanded `CartChangeTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;
}
