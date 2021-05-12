<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PriceProductOfferVolume\Business\PriceProductOfferVolumeBusinessFactory getFactory()
 * @method \Spryker\Zed\PriceProductOfferVolume\Persistence\PriceProductOfferVolumeRepositoryInterface getRepository()
 * @method \Spryker\Zed\PriceProductOfferVolume\Persistence\PriceProductOfferVolumeEntityManagerInterface getEntityManager()
 */
class PriceProductOfferVolumeFacade extends AbstractFacade implements PriceProductOfferVolumeFacadeInterface
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
    public function extractVolumePrices(array $priceProductOfferTransfers): array
    {
        return $this->getFactory()
            ->getPriceProductOfferVolumeService()
            ->extractVolumePrices($priceProductOfferTransfers);
    }
}
