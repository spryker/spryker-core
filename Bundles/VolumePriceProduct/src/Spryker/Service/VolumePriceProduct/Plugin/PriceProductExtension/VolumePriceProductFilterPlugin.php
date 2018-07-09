<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\VolumePriceProduct\Plugin\PriceProductExtension;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductFilterPluginInterface;
use Spryker\Shared\PriceProductStorage\PriceProductStorageConstants;

/**
 * @method \Spryker\Service\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig getConfig()
 */
class VolumePriceProductFilterPlugin extends AbstractPlugin implements PriceProductFilterPluginInterface
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
        if ((int)$priceProductFilterTransfer->getQuantity() <= 1) {
            array_filter($priceProductTransfers, [$this, 'isNotVolumePrice']);

            return $priceProductTransfers;
        }

        /** @var \Generated\Shared\Transfer\PriceProductTransfer $minPriceProductTransfer */
        $minPriceProductTransfer = null;

        foreach ($priceProductTransfers as $priceProductTransfer) {

            if (!$priceProductTransfer->getQuantityToApply()) {
                continue;
            }

            if ($priceProductFilterTransfer->getQuantity() >= $priceProductTransfer->getQuantityToApply()) {
                if ($minPriceProductTransfer == null) {
                    $minPriceProductTransfer = $priceProductTransfer;

                    continue;
                }

                $minPriceProductTransfer = $this->getMinPrice($minPriceProductTransfer, $priceProductTransfer);
            }
        }

        if ($minPriceProductTransfer == null) {
            return $priceProductTransfers;
        }

        return [$minPriceProductTransfer];
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
     * @param PriceProductTransfer $minPrice
     * @param PriceProductTransfer $priceToCompare
     *
     * @return PriceProductTransfer
     */
    protected function getMinPrice(PriceProductTransfer $minPrice, PriceProductTransfer $priceToCompare): PriceProductTransfer
    {
        if ($minPrice->getQuantityToApply() < $priceToCompare->getQuantityToApply()) {
            return $minPrice;
        }

        return $priceToCompare;
    }

    protected function isNotVolumePrice(PriceProductTransfer $priceProductTransfer): bool
    {
        return ((int)$priceProductTransfer->getQuantityToApply() > 0);
    }
}
