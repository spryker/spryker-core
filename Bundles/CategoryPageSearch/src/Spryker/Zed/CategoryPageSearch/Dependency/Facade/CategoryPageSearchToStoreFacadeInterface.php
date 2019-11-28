<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

interface CategoryPageSearchToStoreFacadeInterface
{
    /**
     * @param string $storeName
     *
     * @return string[]
     */
    public function getLocalesPerStore(string $storeName): array;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStoresWithSharedPersistence(StoreTransfer $storeTransfer);

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore();
}
