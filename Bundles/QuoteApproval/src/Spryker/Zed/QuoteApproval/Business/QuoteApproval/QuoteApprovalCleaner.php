<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteApprovalCleaner implements QuoteApprovalCleanerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function clearQuoteApprovalFromQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer->setQuoteApprovals(new ArrayObject());

        return $quoteTransfer;
    }
}
