<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductVolume;

use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductVolumeServiceInterface
{
    /**
     * Specification:
     * - Returns true if volume prices exist in `priceData` and false if don't.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    public function hasVolumePrices(PriceProductTransfer $priceProductTransfer): bool;

    /**
     * Specification:
     * - Adds a new volumePrice to priceData JSON of PriceProductTransfer.
     * - Returns updated PriceProductTransfer.
     *
     * @api
     *
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
     * Specification:
     * - Deletes a volumePrice from priceData JSON of PriceProductTransfer by quantity.
     * - Returns updated PriceProductTransfer.
     *
     * @api
     *
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
     * Specification:
     * - Extracts volume price data from `PriceProductTransfer` money value price data by the same volume price quantity.
     * - Returns `PriceProductTransfer` with extracted `volumePrice` data.
     *
     * @api
     *
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
