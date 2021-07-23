<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\MergeStrategy;

use Generated\Shared\Transfer\PriceProductTransfer;

abstract class AbstractPriceProductMergeStrategy implements PriceProductMergeStrategyInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function isNewPriceProductTransfer(PriceProductTransfer $priceProductTransfer): bool
    {
        return $priceProductTransfer->getIdPriceProduct() === null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function isBasePriceProduct(PriceProductTransfer $priceProductTransfer): bool
    {
        return $priceProductTransfer->getVolumeQuantityOrFail() === 0;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function isVolumePriceProduct(PriceProductTransfer $priceProductTransfer): bool
    {
        return $priceProductTransfer->getVolumeQuantityOrFail() !== 1;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransferToCompare
     *
     * @return bool
     */
    protected function isSamePriceProduct(
        PriceProductTransfer $priceProductTransfer,
        PriceProductTransfer $priceProductTransferToCompare
    ): bool {
        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();
        $moneyValueTransferToCompare = $priceProductTransferToCompare->getMoneyValueOrFail();

        return $priceProductTransfer->getFkPriceTypeOrFail() === $priceProductTransferToCompare->getFkPriceTypeOrFail()
            && $moneyValueTransfer->getFkCurrencyOrFail() === $moneyValueTransferToCompare->getFkCurrencyOrFail()
            && $moneyValueTransfer->getFkStoreOrFail() === $moneyValueTransferToCompare->getFkStoreOrFail();
    }
}
