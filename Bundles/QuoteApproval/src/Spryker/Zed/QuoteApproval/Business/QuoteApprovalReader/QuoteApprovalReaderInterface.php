<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApprovalReader;

interface QuoteApprovalReaderInterface
{
    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer[]
     */
    public function getQuoteApprovalsByIdQuote(int $idQuote): array;
}
