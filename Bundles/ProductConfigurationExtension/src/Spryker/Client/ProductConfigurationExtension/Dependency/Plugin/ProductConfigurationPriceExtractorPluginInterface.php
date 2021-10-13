<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationExtension\Dependency\Plugin;

/**
 * Use this plugin to extract additional product prices from product configuration price data.
 */
interface ProductConfigurationPriceExtractorPluginInterface
{
    /**
     * Specification:
     * - Extracts additional product configuration prices from product configuration price data.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function extractProductPrices(array $priceProductTransfers): array;
}
