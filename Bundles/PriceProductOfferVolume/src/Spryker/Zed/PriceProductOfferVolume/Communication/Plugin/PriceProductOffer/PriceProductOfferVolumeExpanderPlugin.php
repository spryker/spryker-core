<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Communication\Plugin\PriceProductOffer;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferExpanderPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductOfferVolume\PriceProductOfferVolumeConfig getConfig()
 * @method \Spryker\Zed\PriceProductOfferVolume\Business\PriceProductOfferVolumeFacadeInterface getFacade()
 */
class PriceProductOfferVolumeExpanderPlugin extends AbstractPlugin implements PriceProductOfferExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `PriceProductTransfer` with `volumeQuantity`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function expand(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        return $this->getFacade()->expandPriceProductTransfer($priceProductTransfer);
    }
}
