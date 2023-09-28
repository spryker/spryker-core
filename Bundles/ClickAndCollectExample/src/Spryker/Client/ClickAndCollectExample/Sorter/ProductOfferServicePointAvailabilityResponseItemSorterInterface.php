<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\ClickAndCollectExample\Sorter;

interface ProductOfferServicePointAvailabilityResponseItemSorterInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer> $productOfferServicePointAvailabilityResponseItemTransfers
     * @param list<string> $requestedProductOfferReferences
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>
     */
    public function sortProductOfferServicePointAvailabilityResponseItemTransfersByRequestedProductOffers(
        array $productOfferServicePointAvailabilityResponseItemTransfers,
        array $requestedProductOfferReferences
    ): array;
}
