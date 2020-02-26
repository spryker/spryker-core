<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferVolume\VolumePriceExtractor;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\PriceProductOfferVolume\PriceProductOfferVolumeConfig;

class VolumePriceExtractor implements VolumePriceExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractProductPricesForProductOffer(array $priceProductTransfers): array
    {
        $extractedPrices = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $extractedPrices = array_merge(
                $extractedPrices,
                $this->extractPriceProductOfferVolumes($priceProductTransfer)
            );
        }

        return $extractedPrices;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function extractPriceProductOfferVolumes(PriceProductTransfer $priceProductTransfer): array
    {
        if (!$priceProductTransfer->getMoneyValue()->getPriceData()) {
            return [];
        }

        $priceProductTransfers = [];
        $priceData = json_decode($priceProductTransfer->getMoneyValue()->getPriceData(), true);

        if (!is_array($priceData) || !isset($priceData[PriceProductOfferVolumeConfig::VOLUME_PRICE_TYPE])) {
            return [];
        }

        foreach ($priceData[PriceProductOfferVolumeConfig::VOLUME_PRICE_TYPE] as $volumePrice) {
            $priceProductTransfers[] = $this->mapVolumePriceToPriceProductTransfer($priceProductTransfer, $volumePrice);
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param array $volumePrice
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapVolumePriceToPriceProductTransfer(PriceProductTransfer $priceProductTransfer, array $volumePrice): PriceProductTransfer
    {
        $volumePriceTransfer = (new PriceProductTransfer())
            ->fromArray($priceProductTransfer->toArray(), true)
            ->setVolumeQuantity($volumePrice[PriceProductOfferVolumeConfig::VOLUME_PRICE_QUANTITY]);

        $volumePriceTransfer
            ->setGroupKey(
                sprintf(
                    '%s-%s',
                    $volumePriceTransfer->getGroupKey(),
                    $volumePrice[PriceProductOfferVolumeConfig::VOLUME_PRICE_QUANTITY]
                )
            )
            ->setIsMergeable(false)
            ->getMoneyValue()
            ->setGrossAmount($volumePrice[PriceProductOfferVolumeConfig::VOLUME_PRICE_GROSS_PRICE])
            ->setNetAmount($volumePrice[PriceProductOfferVolumeConfig::VOLUME_PRICE_NET_PRICE])
            ->setPriceData(json_encode([
                PriceProductOfferVolumeConfig::VOLUME_PRICE_QUANTITY => $volumePrice[PriceProductOfferVolumeConfig::VOLUME_PRICE_QUANTITY],
            ]));

        return $volumePriceTransfer;
    }
}
