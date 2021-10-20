<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger\MergeStrategy;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductTransfer;

class VolumePriceMatchingExistingPriceProductMergeStrategy extends AbstractPriceProductComparisonMergeStrategy implements PriceProductMergeStrategyInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer $newPriceProductTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>|null
     */
    public function merge(
        ArrayObject $priceProductTransfers,
        PriceProductTransfer $newPriceProductTransfer
    ): ?ArrayObject {
        if ($newPriceProductTransfer->getVolumeQuantity() === 1) {
            return null;
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if ($this->isSame($priceProductTransfer, $newPriceProductTransfer)) {
                $this->priceProductVolumeService
                    ->addVolumePrice(
                        $priceProductTransfer,
                        $newPriceProductTransfer,
                    );

                return $priceProductTransfers;
            }
        }

        return null;
    }
}
