<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodesRestApi\Business;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CartCodesRestApiFacadeInterface
{
    /**
     * Specification:
     * - Extends QuoteTransfer with $code and its relevant data when the $code is applicable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, string $voucherCode): CartCodeOperationResultTransfer;

    /**
     * Specification:
     * - Removes code from QuoteTransfer.
     * - Calls CartCodeFacade.
     * - Finds QuoteTransfer by UUID.
     * - Return CartCodeOperationResultTransfer with message and with no QuoteTransfer if Quote is not found.
     * - Finds Discount by ID.
     * - Return CartCodeOperationResultTransfer with message and with no QuoteTransfer if discount was not deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function removeCode(QuoteTransfer $quoteTransfer, int $idDiscount): CartCodeOperationResultTransfer;
}
