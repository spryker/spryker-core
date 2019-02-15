<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestValidationResponseTransfer;

interface QuoteApprovalRequestValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalRequestValidationResponseTransfer
     */
    public function validateQuoteApprovalCreateRequest(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalRequestValidationResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalRequestValidationResponseTransfer
     */
    public function validateQuoteApprovalRemoveRequest(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalRequestValidationResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalRequestValidationResponseTransfer
     */
    public function validateQuoteApprovalRequest(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalRequestValidationResponseTransfer;
}
