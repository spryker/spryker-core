<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\Extractor;

use Generated\Shared\Transfer\QuoteTransfer;

class QuoteOriginalSalesOrderItemExtractor implements QuoteOriginalSalesOrderItemExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return list<string>
     */
    public function extractOriginalSalesOrderItemProductOfferReferences(QuoteTransfer $quoteTransfer): array
    {
        $originalSalesOrderItemProductOfferReferences = [];
        $originalSalesOrderItemTransfers = $quoteTransfer->getOriginalSalesOrderItems();
        foreach ($originalSalesOrderItemTransfers as $originalSalesOrderItemTransfer) {
            if ($originalSalesOrderItemTransfer->getProductOfferReference()) {
                $originalSalesOrderItemProductOfferReferences[] = $originalSalesOrderItemTransfer->getProductOfferReferenceOrFail();
            }
        }

        return $originalSalesOrderItemProductOfferReferences;
    }
}
