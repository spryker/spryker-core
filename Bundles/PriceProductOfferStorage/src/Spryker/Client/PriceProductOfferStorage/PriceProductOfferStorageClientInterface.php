<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferStorage;

interface PriceProductOfferStorageClientInterface
{
    /**
     * Specification:
     * - Gets product offer prices from storage by idProductConcrete.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getProductOfferPrices(int $idProductConcrete): array;
}
