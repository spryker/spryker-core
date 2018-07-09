<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductMapperPricesExtractorPluginInterface
{
    /**
     * Specification:
     * - Extracts additional product prices from price product data
     *
     * @api
     *
     * @param PriceProductTransfer $priceProductTransfer
     *
     * @return PriceProductTransfer[]
     */
    public function extractProductPrices(
        PriceProductTransfer $priceProductTransfer
    ): array;
}