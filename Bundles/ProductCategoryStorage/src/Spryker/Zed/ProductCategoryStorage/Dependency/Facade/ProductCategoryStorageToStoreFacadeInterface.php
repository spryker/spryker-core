<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

interface ProductCategoryStorageToStoreFacadeInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getAllStores();

    /**
     * @param bool $fallbackToDefault
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore(bool $fallbackToDefault = false): StoreTransfer;
}
