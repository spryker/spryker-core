<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductConfigurationStorageExtension\Dependency\Plugin\ProductConfigurationStoragePriceExtractorPluginInterface;

/**
 * @method \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageClientInterface getClient()
 */
class ProductConfigurationStorageVolumeExtractorPlugin extends AbstractPlugin implements ProductConfigurationStoragePriceExtractorPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractProductPrices(array $priceProductTransfers): array
    {
        return $this->getClient()->extractProductPrices($priceProductTransfers);
    }
}
