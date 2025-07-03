<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Business\Extractor;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteOriginalSalesOrderItemExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return list<string>
     */
    public function extractOriginalSalesOrderItemSkus(QuoteTransfer $quoteTransfer): array;
}
