<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferGui\Communication\Reader\PriceProductOffer;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface PriceProductOfferReaderInterface
{
    /**
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return array
     */
    public function getProductOfferPricesData(ProductOfferTransfer $productOfferTransfer): array;
}
