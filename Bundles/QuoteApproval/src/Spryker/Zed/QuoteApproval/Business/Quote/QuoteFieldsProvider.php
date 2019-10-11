<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\QuoteApproval\QuoteApprovalConfig;

class QuoteFieldsProvider implements QuoteFieldsProviderInterface
{
    /**
     * @var \Spryker\Zed\QuoteApproval\Business\Quote\QuoteStatusCheckerInterface
     */
    protected $quoteStatusChecker;

    /**
     * @var \Spryker\Zed\QuoteApproval\QuoteApprovalConfig
     */
    protected $quoteApprovalConfig;

    /**
     * @param \Spryker\Zed\QuoteApproval\Business\Quote\QuoteStatusCheckerInterface $quoteStatusChecker
     * @param \Spryker\Zed\QuoteApproval\QuoteApprovalConfig $quoteApprovalConfig
     */
    public function __construct(
        QuoteStatusCheckerInterface $quoteStatusChecker,
        QuoteApprovalConfig $quoteApprovalConfig
    ) {
        $this->quoteStatusChecker = $quoteStatusChecker;
        $this->quoteApprovalConfig = $quoteApprovalConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    public function getQuoteFieldsAllowedForSaving(QuoteTransfer $quoteTransfer): array
    {
        if ($this->quoteStatusChecker->isQuoteInApprovalProcess($quoteTransfer)) {
            return $this->quoteApprovalConfig->getRequiredQuoteFieldsForApprovalProcess();
        }

        return [];
    }
}
