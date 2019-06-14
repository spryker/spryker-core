<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Persistence;

use Generated\Shared\Transfer\QuoteApprovalTransfer;

interface QuoteApprovalRepositoryInterface
{
    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer[]
     */
    public function getQuoteApprovalsByIdQuote(int $idQuote): array;

    /**
     * @param int $idQuoteApproval
     *
     * @return int|null
     */
    public function findIdQuoteByIdQuoteApproval(int $idQuoteApproval): ?int;

    /**
     * @param int $idQuoteApproval
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer|null
     */
    public function findQuoteApprovalById(int $idQuoteApproval): ?QuoteApprovalTransfer;
}
