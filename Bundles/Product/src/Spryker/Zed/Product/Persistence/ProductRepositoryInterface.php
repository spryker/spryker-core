<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductRepositoryInterface
{
    /**
     * Specification:
     * - Searches for abstract products by name or sku and returns associative array of abstract products.
     * - Associative array contains items, each of one has product id as key and SKU as value.
     * - Keys for associative array are stored in ProductConstants.
     *
     * @api
     *
     * @param string $search
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $limit
     *
     * @return array
     */
    public function findProductAbstractDataBySkuOrLocalizedName(string $search, LocaleTransfer $localeTransfer, int $limit): array;

    /**
     * Specification:
     * - Searches for concrete products by name or sku and returns associative array of concrete products.
     * - Associative array contains items, each of one has product id as key and SKU as value.
     * - Keys for associative array are stored in ProductConstants.
     *
     * @api
     *
     * @param string $search
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $limit
     *
     * @return array
     */
    public function findProductConcreteDataBySkuOrLocalizedName(string $search, LocaleTransfer $localeTransfer, int $limit): array;
}
