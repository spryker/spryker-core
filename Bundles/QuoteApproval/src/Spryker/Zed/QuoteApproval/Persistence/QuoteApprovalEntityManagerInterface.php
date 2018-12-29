<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Persistence;

use Generated\Shared\Transfer\QuoteApprovalTransfer;

interface QuoteApprovalEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer
     */
    public function saveQuoteApproval(
        QuoteApprovalTransfer $quoteApprovalTransfer,
        int $idQuote
    ): QuoteApprovalTransfer;

    /**
     * @param int $idQuoteApproval
     *
     * @return void
     */
    public function deleteQuoteApprovalById(int $idQuoteApproval): void;
}
