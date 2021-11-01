<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferGui\Communication\Reader;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface PriceProductOfferReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return array<string, mixed>
     */
    public function getProductOfferPricesData(ProductOfferTransfer $productOfferTransfer): array;
}
