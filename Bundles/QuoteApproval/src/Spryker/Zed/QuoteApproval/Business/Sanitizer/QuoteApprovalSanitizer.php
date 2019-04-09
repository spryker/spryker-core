<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\Sanitizer;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface;

class QuoteApprovalSanitizer implements QuoteApprovalSanitizerInterface
{
    /**
     * @var \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface
     */
    protected $quoteApprovalEntityManager;

    /**
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager
     */
    public function __construct(QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager)
    {
        $this->quoteApprovalEntityManager = $quoteApprovalEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function sanitizeQuoteApproval(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer->setQuoteApprovals(new ArrayObject());

        if ($quoteTransfer->getIdQuote()) {
            $this->quoteApprovalEntityManager->removeApprovalsByIdQuote($quoteTransfer->getIdQuote());
        }

        return $quoteTransfer;
    }
}
