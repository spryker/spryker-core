<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShoppingList\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

interface ProductOfferShoppingListToStoreFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore(): StoreTransfer;
}
