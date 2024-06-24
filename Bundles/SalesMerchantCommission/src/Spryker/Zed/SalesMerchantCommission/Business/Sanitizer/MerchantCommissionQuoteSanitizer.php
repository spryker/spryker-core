<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Business\Sanitizer;

use Generated\Shared\Transfer\QuoteTransfer;

class MerchantCommissionQuoteSanitizer implements MerchantCommissionQuoteSanitizerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function sanitizeMerchantCommissionFromQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer
                ->setMerchantCommissionAmountAggregation(null)
                ->setMerchantCommissionAmountFullAggregation(null)
                ->setMerchantCommissionRefundedAmount(null);
        }

        if ($quoteTransfer->getTotals()) {
            $quoteTransfer->getTotalsOrFail()
                ->setMerchantCommissionTotal(null)
                ->setMerchantCommissionRefundedTotal(null);
        }

        return $quoteTransfer;
    }
}
