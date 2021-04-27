<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductOfferVolume;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\PriceProductOfferVolume\PriceProductOfferVolumeServiceFactory getFactory()
 */
class PriceProductOfferVolumeService extends AbstractService implements PriceProductOfferVolumeServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getMinPriceProducts(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): array
    {
        return $this->getFactory()->createPriceProductReader()->getMinPriceProducts($priceProductTransfers, $priceProductFilterTransfer);
    }

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
        return $this->getFactory()->createProductOfferVolumePriceExtractor()->extractProductPrices($priceProductOfferTransfers);
    }
}
