<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ProductOptionCartConnectorFacadeInterface
{
    /**
     * Specification:
     *  - Expand product option transfer object with additional data from persistence
     *  - Returns CartChangeTransfer transfer with option data included
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandProductOptions(CartChangeTransfer $changeTransfer);

    /**
     * Specification:
     *  - Set group key to itemTransfer to contain product option identifiers.
     *  - Returns CartChangeTransfer with modified group key for each item, which includes options
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandGroupKey(CartChangeTransfer $changeTransfer);

    /**
     * Specification:
     *  - Sets each product quantity to item quantity
     *  - Returns CartChangeTransfer with modified item quantity
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function changeProductOptionInCartQuantity(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Checks changed cart product's product option prices if they exist.
     * - Price has to be present for the current store, current currency, and current price mode.
     * - Product option value price might be defined using the "default store" (fkStore=NULL).
     * - Returns pre-check transfer with error messages in case of error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateProductOptionValuePrices(CartChangeTransfer $cartChangeTransfer);

    /**
     * Specification:
     *  - Checks if product options of items in CartChangeTransfer exists.
     *  - If not terminates cart operation and write error messages.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkProductOptionExistence(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;
}
