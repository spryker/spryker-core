<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\StockTransfer;

interface ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface
{
    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function getDefaultMerchantStock(int $idMerchant): StockTransfer;
}
