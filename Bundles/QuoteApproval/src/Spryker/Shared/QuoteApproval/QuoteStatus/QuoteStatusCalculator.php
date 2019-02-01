<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\QuoteApproval\QuoteStatus;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;

class QuoteStatusCalculator implements QuoteStatusCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    public function calculateQuoteStatus(QuoteTransfer $quoteTransfer): ?string
    {
        $status = null;

        foreach ($quoteTransfer->getQuoteApprovals() as $quoteApprovalTransfer) {
            if ($quoteApprovalTransfer->getStatus() === QuoteApprovalConfig::STATUS_APPROVED) {
                return QuoteApprovalConfig::STATUS_APPROVED;
            }

            if ($quoteApprovalTransfer->getStatus() === QuoteApprovalConfig::STATUS_WAITING) {
                $status = QuoteApprovalConfig::STATUS_WAITING;
            }

            if ($status === null && $quoteApprovalTransfer->getStatus() === QuoteApprovalConfig::STATUS_DECLINED) {
                $status = QuoteApprovalConfig::STATUS_DECLINED;
            }
        }

        return $status;
    }
}
