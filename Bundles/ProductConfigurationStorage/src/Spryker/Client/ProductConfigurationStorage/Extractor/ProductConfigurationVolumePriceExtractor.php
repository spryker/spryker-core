<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Extractor;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Service\ProductConfigurationStorageToUtilEncodingServiceInterface;

class ProductConfigurationVolumePriceExtractor implements ProductConfigurationVolumePriceExtractorInterface
{
    /**
     * @uses \Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig::VOLUME_PRICE_TYPE
     */
    protected const VOLUME_PRICE_KEY = 'volume_prices';

    /**
     * @uses \Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY
     */
    protected const VOLUME_PRICE_QUANTITY_KEY = 'quantity';

    /**
     * @uses \Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig::VOLUME_PRICE_NET_PRICE
     */
    protected const VOLUME_PRICE_NET_PRICE_KEY = 'net_price';

    /**
     * @uses \Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig::VOLUME_PRICE_GROSS_PRICE
     */
    protected const VOLUME_PRICE_GROSS_PRICE_KEY = 'gross_price';

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\Service\ProductConfigurationStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Service\ProductConfigurationStorageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ProductConfigurationStorageToUtilEncodingServiceInterface $utilEncodingService)
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
            $extractedPrices = $this->extractProductConfigurationVolumePrices($extractedPrices, $priceProductTransfer);
        }

        return $extractedPrices;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $extractedPrices
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function extractProductConfigurationVolumePrices(array $extractedPrices, PriceProductTransfer $priceProductTransfer): array
    {
        if (!$priceProductTransfer->getMoneyValue()->getPriceData()) {
            return $extractedPrices;
        }

        $priceData = $this->utilEncodingService->decodeJson($priceProductTransfer->getMoneyValue()->getPriceData(), true);

        if (!isset($priceData[static::VOLUME_PRICE_KEY])) {
            return $extractedPrices;
        }

        foreach ($priceData[static::VOLUME_PRICE_KEY] as $volumePrice) {
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
        $priceProductTransfer->setVolumeQuantity($volumePriceData[static::VOLUME_PRICE_QUANTITY_KEY]);

        $priceProductTransfer
            ->setGroupKey(
                sprintf(
                    '%s-%s',
                    $priceProductTransfer->getGroupKey(),
                    $volumePriceData[static::VOLUME_PRICE_QUANTITY_KEY]
                )
            )
            ->setIsMergeable(false)
            ->getMoneyValue()
            ->setGrossAmount($volumePriceData[static::VOLUME_PRICE_GROSS_PRICE_KEY])
            ->setNetAmount($volumePriceData[static::VOLUME_PRICE_NET_PRICE_KEY])
            ->setPriceData(json_encode([
                static::VOLUME_PRICE_QUANTITY_KEY => $volumePriceData[static::VOLUME_PRICE_QUANTITY_KEY],
            ]));

        return $priceProductTransfer;
    }
}
