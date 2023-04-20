<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client;

interface CmsSlotBlockProductCategoryConnectorToProductCategoryStorageClientInterface
{
    /**
     * @param list<int> $productAbstractIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return list<\Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer>
     */
    public function findBulkProductAbstractCategory(array $productAbstractIds, string $localeName, string $storeName): array;
}
