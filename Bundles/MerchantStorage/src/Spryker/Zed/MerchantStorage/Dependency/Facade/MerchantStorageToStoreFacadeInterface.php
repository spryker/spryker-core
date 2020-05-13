<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Dependency\Facade;

interface MerchantStorageToStoreFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getAllStores();
}
