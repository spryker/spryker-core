<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfiguration;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

interface ProductConfigurationServiceInterface
{
    /**
     * Specification:
     * - Generates a hash for `ProductConfigurationInstanceTransfer`.
     * - Uses md5 as hashing algorithm.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return string
     */
    public function getProductConfigurationInstanceHash(ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer): string;

    /**
     * Specification:
     * - Checks that price product filter has product configuration instance.
     * - Filters out all prices except product configuration prices.
     * - Leaves prices without changes if price product filter doesn't have product configuration instance.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function filterProductConfigurationPrices(
        array $priceProductTransfers,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): array;

    /**
     * Specification:
     * - Compares singular item prices with volume prices.
     * - Finds corresponding volume price for provided quantity.
     * - Returns singular item prices if matching volume price can not be found.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function filterProductConfigurationVolumePrices(
        array $priceProductTransfers,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): array;
}
