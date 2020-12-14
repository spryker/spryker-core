<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Deleter;

use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;

interface PriceProductOfferDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     *
     * @return void
     */
    public function deleteProductOfferPrices(PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer): void;
}
