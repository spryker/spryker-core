<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductOfferVolume;

use Generated\Shared\Transfer\PriceProductFilterTransfer;

interface PriceProductOfferVolumeServiceInterface
{
    /**
     * Specification:
     * - Finds a minimal volume price for provided quantity.
     * - Returns singular item prices if matching volume price can not be found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getMinPriceProducts(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): array;

    /**
     * Specification:
     * - Extracts volume prices from price product offer collection.
     * - Returns volume price collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractVolumePrices(array $priceProductTransfers): array;
}
