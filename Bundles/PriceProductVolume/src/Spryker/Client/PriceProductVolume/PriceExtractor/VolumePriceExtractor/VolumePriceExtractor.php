<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductVolume\PriceExtractor\VolumePriceExtractor;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Client\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface;
use Spryker\Client\PriceProductVolume\PriceExtractor\PriceProductReader\PriceProductReaderInterface;
use Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig;

class VolumePriceExtractor implements VolumePriceExtractorInterface
{
    /**
     * @var \Spryker\Client\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface
     */
    protected $utilEncoding;

    /**
     * @var \Spryker\Client\PriceProductVolume\PriceExtractor\PriceProductReader\PriceProductReaderInterface
     */
    protected $priceProductReader;

    /**
     * @param \Spryker\Client\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface $utilEncoding
     * @param \Spryker\Client\PriceProductVolume\PriceExtractor\PriceProductReader\PriceProductReaderInterface $priceProductReader
     */
    public function __construct(
        PriceProductVolumeToUtilEncodingServiceInterface $utilEncoding,
        PriceProductReaderInterface $priceProductReader
    ) {
        $this->utilEncoding = $utilEncoding;
        $this->priceProductReader = $priceProductReader;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractProductPricesForProductAbstract(array $priceProductTransfers): array
    {
        return $this->extractPriceProductVolumeTransfersFromArray($priceProductTransfers);
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractProductPricesForProductConcrete(int $idProductConcrete, array $priceProductTransfers): array
    {
        $extractedPrices = $this->extractPriceProductVolumeTransfersFromArray($priceProductTransfers);

        if (empty($extractedPrices) && !empty($priceProductTransfers)) {
            $abstractProductPrices = $this->priceProductReader->getPriceProductAbstractFromPriceProduct(
                $idProductConcrete
            );
            $extractedPrices = $this->extractPriceProductVolumeTransfersFromArray($abstractProductPrices);
        }

        return array_merge($extractedPrices, $priceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function extractPriceProductVolumeTransfersFromArray(array $priceProductTransfers): array
    {
        $extractedPrices = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $extractedPrices = array_merge(
                $extractedPrices,
                $this->extractPriceProductVolumes($priceProductTransfer)
            );
        }

        return $extractedPrices;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractPriceProductVolumes(PriceProductTransfer $priceProductTransfer): array
    {
        if (!$priceProductTransfer->getMoneyValue()->getPriceData()) {
            return [];
        }

        $priceProductTransfers = [];
        $priceData = $this->utilEncoding->decodeJson($priceProductTransfer->getMoneyValue()->getPriceData(), true);

        if (!is_array($priceData) || !isset($priceData[PriceProductVolumeConfig::VOLUME_PRICE_TYPE])) {
            return [];
        }

        foreach ($priceData[PriceProductVolumeConfig::VOLUME_PRICE_TYPE] as $volumePrice) {
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
            ->setVolumeQuantity($volumePrice[PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY]);

        $volumePriceTransfer
            ->setGroupKey(
                sprintf(
                    '%s-%s',
                    $volumePriceTransfer->getGroupKey(),
                    $volumePrice[PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY]
                )
            )
            ->setIsMergeable(false)
            ->getMoneyValue()
            ->setGrossAmount($volumePrice[PriceProductVolumeConfig::VOLUME_PRICE_GROSS_PRICE])
            ->setNetAmount($volumePrice[PriceProductVolumeConfig::VOLUME_PRICE_NET_PRICE]);

        $volumePriceTransfer = $this->setPriceData($volumePriceTransfer, $volumePrice);

        return $volumePriceTransfer;
    }

    /**
     * @deprecated Will be removed with next major release.
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $volumePriceTransfer
     * @param array $volumePrice
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function setPriceData(PriceProductTransfer $volumePriceTransfer, array $volumePrice): PriceProductTransfer
    {
        $volumePriceTransfer->getMoneyValue()
            ->setPriceData($this->utilEncoding->encodeJson([
                PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY => $volumePrice[PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY],
            ]));

        return $volumePriceTransfer;
    }
}
