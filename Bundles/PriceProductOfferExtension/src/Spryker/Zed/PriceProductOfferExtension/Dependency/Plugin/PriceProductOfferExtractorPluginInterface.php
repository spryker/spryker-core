<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin;

/**
 * Provides ability to extract volume prices from product offer prices.
 */
interface PriceProductOfferExtractorPluginInterface
{
    /**
     * Specification:
     * - Extracts volume prices from price product offer collection.
     * - Returns extracted volume price collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductOfferTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extract(array $priceProductOfferTransfers): array;
}
