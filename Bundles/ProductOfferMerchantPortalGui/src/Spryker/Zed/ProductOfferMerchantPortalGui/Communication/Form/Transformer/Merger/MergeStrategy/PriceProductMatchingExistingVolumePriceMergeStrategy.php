<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger\MergeStrategy;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductMatchingExistingVolumePriceMergeStrategy extends AbstractPriceProductComparisonMergeStrategy implements PriceProductMergeStrategyInterface
{
    /**
     * @phpstan-param ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @phpstan-return ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer $newPriceProductTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]|null
     */
    public function merge(
        ArrayObject $priceProductTransfers,
        PriceProductTransfer $newPriceProductTransfer
    ): ?ArrayObject {
        if ($newPriceProductTransfer->getVolumeQuantity() !== 1) {
            return null;
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if (
                $this->isUnpersistedVolumePrice($priceProductTransfer)
                && $this->isSame($priceProductTransfer, $newPriceProductTransfer)
                && $priceProductTransfer->getVolumeQuantity() !== $newPriceProductTransfer->getVolumeQuantity()
            ) {
                $this->mergeIntoUnpersistedVolumePrice(
                    $priceProductTransfer,
                    $newPriceProductTransfer
                );

                return $priceProductTransfers;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function isUnpersistedVolumePrice(PriceProductTransfer $priceProductTransfer): bool
    {
        return $priceProductTransfer->getIdPriceProduct() === null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $newPriceProductTransfer
     *
     * @return void
     */
    protected function mergeIntoUnpersistedVolumePrice(
        PriceProductTransfer $priceProductTransfer,
        PriceProductTransfer $newPriceProductTransfer
    ): void {
        $newMoneyValueTransfer = $newPriceProductTransfer->getMoneyValueOrFail();

        $priceProductTransfer
            ->setVolumeQuantity(1)
            ->getMoneyValueOrFail()
                ->setGrossAmount($newMoneyValueTransfer->getGrossAmount())
                ->setNetAmount($newMoneyValueTransfer->getNetAmount());
    }
}
