<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

interface MinimumOrderValueDataImportToStoreFacadeInterface
{
    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    public function getStoreByName(string $storeName): ?StoreTransfer;
}
