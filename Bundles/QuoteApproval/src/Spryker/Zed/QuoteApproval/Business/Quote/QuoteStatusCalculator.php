<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\Quote;

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
        $statuses = $this->flattenApprovalStatuses($quoteTransfer);

        if (in_array(QuoteApprovalConfig::STATUS_APPROVED, $statuses, true)) {
            return QuoteApprovalConfig::STATUS_APPROVED;
        }

        if (in_array(QuoteApprovalConfig::STATUS_WAITING, $statuses, true)) {
            return QuoteApprovalConfig::STATUS_WAITING;
        }

        if (in_array(QuoteApprovalConfig::STATUS_DECLINED, $statuses, true)) {
            return QuoteApprovalConfig::STATUS_DECLINED;
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteApprovalRequestWaitingOrApproval(QuoteTransfer $quoteTransfer): bool
    {
        return in_array($this->calculateQuoteStatus($quoteTransfer), [
            QuoteApprovalConfig::STATUS_WAITING,
            QuoteApprovalConfig::STATUS_APPROVED,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function flattenApprovalStatuses(QuoteTransfer $quoteTransfer): array
    {
        $statuses = [];
        foreach ($quoteTransfer->getQuoteApprovals() as $quoteApprovalTransfer) {
            $statuses[$quoteApprovalTransfer->getStatus()] = $quoteApprovalTransfer->getStatus();
        }

        return array_filter($statuses);
    }
}
