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
     * - return Product Abstract Category by id
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer|null
     */
    public function findProductAbstractCategory($idProductAbstract, $locale);

    /**
     * Specification:
     * - return Product Abstract Category by ids
     *
     * @api
     *
     * @param array $productAbstractIds
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer[]|null
     */
    public function findBulkProductAbstractCategory(array $productAbstractIds, $locale): ?array;
}
