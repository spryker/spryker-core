<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\PriceProductTransfer;

interface ProductOfferMerchantPortalGuiToPriceProductFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\PriceTypeTransfer[]
     */
    public function getPriceTypeValues();

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function persistPriceProductStore(PriceProductTransfer $priceProductTransfer): PriceProductTransfer;
}
