<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductVolume\Reader;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface;
use Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig;

class VolumePriceReader implements VolumePriceReaderInterface
{
    /**
     * @var \Spryker\Service\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Service\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(PriceProductVolumeToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    public function hasVolumePrices(PriceProductTransfer $priceProductTransfer): bool
    {
        $volumePriceData = $this->getVolumePriceData($priceProductTransfer);

        return (bool)$volumePriceData;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $volumePriceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function extractVolumePrice(
        PriceProductTransfer $priceProductTransfer,
        PriceProductTransfer $volumePriceProductTransfer
    ): ?PriceProductTransfer {
        $volumePriceData = $this->getVolumePriceData($priceProductTransfer);
        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();

        foreach ($volumePriceData as $volumePriceDataElement) {
            if ($this->isSameQuantity($volumePriceDataElement, $volumePriceProductTransfer)) {
                $volumeMoneyValueTransfer = $this->getVolumeMoneyValueTransfer(
                    $volumePriceDataElement,
                    $moneyValueTransfer,
                );

                $volumePriceProductTransfer->setMoneyValue($volumeMoneyValueTransfer);

                return $volumePriceProductTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return array
     */
    protected function getVolumePriceData(PriceProductTransfer $priceProductTransfer): array
    {
        $priceData = $this->utilEncodingService->decodeJson(
            $priceProductTransfer->getMoneyValueOrFail()->getPriceData(),
            true,
        );

        if (!is_array($priceData)) {
            $priceData = [];
        }

        return $priceData[PriceProductVolumeConfig::VOLUME_PRICE_TYPE] ?? [];
    }

    /**
     * @param array $volumePriceDataElement
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function isSameQuantity(array $volumePriceDataElement, PriceProductTransfer $priceProductTransfer): bool
    {
        return (int)$volumePriceDataElement[PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY] === (int)$priceProductTransfer->getVolumeQuantityOrFail();
    }

    /**
     * @param array<mixed> $volumePriceDataItem
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function getVolumeMoneyValueTransfer(
        array $volumePriceDataItem,
        MoneyValueTransfer $moneyValueTransfer
    ): MoneyValueTransfer {
        return (new MoneyValueTransfer())
            ->setGrossAmount($volumePriceDataItem[PriceProductVolumeConfig::VOLUME_PRICE_GROSS_PRICE])
            ->setNetAmount($volumePriceDataItem[PriceProductVolumeConfig::VOLUME_PRICE_NET_PRICE])
            ->setCurrency($moneyValueTransfer->getCurrency())
            ->setFkStore($moneyValueTransfer->getFkStore())
            ->setStore($moneyValueTransfer->getStore())
            ->setFkCurrency($moneyValueTransfer->getFkCurrency());
    }
}
