<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfigurationPriceExtractorPluginInterface;

/**
 * @method \Spryker\Client\ProductConfiguration\ProductConfigurationFactory getFactory()
 */
class PriceProductVolumeProductConfigurationPriceExtractorPlugin extends AbstractPlugin implements ProductConfigurationPriceExtractorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Extracts volume prices from price product data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractProductPrices(array $priceProductTransfers): array
    {
        return $this->getFactory()
            ->getPriceProductVolumeClient()
            ->extractProductPricesForProductAbstract($priceProductTransfers);
    }
}
