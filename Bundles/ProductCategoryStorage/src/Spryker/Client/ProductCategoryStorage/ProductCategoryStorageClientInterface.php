<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryStorage;

interface ProductCategoryStorageClientInterface
{
    /**
     * Specification:
     * - Returns Product Abstract Category by id for given store and locale.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $locale
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer|null
     */
    public function findProductAbstractCategory($idProductAbstract, $locale, string $storeName);

    /**
     * Specification:
     * - Returns Product Abstract Categories grouped by Product Abstract ids for given store and locale.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer[]
     */
    public function findBulkProductAbstractCategory(array $productAbstractIds, string $localeName, string $storeName): array;
}
