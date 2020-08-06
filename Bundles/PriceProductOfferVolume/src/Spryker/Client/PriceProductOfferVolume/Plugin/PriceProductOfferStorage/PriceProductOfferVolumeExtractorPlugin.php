<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferVolume\Plugin\PriceProductOfferStorage;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PriceProductOfferStorageExtension\Dependency\Plugin\PriceProductOfferStoragePriceExtractorPluginInterface;

/**
 * @method \Spryker\Client\PriceProductOfferVolume\PriceProductOfferVolumeClientInterface getClient()
 */
class PriceProductOfferVolumeExtractorPlugin extends AbstractPlugin implements PriceProductOfferStoragePriceExtractorPluginInterface
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
