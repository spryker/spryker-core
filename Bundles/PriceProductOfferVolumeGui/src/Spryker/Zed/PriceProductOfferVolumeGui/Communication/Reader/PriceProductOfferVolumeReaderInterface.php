<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolumeGui\Communication\Reader;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface PriceProductOfferVolumeReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param string $storeName
     * @param string $currencyCode
     * @param string|null $priceType
     *
     * @return array<string, mixed>
     */
    public function getVolumePricesData(
        ProductOfferTransfer $productOfferTransfer,
        string $storeName,
        string $currencyCode,
        ?string $priceType = null
    ): array;
}
