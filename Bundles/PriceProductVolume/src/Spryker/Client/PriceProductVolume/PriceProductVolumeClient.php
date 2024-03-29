<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductVolume;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\PriceProductVolume\PriceProductVolumeFactory getFactory()
 */
class PriceProductVolumeClient extends AbstractClient implements PriceProductVolumeClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function extractProductPricesForProductAbstract(array $priceProductTransfers): array
    {
        return $this->getFactory()
            ->createVolumePriceExtractor()
            ->extractProductPricesForProductAbstract($priceProductTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function extractProductPricesForProductConcrete(int $idProductConcrete, array $priceProductTransfers): array
    {
        return $this->getFactory()
            ->createVolumePriceExtractor()
            ->extractProductPricesForProductConcrete($idProductConcrete, $priceProductTransfers);
    }
}
