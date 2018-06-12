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
     * - Searches for abstract products by SKU and returns associative array of abstract products.
     * - Associative array contains items, each of one has product id as key and SKU as value.
     * - Keys for associative array are stored in ProductConstants.
     *
     * @api
     *
     * @param string $sku
     * @param int $limit
     *
     * @return array
     */
    public function getProductAbstractDataBySku(string $sku, int $limit): array;

    /**
     * Specification:
     * - Searches for abstract products by name and returns associative array of abstract products.
     * - Associative array contains items, each of one has product id as key and name as value.
     * - Keys for associative array are stored in ProductConstants.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $localizedName
     * @param int $limit
     *
     * @return array
     */
    public function getProductAbstractDataByLocalizedName(LocaleTransfer $localeTransfer, string $localizedName, int $limit): array;

    /**
     * Specification:
     * - Searches for concrete products by name and returns associative array of concrete products.
     * - Associative array contains items, each of one has product id as key and SKU as value.
     * - Keys for associative array are stored in ProductConstants.
     *
     * @api
     *
     * @param string $sku
     * @param int $limit
     *
     * @return array
     */
    public function getProductConcreteDataBySku(string $sku, int $limit): array;

    /**
     * Specification:
     * - Searches for concrete products by name and returns associative array of concrete products.
     * - Associative array contains items, each of one has product id as key and name as value.
     * - Keys for associative array are stored in ProductConstants.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $localizedName
     * @param int $limit
     *
     * @return array
     */
    public function getProductConcreteDataByLocalizedName(LocaleTransfer $localeTransfer, string $localizedName, int $limit): array;
}
