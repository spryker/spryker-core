<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business\Reader;

interface ProductCategoryStorageReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductCategoryTransfer[] $productCategoryTransfers
     * @param string $storeName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductCategoryStorageTransfer[]
     */
    public function getProductCategoryStoragesFromCategoryTree(
        array $productCategoryTransfers,
        string $storeName,
        string $localeName
    ): array;
}
