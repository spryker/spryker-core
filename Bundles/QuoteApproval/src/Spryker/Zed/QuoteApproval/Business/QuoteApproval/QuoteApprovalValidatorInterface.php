<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;

interface QuoteApprovalValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     *
     * @return bool
     */
    public function canUpdateQuoteApprovalRequest(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer, QuoteApprovalTransfer $quoteApprovalTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     *
     * @return bool
     */
    public function canDeleteQuoteApprovalRequest(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer, QuoteApprovalTransfer $quoteApprovalTransfer): bool;
}
