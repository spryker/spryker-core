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
     * @return int[]
     */
    public function findQuoteQuoteApprovalIdCollection(int $idQuote): array;
}
