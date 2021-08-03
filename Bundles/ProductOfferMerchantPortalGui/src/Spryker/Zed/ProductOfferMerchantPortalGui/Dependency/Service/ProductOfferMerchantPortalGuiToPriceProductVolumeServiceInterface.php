<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service;

use Generated\Shared\Transfer\PriceProductTransfer;

interface ProductOfferMerchantPortalGuiToPriceProductVolumeServiceInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    public function hasVolumePrices(PriceProductTransfer $priceProductTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $newVolumePriceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function addVolumePrice(
        PriceProductTransfer $priceProductTransfer,
        PriceProductTransfer $newVolumePriceProductTransfer
    ): PriceProductTransfer;

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $volumePriceProductTransferToDelete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function deleteVolumePrice(
        PriceProductTransfer $priceProductTransfer,
        PriceProductTransfer $volumePriceProductTransferToDelete
    ): PriceProductTransfer;

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $volumePriceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function extractVolumePrice(
        PriceProductTransfer $priceProductTransfer,
        PriceProductTransfer $volumePriceProductTransfer
    ): ?PriceProductTransfer;
}
