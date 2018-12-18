<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface;

class QuoteApprovalWriter implements QuoteApprovalWriterInterface
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
    public function approveQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $approvals = new ArrayObject();
        foreach ($quoteTransfer->getApprovals() as $quoteApprovalTransfer) {
            if ($quoteApprovalTransfer->getApprover()->getCustomer()) {
                //todo: check that current customer is approver
                //todo: check that he/she can approve

                $quoteApprovalTransfer->setStatus(QuoteApprovalConfig::STATUS_APPROVED); // todo update to config
                $quoteApprovalTransfer = $this->quoteApprovalEntityManager->updateQuoteApproval($quoteApprovalTransfer);
                $approvals->append($quoteApprovalTransfer);
            }
        }

        $quoteTransfer->setApprovals($approvals);

        return $quoteTransfer;
    }
}
