<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductVolume\VolumePriceUpdater;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\PriceProductVolume\Dependency\Service\PriceProductVolumeToUtilEncodingServiceInterface;
use Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig;

class VolumePriceUpdater implements VolumePriceUpdaterInterface
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
     * @param \Generated\Shared\Transfer\PriceProductTransfer $newVolumePriceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function addVolumePrice(
        PriceProductTransfer $priceProductTransfer,
        PriceProductTransfer $newVolumePriceProductTransfer
    ): PriceProductTransfer {
        $volumePriceData = $this->addNewVolumePriceDataElement(
            $this->getVolumePriceData($priceProductTransfer),
            $newVolumePriceProductTransfer
        );

        return $this->setVolumePriceData($priceProductTransfer, $volumePriceData);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $volumePriceProductTransferToDelete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function deleteVolumePrice(
        PriceProductTransfer $priceProductTransfer,
        PriceProductTransfer $volumePriceProductTransferToDelete
    ): PriceProductTransfer {
        $volumePriceData = $this->getVolumePriceData($priceProductTransfer);

        foreach ($volumePriceData as $index => $volumePriceDataElement) {
            if ($volumePriceDataElement[PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY] === $volumePriceProductTransferToDelete->getVolumeQuantity()) {
                unset($volumePriceData[$index]);

                break;
            }
        }

        return $this->setVolumePriceData($priceProductTransfer, $volumePriceData);
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
     * @param array $volumePriceData
     * @param \Generated\Shared\Transfer\PriceProductTransfer $newVolumePriceProductTransfer
     *
     * @return array
     */
    protected function addNewVolumePriceDataElement(
        array $volumePriceData,
        PriceProductTransfer $newVolumePriceProductTransfer
    ): array {
        $moneyValueTransfer = $newVolumePriceProductTransfer->getMoneyValueOrFail();

        $volumePriceData[] = [
            PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY => $newVolumePriceProductTransfer->getVolumeQuantity(),
            PriceProductVolumeConfig::VOLUME_PRICE_NET_PRICE => $moneyValueTransfer->getNetAmount(),
            PriceProductVolumeConfig::VOLUME_PRICE_GROSS_PRICE => $moneyValueTransfer->getGrossAmount(),
        ];

        $volumePriceData = $this->sortVolumePriceDataByQuantity($volumePriceData);

        return $volumePriceData;
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
            true
        );

        if (!is_array($priceData)) {
            $priceData = [];
        }

        return $priceData[PriceProductVolumeConfig::VOLUME_PRICE_TYPE] ?? [];
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param array $volumePriceData
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function setVolumePriceData(
        PriceProductTransfer $priceProductTransfer,
        array $volumePriceData
    ): PriceProductTransfer {
        $priceData[PriceProductVolumeConfig::VOLUME_PRICE_TYPE] = array_values($volumePriceData);

        $priceDataJson = $this->utilEncodingService->encodeJson($priceData);

        $priceProductTransfer->getMoneyValueOrFail()->setPriceData($priceDataJson);

        return $priceProductTransfer;
    }

    /**
     * @param array $volumePriceData
     *
     * @return array
     */
    protected function sortVolumePriceDataByQuantity(array $volumePriceData): array
    {
        $compareFunction = function (array $volumePriceDataA, array $volumePriceDataB): int {
            $quantityA = $volumePriceDataA[PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY];
            $quantityB = $volumePriceDataB[PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY];

            if ($quantityA == $quantityB) {
                return 0;
            }

            return ($quantityA < $quantityB) ? -1 : 1;
        };

        uasort($volumePriceData, $compareFunction);

        return $volumePriceData;
    }
}
