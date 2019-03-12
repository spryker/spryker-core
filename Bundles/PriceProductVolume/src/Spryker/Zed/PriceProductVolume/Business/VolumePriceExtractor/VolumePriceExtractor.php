<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Business\VolumePriceExtractor;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig;
use Spryker\Zed\PriceProductVolume\Business\PriceProductReader\PriceProductReaderInterface;
use Spryker\Zed\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface;

class VolumePriceExtractor implements VolumePriceExtractorInterface
{
    /**
     * @var \Spryker\Zed\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface
     */
    protected $utilEncoding;

    /**
     * @var \Spryker\Zed\PriceProductVolume\Business\PriceProductReader\PriceProductReaderInterface
     */
    protected $priceProductReader;

    /**
     * @param \Spryker\Zed\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface $utilEncoding
     * @param \Spryker\Zed\PriceProductVolume\Business\PriceProductReader\PriceProductReaderInterface $priceProductReader
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
    public function extractPriceProductVolumesForProductAbstract(array $priceProductTransfers): array
    {
        $extractedPrices = $this->extractPriceProductVolumeTransfersFromArray($priceProductTransfers);

        return array_merge($extractedPrices, $priceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractPriceProductVolumesForProductConcrete(array $priceProductTransfers): array
    {
        $extractedPrices = $this->extractPriceProductVolumeTransfersFromArray($priceProductTransfers);

        if (empty($extractedPrices) && !empty($priceProductTransfers)) {
            $abstractProductPrices = $this->priceProductReader->getPriceProductAbstractFromPriceProduct(
                $priceProductTransfers[0]
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
                $this->extractVolumePriceFromTransfer($priceProductTransfer)
            );
        }

        return $extractedPrices;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function extractVolumePriceFromTransfer(PriceProductTransfer $priceProductTransfer)
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
            ->setNetAmount($volumePrice[PriceProductVolumeConfig::VOLUME_PRICE_NET_PRICE])
            ->setPriceData($this->utilEncoding->encodeJson([]));

        return $volumePriceTransfer;
    }
}
