<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Filter;

use Generated\Shared\Transfer\PriceProductTableCriteriaTransfer;

class MerchantRelationshipPriceProductFilter implements MerchantRelationshipPriceProductFilterInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function filterPriceProductCollection(array $priceProductTransfers, PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer): array
    {
        $result = [];

        if (!$priceProductTableCriteriaTransfer->getFilterInMerchantRelationships()) {
            return $priceProductTransfers;
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if (
                in_array(
                    $priceProductTransfer->getPriceDimensionOrFail()->getIdMerchantRelationship(),
                    $priceProductTableCriteriaTransfer->getFilterInMerchantRelationships(),
                )
            ) {
                $result[] = $priceProductTransfer;
            }
        }

        return $result;
    }
}
