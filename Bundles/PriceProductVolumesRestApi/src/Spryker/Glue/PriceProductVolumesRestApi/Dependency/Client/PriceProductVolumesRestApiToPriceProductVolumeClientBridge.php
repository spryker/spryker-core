<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PriceProductVolumesRestApi\Dependency\Client;

class PriceProductVolumesRestApiToPriceProductVolumeClientBridge implements PriceProductVolumesRestApiToPriceProductVolumeClientInterface
{
    /**
     * @var \Spryker\Client\PriceProductVolume\PriceProductVolumeClientInterface
     */
    protected $priceProductVolumeClient;

    /**
     * @param \Spryker\Client\PriceProductVolume\PriceProductVolumeClientInterface $priceProductVolumeClient
     */
    public function __construct($priceProductVolumeClient)
    {
        $this->priceProductVolumeClient = $priceProductVolumeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractProductPricesForProductAbstract(array $priceProductTransfers): array
    {
        return $this->priceProductVolumeClient->extractProductPricesForProductAbstract($priceProductTransfers);
    }
}
