<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryResourceAliasStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;

interface ProductAbstractCategoryStorageReaderInterface
{
    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer|null
     */
    public function findProductAbstractCategoryStorageData(string $sku, string $localeName): ?ProductAbstractCategoryStorageTransfer;
}
