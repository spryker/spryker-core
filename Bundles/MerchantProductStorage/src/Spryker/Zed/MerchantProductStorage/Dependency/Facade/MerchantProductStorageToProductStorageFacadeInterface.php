<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Dependency\Facade;

interface MerchantProductStorageToProductStorageFacadeInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publishAbstractProducts(array $productAbstractIds);
}
