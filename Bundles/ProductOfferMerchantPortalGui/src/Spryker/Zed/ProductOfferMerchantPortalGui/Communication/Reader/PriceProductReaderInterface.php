<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Reader;

use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;

interface PriceProductReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getPriceProductTransfers(
        PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
    ): array;
}
