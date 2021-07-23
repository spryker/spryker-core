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
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param mixed[] $requestData
     * @param int $volumeQuantity
     * @param int $idProductOffer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function expandPriceProductsWithVolumeData(
        ArrayObject $priceProductTransfers,
        array $requestData,
        int $volumeQuantity,
        int $idProductOffer
    ): ArrayObject;
}
