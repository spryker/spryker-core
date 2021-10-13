<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Expander;

use ArrayObject;

interface PriceProductsVolumeDataExpanderInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param array<mixed> $requestData
     * @param int $volumeQuantity
     * @param int $idProductOffer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function expandPriceProductsWithVolumeData(
        ArrayObject $priceProductTransfers,
        array $requestData,
        int $volumeQuantity,
        int $idProductOffer
    ): ArrayObject;
}
