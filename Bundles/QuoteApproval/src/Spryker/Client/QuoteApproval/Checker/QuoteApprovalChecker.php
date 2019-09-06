<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Checker;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\QuoteApproval\QuoteApprovalConfig;

class QuoteApprovalChecker implements QuoteApprovalCheckerInterface
{
    /**
     * @var \Spryker\Client\QuoteApproval\QuoteApprovalConfig
     */
    protected $quoteApprovalConfig;

    /**
     * @param \Spryker\Client\QuoteApproval\QuoteApprovalConfig $quoteApprovalConfig
     */
    public function __construct(QuoteApprovalConfig $quoteApprovalConfig)
    {
        $this->quoteApprovalConfig = $quoteApprovalConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteApplicableForApprovalProcess(QuoteTransfer $quoteTransfer): bool
    {
        $quoteData = $quoteTransfer->toArray(false, true);

        foreach ($this->quoteApprovalConfig->getRequiredQuoteFields() as $requiredQuoteField) {
            if ($quoteData[$requiredQuoteField] === null) {
                return false;
            }
        }

        return true;
    }
}
