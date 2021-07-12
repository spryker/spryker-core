<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Business\Expander;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\PriceProductOfferVolume\PriceProductOfferVolumeConfig;
use Spryker\Zed\PriceProductOfferVolume\Dependency\Service\PriceProductOfferVolumeToUtilEncodingInterface;

class PriceProductOfferVolumeExpander implements PriceProductOfferVolumeExpanderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductOfferVolume\Dependency\Service\PriceProductOfferVolumeToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\PriceProductOfferVolume\Dependency\Service\PriceProductOfferVolumeToUtilEncodingInterface $utilEncodingService
     */
    public function __construct(PriceProductOfferVolumeToUtilEncodingInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function expand(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $volumeQuantity = 1;

        if (!$priceProductTransfer->getMoneyValue()) {
            return $priceProductTransfer->setVolumeQuantity($volumeQuantity);
        }

        $volumeQuantity = $this->extractVolumeQuantity($priceProductTransfer) ?: $volumeQuantity;

        return $priceProductTransfer->setVolumeQuantity($volumeQuantity);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return int|null
     */
    protected function extractVolumeQuantity(PriceProductTransfer $priceProductTransfer): ?int
    {
        if ($priceProductTransfer->getMoneyValueOrFail()->getPriceData() === null) {
            return null;
        }

        $priceData = $this->utilEncodingService->decodeJson(
            $priceProductTransfer->getMoneyValueOrFail()->getPriceData(),
            true
        );
        if (!is_array($priceData)) {
            $priceData = [];
        }

        if (array_key_exists(PriceProductOfferVolumeConfig::VOLUME_PRICE_QUANTITY, $priceData)) {
            return (int)$priceData[PriceProductOfferVolumeConfig::VOLUME_PRICE_QUANTITY];
        }

        return null;
    }
}
