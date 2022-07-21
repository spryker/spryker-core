<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Communication\Plugin\PriceProductOffer;

use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferValidatorPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductOfferVolume\PriceProductOfferVolumeConfig getConfig()
 * @method \Spryker\Zed\PriceProductOfferVolume\Business\PriceProductOfferVolumeFacadeInterface getFacade()
 */
class PriceProductOfferVolumeValidatorPlugin extends AbstractPlugin implements PriceProductOfferValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates volume prices.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validate(PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer): ValidationResponseTransfer
    {
        return $this->getFacade()
            ->validatePriceProductOfferCollection($priceProductOfferCollectionTransfer);
    }
}
