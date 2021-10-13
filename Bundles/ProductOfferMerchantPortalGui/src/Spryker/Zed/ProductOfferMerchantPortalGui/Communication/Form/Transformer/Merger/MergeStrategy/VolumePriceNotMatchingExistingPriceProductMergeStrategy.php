<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger\MergeStrategy;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductTransfer;

class VolumePriceNotMatchingExistingPriceProductMergeStrategy extends AbstractPriceProductComparisonMergeStrategy implements PriceProductMergeStrategyInterface
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
                return null;
            }
        }

        $basePriceProductTransfer = $this->createNewBasePriceProductTransferForVolumePrice(
            $newPriceProductTransfer
        );

        $basePriceProductTransfer = $this->priceProductVolumeService
            ->addVolumePrice(
                $basePriceProductTransfer,
                $newPriceProductTransfer
            );

        $priceProductTransfers->append($basePriceProductTransfer);

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $newPriceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createNewBasePriceProductTransferForVolumePrice(
        PriceProductTransfer $newPriceProductTransfer
    ): PriceProductTransfer {
        $priceProductTransfer = (new PriceProductTransfer())
            ->fromArray($newPriceProductTransfer->toArray())
            ->setVolumeQuantity(null);

        $priceProductTransfer->getMoneyValueOrFail()
            ->setGrossAmount(null)
            ->setNetAmount(null);

        return $priceProductTransfer;
    }
}
