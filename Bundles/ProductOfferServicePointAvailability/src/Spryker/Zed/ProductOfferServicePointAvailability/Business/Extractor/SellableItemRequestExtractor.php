<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointAvailability\Business\Extractor;

class SellableItemRequestExtractor implements SellableItemRequestExtractorInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\SellableItemRequestTransfer> $sellableItemRequestTransfers
     *
     * @return list<string>
     */
    public function extractProductOfferReferencesFromSellableItemRequestTransfers(array $sellableItemRequestTransfers): array
    {
        $productOfferReferences = [];

        foreach ($sellableItemRequestTransfers as $sellableItemRequestTransfer) {
            $productOfferReferences[] = $sellableItemRequestTransfer->getProductAvailabilityCriteriaOrFail()->getProductOfferReferenceOrFail();
        }

        return $productOfferReferences;
    }
}
