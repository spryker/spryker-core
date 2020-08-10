<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferVolume;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\PriceProductOfferVolume\PriceProductOfferVolumeFactory getFactory()
 */
class PriceProductOfferVolumeClient extends AbstractClient implements PriceProductOfferVolumeClientInterface
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
        return $this->getFactory()
            ->createProductOfferVolumePriceExtractor()
            ->extractProductPrices($priceProductTransfers);
    }
}
