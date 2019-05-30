<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductVolume\Plugin\PriceProductExtension;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductFilterPluginInterface;
use Spryker\Shared\PriceProductStorage\PriceProductStorageConstants;

/**
 * @method \Spryker\Service\PriceProductVolume\PriceProductVolumeServiceFactory getFactory()
 */
class PriceProductVolumeFilterPlugin extends AbstractPlugin implements PriceProductFilterPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function filter(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): array
    {
        if ($priceProductFilterTransfer->getQuantity() === null) {
            return $this->getFilteredPriceProductTransfers($priceProductTransfers);
        }

        $minPriceProductTransfer = $this->getMinPrice(
            $priceProductTransfers,
            $priceProductFilterTransfer->getQuantity()
        );

        if ($minPriceProductTransfer == null) {
            return $this->getFilteredPriceProductTransfers($priceProductTransfers);
        }

        return [$minPriceProductTransfer];
    }

    /**
     * @param array $priceProductTransfers
     *
     * @return array
     */
    protected function getFilteredPriceProductTransfers(array $priceProductTransfers): array
    {
        $priceProductTransfers = array_filter($priceProductTransfers, [$this, 'filterVolumePrices']);

        return $priceProductTransfers;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getDimensionName(): string
    {
        return PriceProductStorageConstants::PRICE_DIMENSION_DEFAULT;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param float $filterQuantity
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    protected function getMinPrice(array $priceProductTransfers, float $filterQuantity): ?PriceProductTransfer
    {
        $minPriceProductTransfer = null;
        $utilQuantityService = $this->getFactory()->getUtilQuantityService();

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if (!$priceProductTransfer->getVolumeQuantity()) {
                continue;
            }

            if ($utilQuantityService->isQuantityLessOrEqual($priceProductTransfer->getVolumeQuantity(), $filterQuantity)) {
                if ($minPriceProductTransfer == null) {
                    $minPriceProductTransfer = $priceProductTransfer;

                    continue;
                }

                $minPriceProductTransfer = $this->resolvePrice($minPriceProductTransfer, $priceProductTransfer);
            }
        }

        return $minPriceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $minPrice
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceToCompare
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function resolvePrice(PriceProductTransfer $minPrice, PriceProductTransfer $priceToCompare): PriceProductTransfer
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
    protected function filterVolumePrices(PriceProductTransfer $priceProductTransfer): bool
    {
        return $priceProductTransfer->getVolumeQuantity() == null;
    }
}
