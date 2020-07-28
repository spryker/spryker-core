<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolumeGui\Communication\Reader;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface PriceProductOfferVolumeReaderInterface
{
    /**
     * @phpstan-return array<string, mixed>
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param string $storeName
     * @param string $currencyCode
     *
     * @return array
     */
    public function getVolumePricesData(
        ProductOfferTransfer $productOfferTransfer,
        string $storeName,
        string $currencyCode
    ): array;
}
