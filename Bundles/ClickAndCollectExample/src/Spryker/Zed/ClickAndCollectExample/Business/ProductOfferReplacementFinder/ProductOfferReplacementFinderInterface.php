<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ProductOfferReplacementFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param list<\Generated\Shared\Transfer\ProductOfferServicePointTransfer> $productOfferServicePointTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findSuitableProductOffer(
        ItemTransfer $itemTransfer,
        QuoteTransfer $quoteTransfer,
        array $productOfferServicePointTransfers
    ): ?ProductOfferTransfer;
}
