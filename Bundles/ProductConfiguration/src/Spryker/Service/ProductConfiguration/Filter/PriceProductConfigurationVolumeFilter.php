<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfiguration\Filter;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductConfigurationVolumeFilter implements PriceProductConfigurationVolumeFilterInterface
{
    /**
     * @var int
     */
    protected const SINGLE_ITEM_QUANTITY = 1;

    /**
     * @var \Spryker\Service\ProductConfiguration\Filter\PriceProductConfigurationFilterInterface
     */
    protected $priceProductConfigurationFilter;

    /**
     * @param \Spryker\Service\ProductConfiguration\Filter\PriceProductConfigurationFilterInterface $priceProductConfigurationFilter
     */
    public function __construct(PriceProductConfigurationFilterInterface $priceProductConfigurationFilter)
    {
        $this->priceProductConfigurationFilter = $priceProductConfigurationFilter;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function filterProductConfigurationVolumePrices(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): array
    {
        $priceProductTransfers = $this->priceProductConfigurationFilter
            ->filterProductConfigurationPrices($priceProductTransfers, $priceProductFilterTransfer);

        if ($priceProductFilterTransfer->getQuantity() <= static::SINGLE_ITEM_QUANTITY) {
            return array_filter($priceProductTransfers, [$this, 'isSingleItemPrice']);
        }

        $minPriceProductTransfer = $this->findMinPrice(
            $priceProductTransfers,
            $priceProductFilterTransfer->getQuantityOrFail(),
        );

        if (!$minPriceProductTransfer) {
            return array_filter($priceProductTransfers, [$this, 'isSingleItemPrice']);
        }

        return [$minPriceProductTransfer];
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param int $filterQuantity
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    protected function findMinPrice(array $priceProductTransfers, int $filterQuantity): ?PriceProductTransfer
    {
        $minPriceProductTransfer = null;

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if (!$priceProductTransfer->getVolumeQuantity() || $priceProductTransfer->getVolumeQuantity() > $filterQuantity) {
                continue;
            }

            if (!$minPriceProductTransfer) {
                $minPriceProductTransfer = $priceProductTransfer;

                continue;
            }

            $minPriceProductTransfer = $this->resolveLowestPrice($minPriceProductTransfer, $priceProductTransfer);
        }

        return $minPriceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $minPrice
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceToCompare
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function resolveLowestPrice(PriceProductTransfer $minPrice, PriceProductTransfer $priceToCompare): PriceProductTransfer
    {
        if ($minPrice->getVolumeQuantity() > $priceToCompare->getVolumeQuantity()) {
            return $minPrice;
        }

        return $priceToCompare;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function isSingleItemPrice(PriceProductTransfer $priceProductTransfer): bool
    {
        return $priceProductTransfer->getVolumeQuantity() === null;
    }
}
