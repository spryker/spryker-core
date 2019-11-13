<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\PriceProductOfferStorage\PriceProductOfferStorageFactory getFactory()
 */
class PriceProductOfferStorageClient extends AbstractClient implements PriceProductOfferStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductOfferPrices(int $idProductConcrete): array
    {
        return $this->getFactory()
            ->createPriceProductOfferStorageReader()
            ->findPriceProductList($idProductConcrete);
    }
}
