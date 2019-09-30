<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\StepAccessChecker;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToQuoteClientInterface;
use Spryker\Client\QuoteApproval\Quote\QuoteStatusCheckerInterface;

class PaymentStepAccessChecker implements PaymentStepAccessCheckerInterface
{
    /**
     * @var \Spryker\Client\QuoteApproval\Quote\QuoteStatusCheckerInterface
     */
    protected $quoteStatusChecker;

    /**
     * @var \Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\QuoteApproval\Quote\QuoteStatusCheckerInterface $quoteStatusChecker
     * @param \Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToQuoteClientInterface $quoteClient
     */
    public function __construct(QuoteStatusCheckerInterface $quoteStatusChecker, QuoteApprovalToQuoteClientInterface $quoteClient)
    {
        $this->quoteStatusChecker = $quoteStatusChecker;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkPaymentStepAccessibility(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getQuoteApprovals()->count()
            && $this->quoteClient->isQuoteLocked($quoteTransfer)
            && !$this->quoteStatusChecker->isQuoteDeclined($quoteTransfer);
    }
}
