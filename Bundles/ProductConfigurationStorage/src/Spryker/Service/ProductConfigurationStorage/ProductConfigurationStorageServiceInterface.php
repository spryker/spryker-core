<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfigurationStorage;

use Generated\Shared\Transfer\PriceProductFilterTransfer;

interface ProductConfigurationStorageServiceInterface
{
    /**
     * Specification:
     * - Checks that price product filter has product configuration instance.
     * - Filters out all prices except product configuration prices.
     * - Leaves prices without changes if price product filter doesn't have product configuration instance.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
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
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function filterProductConfigurationVolumePrices(
        array $priceProductTransfers,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): array;
}
