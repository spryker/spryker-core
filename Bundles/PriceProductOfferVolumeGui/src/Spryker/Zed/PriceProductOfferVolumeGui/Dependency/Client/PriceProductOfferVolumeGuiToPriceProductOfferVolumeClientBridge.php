<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolumeGui\Dependency\Client;

class PriceProductOfferVolumeGuiToPriceProductOfferVolumeClientBridge implements PriceProductOfferVolumeGuiToPriceProductOfferVolumeClientInterface
{
    /**
     * @var \Spryker\Client\PriceProductOfferVolume\PriceProductOfferVolumeClientInterface
     */
    protected $priceProductOfferVolumeClient;

    /**
     * @param \Spryker\Client\PriceProductOfferVolume\PriceProductOfferVolumeClientInterface $priceProductOfferVolumeClient
     */
    public function __construct($priceProductOfferVolumeClient)
    {
        $this->priceProductOfferVolumeClient = $priceProductOfferVolumeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractProductPrices(array $priceProductTransfers): array
    {
        return $this->priceProductOfferVolumeClient->extractProductPrices($priceProductTransfers);
    }
}
