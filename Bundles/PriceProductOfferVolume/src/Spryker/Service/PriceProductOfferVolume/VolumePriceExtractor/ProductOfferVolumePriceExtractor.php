<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductOfferVolume\VolumePriceExtractor;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\PriceProductOfferVolume\Dependency\Service\PriceProductOfferVolumeToUtilEncodingServiceInterface;
use Spryker\Shared\PriceProductOfferVolume\PriceProductOfferVolumeConfig;

class ProductOfferVolumePriceExtractor implements ProductOfferVolumePriceExtractorInterface
{
    /**
     * @var \Spryker\Service\PriceProductOfferVolume\Dependency\Service\PriceProductOfferVolumeToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Service\PriceProductOfferVolume\Dependency\Service\PriceProductOfferVolumeToUtilEncodingServiceInterface $utilEncodingService
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
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();

        if (!$moneyValueTransfer->getPriceData()) {
            return $extractedPrices;
        }

        /** @var string $priceData */
        $priceData = $moneyValueTransfer->getPriceData();

        /** @var mixed[] $priceData */
        $priceData = $this->utilEncodingService->decodeJson($priceData, true);

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
     * @phpstan-param array<mixed> $volumePriceData
     *
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
            ->setIsMergeable(false);

        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
        $moneyValueTransfer
            ->setGrossAmount($volumePriceData[PriceProductOfferVolumeConfig::VOLUME_PRICE_GROSS_PRICE])
            ->setNetAmount($volumePriceData[PriceProductOfferVolumeConfig::VOLUME_PRICE_NET_PRICE])
            ->setPriceData(
                $this->utilEncodingService->encodeJson([
                    PriceProductOfferVolumeConfig::VOLUME_PRICE_QUANTITY => $volumePriceData[PriceProductOfferVolumeConfig::VOLUME_PRICE_QUANTITY],
                ])
            );

        return $priceProductTransfer->setMoneyValue($moneyValueTransfer);
    }
}
