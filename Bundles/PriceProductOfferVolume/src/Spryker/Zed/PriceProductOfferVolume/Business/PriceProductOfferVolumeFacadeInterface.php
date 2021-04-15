<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Business;

interface PriceProductOfferVolumeFacadeInterface
{
    /**
     * Specification:
     * - Extracts volume prices from price product offer collection.
     * - Returns volume price collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductOfferTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractVolumePrices(array $priceProductOfferTransfers): array;
}
