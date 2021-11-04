<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Business;

use ArrayObject;
use Generated\Shared\Transfer\ValidationResponseTransfer;

interface PriceProductVolumeFacadeInterface
{
    /**
     * Specification:
     * - Extracts additional product prices from price product data volume prices
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function extractPriceProductVolumesForProductAbstract(array $priceProductTransfers): array;

    /**
     * Specification:
     * - Extracts additional product prices from price product data volume prices
     * - If volume prices for concrete product empty - fetches product abstract prices and extract from them
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function extractPriceProductVolumesForProductConcrete(array $priceProductTransfers): array;

    /**
     * Specification:
     * - Extracts additional product prices from price product data volume prices
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function extractPriceProductVolumeTransfersFromArray(array $priceProductTransfers): array;

    /**
     * Specification:
     * - Provides validation for a collection of PriceProduct transfers.
     * - Returns ValidationResponseTransfer.isSuccess = true if validation is passed, false otherwise.
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateVolumePrices(ArrayObject $priceProductTransfers): ValidationResponseTransfer;
}
