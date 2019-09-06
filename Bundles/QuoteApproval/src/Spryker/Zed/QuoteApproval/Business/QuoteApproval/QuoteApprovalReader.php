<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\QuoteApproval\Business\Quote\QuoteStatusCalculatorInterface;
use Spryker\Zed\QuoteApproval\QuoteApprovalConfig;

class QuoteApprovalReader implements QuoteApprovalReaderInterface
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
        if (!$this->quoteStatusCalculator->isQuoteApprovalRequestWaitingOrApproval($quoteTransfer)) {
            return $this->quoteApprovalConfig->getRequiredQuoteFields();
        }

        return [];
    }
}
