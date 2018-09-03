<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;

class QuoteCleaner implements QuoteCleanerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function cleanUp(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $skuCounts = array_count_values($this->getItemSkus($quoteTransfer));

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($skuCounts[$itemTransfer->getSku()] === 1 && $itemTransfer->getGroupKeyPrefix()) {
                $itemTransfer->setGroupKeyPrefix(null);
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getItemSkus(QuoteTransfer $quoteTransfer): array
    {
        $skus = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $skus[] = $itemTransfer->getSku();
        }

        return $skus;
    }
}
