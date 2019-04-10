<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\Sanitizer;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface;

class QuoteApprovalSanitizer implements QuoteApprovalSanitizerInterface
{
    /**
     * @var \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface
     */
    protected $quoteApprovalEntityManager;

    /**
     * @var \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface
     */
    protected $quoteApprovalRepository;

    /**
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface $quoteApprovalRepository
     */
    public function __construct(
        QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager,
        QuoteApprovalRepositoryInterface $quoteApprovalRepository
    ) {
        $this->quoteApprovalEntityManager = $quoteApprovalEntityManager;
        $this->quoteApprovalRepository = $quoteApprovalRepository;
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
            $this->declineQuoteApprovals($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function declineQuoteApprovals(QuoteTransfer $quoteTransfer): void
    {
        $quoteTransfer->requireIdQuote();

        $quoteApprovalTransfers = $this->quoteApprovalRepository
            ->getQuoteApprovalsByIdQuote($quoteTransfer->getIdQuote());

        foreach ($quoteApprovalTransfers as $quoteApprovalTransfer) {
            $this->quoteApprovalEntityManager->updateQuoteApprovalWithStatus(
                $quoteApprovalTransfer->getIdQuoteApproval(),
                QuoteApprovalConfig::STATUS_DECLINED
            );
        }
    }
}
