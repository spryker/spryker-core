<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade;

interface ProductOfferMerchantPortalGuiToPriceProductOfferVolumeFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductOfferTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractVolumePrices(array $priceProductOfferTransfers): array;
}
