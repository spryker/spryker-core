<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

interface MerchantRelationshipSalesOrderThresholdDataImportToStoreFacadeInterface
{
    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    public function getStoreByName(string $storeName): ?StoreTransfer;
}
