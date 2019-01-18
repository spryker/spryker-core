<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Persistence;

interface QuoteApprovalRepositoryInterface
{
    /**
     * @param int $idQuote
     *
     * @return array
     */
    public function findQuoteApprovalCollectionByIdQuote(int $idQuote): array;

    /**
     * @param int $idQuoteApproval
     *
     * @return int|null
     */
    public function findIdQuoteByIdQuoteApproval(int $idQuoteApproval): ?int;
}
