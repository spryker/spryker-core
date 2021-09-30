<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductMerchantRelationship\Filter;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig;

class MerchantRelationshipPriceProductFilter implements MerchantRelationshipPriceProductFilterInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function filterPriceProductsByMerchantRelationship(
        array $priceProductTransfers,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): array {
        $priceProductTransfers = $this->filterPriceProductsByActiveMerchants($priceProductTransfers, $priceProductFilterTransfer);

        if (!$this->isPriceProductFilterHasMerchantRelationship($priceProductFilterTransfer)) {
            return $priceProductTransfers;
        }

        return $this->filterOutPriceProductTransfersWithIncorrectMerchantRelationship(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return bool
     */
    protected function isPriceProductFilterHasMerchantRelationship(PriceProductFilterTransfer $priceProductFilterTransfer): bool
    {
        return $priceProductFilterTransfer->getPriceDimension() && $priceProductFilterTransfer->getPriceDimension()->getIdMerchantRelationship();
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function filterOutPriceProductTransfersWithIncorrectMerchantRelationship(
        array $priceProductTransfers,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): array {
        return array_filter($priceProductTransfers, function (PriceProductTransfer $priceProductTransfer) use ($priceProductFilterTransfer) {
            return !$this->isPriceProductHasMerchantRelationship($priceProductTransfer)
                || $this->isSameMerchantRelationship($priceProductTransfer, $priceProductFilterTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function isPriceProductHasMerchantRelationship(PriceProductTransfer $priceProductTransfer): bool
    {
        return $priceProductTransfer->getPriceDimension() && $priceProductTransfer->getPriceDimension()->getIdMerchantRelationship();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return bool
     */
    protected function isSameMerchantRelationship(
        PriceProductTransfer $priceProductTransfer,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): bool {
        return $priceProductTransfer->getPriceDimension()
            && $priceProductFilterTransfer->getPriceDimension()
            && $priceProductTransfer->getPriceDimension()->getIdMerchantRelationship() === $priceProductFilterTransfer->getPriceDimension()->getIdMerchantRelationship();
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function filterPriceProductsByActiveMerchants(
        array $priceProductTransfers,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): array {
        return array_filter($priceProductTransfers, function ($priceProductTransfer) {
            $priceProductDimensionTransfer = $priceProductTransfer->getPriceDimension();
            if (!$priceProductDimensionTransfer) {
                return true;
            }
            if ($priceProductDimensionTransfer->getType() !== PriceProductMerchantRelationshipConfig::PRICE_DIMENSION_MERCHANT_RELATIONSHIP) {
                return true;
            }

            return $priceProductDimensionTransfer->getIsMerchantActive() !== false;
        });
    }
}
