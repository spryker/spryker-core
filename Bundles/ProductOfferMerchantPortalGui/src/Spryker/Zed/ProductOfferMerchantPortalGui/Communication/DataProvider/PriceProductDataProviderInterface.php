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
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param int[] $typePriceProductOfferIds
     * @param mixed[] $requestData
     * @param int $volumeQuantity
     * @param int $idProductOffer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getPriceProductOfferPrices(
        array $typePriceProductOfferIds,
        array $requestData,
        int $volumeQuantity,
        int $idProductOffer
    ): ArrayObject;
}
