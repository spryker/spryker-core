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
    public function cleanUpItemGroupKeyPrefix(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $skuCounts = $this->getItemSkuCounts($quoteTransfer);

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
    protected function getItemSkuCounts(QuoteTransfer $quoteTransfer): array
    {
        $skuCounts = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!isset($skuCounts[$itemTransfer->getSku()])) {
                $skuCounts[$itemTransfer->getSku()] = 0;
            }

            $skuCounts[$itemTransfer->getSku()]++;
        }

        return $skuCounts;
    }
}
