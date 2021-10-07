<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider;

use ArrayObject;

interface PriceProductDataProviderInterface
{
    /**
     * @param array<int> $typePriceProductOfferIds
     * @param array<mixed> $requestData
     * @param int $volumeQuantity
     * @param int $idProductOffer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getPriceProductOfferPrices(
        array $typePriceProductOfferIds,
        array $requestData,
        int $volumeQuantity,
        int $idProductOffer
    ): ArrayObject;
}
