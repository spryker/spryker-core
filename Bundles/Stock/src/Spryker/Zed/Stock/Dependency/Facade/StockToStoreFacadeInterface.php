<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Dependency\Facade;

interface StockToStoreFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getAllStores();

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore();
}
