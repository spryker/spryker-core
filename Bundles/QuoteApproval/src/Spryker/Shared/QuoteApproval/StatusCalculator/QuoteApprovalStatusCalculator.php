<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\QuoteApproval\StatusCalculator;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;

class QuoteApprovalStatusCalculator implements QuoteApprovalStatusCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    public function calculateQuoteStatus(QuoteTransfer $quoteTransfer): ?string
    {
        $status = null;

        foreach ($quoteTransfer->getApprovals() as $approval) {
            if ($approval->getStatus() === QuoteApprovalConfig::STATUS_APPROVED) {
                return QuoteApprovalConfig::STATUS_APPROVED;
            }

            if ($approval->getStatus() === QuoteApprovalConfig::STATUS_WAITING) {
                $status = QuoteApprovalConfig::STATUS_WAITING;
            }

            if ($status === null && $approval->getStatus() === QuoteApprovalConfig::STATUS_DECLINED) {
                $status = QuoteApprovalConfig::STATUS_DECLINED;
            }
        }

        return $status;
    }
}
