<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade;

interface ProductOfferMerchantPortalGuiToCurrencyFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\StoreWithCurrencyTransfer[]
     */
    public function getAllStoresWithCurrencies();
}
