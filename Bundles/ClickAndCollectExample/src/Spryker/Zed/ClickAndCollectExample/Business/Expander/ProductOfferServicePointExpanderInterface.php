<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business\Expander;

use Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer;

interface ProductOfferServicePointExpanderInterface
{
    /**
     * @param array<int, \Generated\Shared\Transfer\ProductOfferServicePointTransfer> $productOfferServicePointTransfersIndexedByIdProductOffer
     *
     * @return array<int, \Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    public function expandProductOfferServicePointsWithProductOfferStocks(array $productOfferServicePointTransfersIndexedByIdProductOffer): array;

    /**
     * @param array<int, \Generated\Shared\Transfer\ProductOfferServicePointTransfer> $productOfferServicePointTransfersIndexedByIdProductOffer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    public function expandProductOfferServicePointsWithProductOfferPrices(
        array $productOfferServicePointTransfersIndexedByIdProductOffer,
        ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
    ): array;
}
