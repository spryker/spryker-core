<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Business;

use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;

interface PriceProductOfferVolumeFacadeInterface
{
    /**
     * Specification:
     * - Extracts volume prices from price product offer collection.
     * - Returns volume price collection.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductOfferTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function extractVolumePrices(array $priceProductOfferTransfers): array;

    /**
     * Specification:
     * - Expands PriceProductTransfer with volumeQuantity property taken from PriceProductTransfer.moneyValue.priceData.
     * - Expands with 1 if PriceProductTransfer.moneyValue.priceData transfer property is not set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function expandPriceProductTransfer(PriceProductTransfer $priceProductTransfer): PriceProductTransfer;

    /**
     * Specification:
     * - Provides validation for a collection of PriceProductOffer transfers.
     * - Returns ValidationResponseTransfer.isSuccess = true if validation is passed, false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validatePriceProductOfferCollection(
        PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
    ): ValidationResponseTransfer;
}
