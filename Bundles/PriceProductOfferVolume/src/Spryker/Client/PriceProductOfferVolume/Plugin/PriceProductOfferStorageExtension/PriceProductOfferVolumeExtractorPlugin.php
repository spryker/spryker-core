<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferVolume\Plugin\PriceProductOfferStorageExtension;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PriceProductOfferStorageExtension\Dependency\Plugin\PriceProductOfferStoragePricesExtractorPluginInterface;

/**
 * @method \Spryker\Client\PriceProductOfferVolume\PriceProductOfferVolumeClientInterface getClient()
 */
class PriceProductOfferVolumeExtractorPlugin extends AbstractPlugin implements PriceProductOfferStoragePricesExtractorPluginInterface
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
    public function extractProductPricesForProductOffer(array $priceProductTransfers): array
    {
        return $this->getClient()->extractProductPricesForProductOffer($priceProductTransfers);
    }
}
