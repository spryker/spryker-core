<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Dependency\Service;

use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductOfferVolumeToPriceProductVolumeBridge implements PriceProductOfferVolumeToPriceProductVolumeInterface
{
    /**
     * @var \Spryker\Service\PriceProductVolume\PriceProductVolumeServiceInterface
     */
    protected $priceProductVolumeService;

    /**
     * @param \Spryker\Service\PriceProductVolume\PriceProductVolumeServiceInterface $priceProductVolumeService
     */
    public function __construct($priceProductVolumeService)
    {
        $this->priceProductVolumeService = $priceProductVolumeService;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    public function hasVolumePrices(PriceProductTransfer $priceProductTransfer): bool
    {
        return $this->priceProductVolumeService->hasVolumePrices($priceProductTransfer);
    }
}
