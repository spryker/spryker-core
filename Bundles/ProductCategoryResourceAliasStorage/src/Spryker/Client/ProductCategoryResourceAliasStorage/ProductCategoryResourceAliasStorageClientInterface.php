<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryResourceAliasStorage;

use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;

interface ProductCategoryResourceAliasStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves a current store specific ProductAbstractCategory resource from storage.
     *
     * @api
     *
     * @param string $sku
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer|null
     */
    public function findProductCategoryAbstractStorageTransfer(string $sku, string $localeName): ?ProductAbstractCategoryStorageTransfer;
}
