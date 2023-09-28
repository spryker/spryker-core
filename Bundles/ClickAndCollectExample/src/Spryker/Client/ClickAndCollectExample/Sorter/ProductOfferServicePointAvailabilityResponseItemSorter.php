<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\ClickAndCollectExample\Sorter;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer;

class ProductOfferServicePointAvailabilityResponseItemSorter implements ProductOfferServicePointAvailabilityResponseItemSorterInterface
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
    ): array {
        $prioritizedProductOfferServicePointAvailabilityResponseItemTransfers = [];
        $regularProductOfferServicePointAvailabilityResponseItemTransfers = [];

        foreach ($productOfferServicePointAvailabilityResponseItemTransfers as $productOfferServicePointAvailabilityResponseItemTransfer) {
            if ($this->isPrioritizedProductOfferServicePointAvailabilityResponseItemTransfer($productOfferServicePointAvailabilityResponseItemTransfer, $requestedProductOfferReferences)) {
                $prioritizedProductOfferServicePointAvailabilityResponseItemTransfers[] = $productOfferServicePointAvailabilityResponseItemTransfer;

                continue;
            }

            $regularProductOfferServicePointAvailabilityResponseItemTransfers[] = $productOfferServicePointAvailabilityResponseItemTransfer;
        }

        return array_merge(
            $prioritizedProductOfferServicePointAvailabilityResponseItemTransfers,
            $regularProductOfferServicePointAvailabilityResponseItemTransfers,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer $productOfferServicePointAvailabilityResponseItemTransfer
     * @param list<string> $requestedProductOfferReferences
     *
     * @return bool
     */
    protected function isPrioritizedProductOfferServicePointAvailabilityResponseItemTransfer(
        ProductOfferServicePointAvailabilityResponseItemTransfer $productOfferServicePointAvailabilityResponseItemTransfer,
        array $requestedProductOfferReferences
    ): bool {
        $productOfferReference = $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference();

        if (!$productOfferReference) {
            return false;
        }

        return in_array($productOfferReference, $requestedProductOfferReferences, true);
    }
}
