<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service;

use Generated\Shared\Transfer\PriceProductTransfer;

class ProductOfferMerchantPortalGuiToPriceProductVolumeServiceBridge implements ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface
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
        return $this->priceProductVolumeService->addVolumePrice($priceProductTransfer, $newVolumePriceProductTransfer);
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
        return $this->priceProductVolumeService
            ->deleteVolumePrice($priceProductTransfer, $volumePriceProductTransferToDelete);
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
        return $this->priceProductVolumeService->extractVolumePrice(
            $priceProductTransfer,
            $volumePriceProductTransfer
        );
    }
}
