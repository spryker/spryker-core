<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\QuoteApproval;

use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;

class QuoteApprovalReader implements QuoteApprovalReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer|null
     */
    public function findWaitingQuoteApprovalByIdCompanyUser(QuoteTransfer $quoteTransfer, int $idCompanyUser): ?QuoteApprovalTransfer
    {
        foreach ($quoteTransfer->getQuoteApprovals() as $quoteApprovalTransfer) {
            if ($quoteApprovalTransfer->getApprover()->getIdCompanyUser() !== $idCompanyUser) {
                continue;
            }

            if ($quoteApprovalTransfer->getStatus() !== QuoteApprovalConfig::STATUS_WAITING) {
                continue;
            }

            return $quoteApprovalTransfer;
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idCompanyUser
     *
     * @return bool
     */
    public function isCompanyUserInQuoteApproverList(QuoteTransfer $quoteTransfer, int $idCompanyUser): bool
    {
        foreach ($quoteTransfer->getQuoteApprovals() as $quoteApprovalTransfer) {
            if ($quoteApprovalTransfer->getApprover()->getIdCompanyUser() === $idCompanyUser) {
                return true;
            }
        }

        return false;
    }
}
