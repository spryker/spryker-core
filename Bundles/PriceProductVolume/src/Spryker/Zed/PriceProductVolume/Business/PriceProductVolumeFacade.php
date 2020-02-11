<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PriceProductVolume\Business\PriceProductVolumeBusinessFactory getFactory()
 */
class PriceProductVolumeFacade extends AbstractFacade implements PriceProductVolumeFacadeInterface
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
    public function extractPriceProductVolumesForProductAbstract(array $priceProductTransfers): array
    {
        return $this->getFactory()
            ->createVolumePriceExtractor()
            ->extractPriceProductVolumesForProductAbstract($priceProductTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractPriceProductVolumesForProductConcrete(array $priceProductTransfers): array
    {
        return $this->getFactory()
            ->createVolumePriceExtractor()
            ->extractPriceProductVolumesForProductConcrete($priceProductTransfers);
    }
}
