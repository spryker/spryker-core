<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig as SharedApprovalConfig;
use Spryker\Zed\QuoteApproval\QuoteApprovalConfig;

class QuoteFieldsProvider implements QuoteFieldsProviderInterface
{
    /**
     * @var \Spryker\Zed\QuoteApproval\Business\Quote\QuoteStatusCalculatorInterface
     */
    protected $quoteStatusCalculator;

    /**
     * @var \Spryker\Zed\QuoteApproval\QuoteApprovalConfig
     */
    protected $quoteApprovalConfig;

    /**
     * @param \Spryker\Zed\QuoteApproval\Business\Quote\QuoteStatusCalculatorInterface $quoteStatusCalculator
     * @param \Spryker\Zed\QuoteApproval\QuoteApprovalConfig $quoteApprovalConfig
     */
    public function __construct(
        QuoteStatusCalculatorInterface $quoteStatusCalculator,
        QuoteApprovalConfig $quoteApprovalConfig
    ) {
        $this->quoteStatusCalculator = $quoteStatusCalculator;
        $this->quoteApprovalConfig = $quoteApprovalConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    public function getQuoteFieldsAllowedForSaving(QuoteTransfer $quoteTransfer): array
    {
        if ($this->isQuoteApprovalRequestWaitingOrApproved($quoteTransfer)) {
            return $this->quoteApprovalConfig->getRequiredQuoteFieldsForApprovalProcess();
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteApprovalRequestWaitingOrApproved(QuoteTransfer $quoteTransfer): bool
    {
        return in_array($this->quoteStatusCalculator->calculateQuoteStatus($quoteTransfer), [
            SharedApprovalConfig::STATUS_WAITING,
            SharedApprovalConfig::STATUS_APPROVED,
        ]);
    }
}
