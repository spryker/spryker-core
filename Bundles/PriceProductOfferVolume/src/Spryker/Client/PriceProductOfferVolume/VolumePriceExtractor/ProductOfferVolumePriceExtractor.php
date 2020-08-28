<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferVolume\VolumePriceExtractor;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Client\PriceProductOfferVolume\Dependency\Service\PriceProductOfferVolumeToUtilEncodingServiceInterface;
use Spryker\Shared\PriceProductOfferVolume\PriceProductOfferVolumeConfig;

class ProductOfferVolumePriceExtractor implements ProductOfferVolumePriceExtractorInterface
{
    /**
     * @var \Spryker\Client\PriceProductOfferVolume\Dependency\Service\PriceProductOfferVolumeToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\PriceProductOfferVolume\Dependency\Service\PriceProductOfferVolumeToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(PriceProductOfferVolumeToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractProductPrices(array $priceProductTransfers): array
    {
        $extractedPrices = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $extractedPrices = $this->extractPriceProductOfferVolumes($extractedPrices, $priceProductTransfer);
        }

        return $extractedPrices;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $extractedPrices
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function extractPriceProductOfferVolumes(array $extractedPrices, PriceProductTransfer $priceProductTransfer): array
    {
        if (!$priceProductTransfer->getMoneyValue()->getPriceData()) {
            return $extractedPrices;
        }

        $priceData = $this->utilEncodingService->decodeJson($priceProductTransfer->getMoneyValue()->getPriceData(), true);

        if (!isset($priceData[PriceProductOfferVolumeConfig::VOLUME_PRICE_TYPE])) {
            return $extractedPrices;
        }

        foreach ($priceData[PriceProductOfferVolumeConfig::VOLUME_PRICE_TYPE] as $volumePrice) {
            $priceProductTransferForMapping = (new PriceProductTransfer())
                ->fromArray($priceProductTransfer->toArray(), true);

            $extractedPrices[] = $this->mapVolumePriceDataToPriceProductTransfer($volumePrice, $priceProductTransferForMapping);
        }

        return $extractedPrices;
    }

    /**
     * @param array $volumePriceData
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapVolumePriceDataToPriceProductTransfer(
        array $volumePriceData,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        $priceProductTransfer->setVolumeQuantity($volumePriceData[PriceProductOfferVolumeConfig::VOLUME_PRICE_QUANTITY]);

        $priceProductTransfer
            ->setGroupKey(
                sprintf(
                    '%s-%s',
                    $priceProductTransfer->getGroupKey(),
                    $volumePriceData[PriceProductOfferVolumeConfig::VOLUME_PRICE_QUANTITY]
                )
            )
            ->setIsMergeable(false)
            ->getMoneyValue()
            ->setGrossAmount($volumePriceData[PriceProductOfferVolumeConfig::VOLUME_PRICE_GROSS_PRICE])
            ->setNetAmount($volumePriceData[PriceProductOfferVolumeConfig::VOLUME_PRICE_NET_PRICE])
            ->setPriceData(json_encode([
                PriceProductOfferVolumeConfig::VOLUME_PRICE_QUANTITY => $volumePriceData[PriceProductOfferVolumeConfig::VOLUME_PRICE_QUANTITY],
            ]));

        return $priceProductTransfer;
    }
}
