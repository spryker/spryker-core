<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodesRestApi\Business;

use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;

interface CartCodesRestApiFacadeInterface
{
    /**
     * Specification:
     * - Extends QuoteTransfer with $voucherCode and its relevant data when the $code is applicable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function addCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer;

    /**
     * Specification:
     * - Removes discount code from QuoteTransfer.
     * - Calls CartCodeFacade.
     * - Finds QuoteTransfer by UUID.
     * - Return CartCodeResponseTransfer with message and with no QuoteTransfer if Quote is not found.
     * - Finds Discount by ID.
     * - Return CartCodeResponseTransfer with message and with no QuoteTransfer if discount was not deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function removeCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer;

    /**
     * Specification:
     * - Removes cart code from QuoteTransfer.
     * - Calls CartCodeFacade.
     * - Finds QuoteTransfer by UUID.
     * - Return CartCodeResponseTransfer with message and with no QuoteTransfer if Quote is not found.
     * - Return CartCodeResponseTransfer with message and with no QuoteTransfer if cart code can not be removed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function removeCartCodeFromQuote(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer;
}
