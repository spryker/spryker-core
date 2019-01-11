<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Persistence\Mapper;

use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Orm\Zed\QuoteApproval\Persistence\SpyQuoteApproval;

class QuoteApprovalMapper
{
    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     * @param \Orm\Zed\QuoteApproval\Persistence\SpyQuoteApproval $quoteApprovalEnituty
     *
     * @return \Orm\Zed\QuoteApproval\Persistence\SpyQuoteApproval
     */
    public function mapQuoteApprovalTransferToEntity(
        QuoteApprovalTransfer $quoteApprovalTransfer,
        SpyQuoteApproval $quoteApprovalEnituty
    ): SpyQuoteApproval {
        $quoteApprovalEnituty->fromArray(
            $quoteApprovalTransfer->modifiedToArray(false)
        );

        return $quoteApprovalEnituty;
    }

    /**
     * @param \Orm\Zed\QuoteApproval\Persistence\SpyQuoteApproval $quoteApprovalEnituty
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer
     */
    public function mapEntityToQuoteApprovalTransfer(
        SpyQuoteApproval $quoteApprovalEnituty,
        QuoteApprovalTransfer $quoteApprovalTransfer
    ): QuoteApprovalTransfer {
        return $quoteApprovalTransfer->fromArray(
            $quoteApprovalEnituty->toArray(),
            true
        );
    }
}
