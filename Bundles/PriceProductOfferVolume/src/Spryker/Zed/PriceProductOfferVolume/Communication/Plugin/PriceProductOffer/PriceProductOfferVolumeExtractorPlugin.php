<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Communication\Plugin\PriceProductOffer;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferExtractorPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductOfferVolume\PriceProductOfferVolumeConfig getConfig()
 * @method \Spryker\Zed\PriceProductOfferVolume\Business\PriceProductOfferVolumeFacadeInterface getFacade()
 */
class PriceProductOfferVolumeExtractorPlugin extends AbstractPlugin implements PriceProductOfferExtractorPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductOfferTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extract(array $priceProductOfferTransfers): array
    {
        return $this->getFacade()->extractVolumePrices($priceProductOfferTransfers);
    }
}
