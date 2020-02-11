<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage;

use Generated\Shared\Transfer\ProductAbstractProductListStorageTransfer;
use Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer;

interface ProductListStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves product abstract product list storage data from storage.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractProductListStorageTransfer|null
     */
    public function findProductAbstractProductListStorage(int $idProductAbstract): ?ProductAbstractProductListStorageTransfer;

    /**
     * Specification:
     * - Retrieves product concrete product list storage data from storage.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer|null
     */
    public function findProductConcreteProductListStorage(int $idProduct): ?ProductConcreteProductListStorageTransfer;

    /**
     * Specification:
     * - Checks if products abstract is restricted.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductAbstractRestricted(int $idProductAbstract): bool;

    /**
     * Specification:
     * - Checks if products concrete is restricted.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return bool
     */
    public function isProductConcreteRestricted(int $idProduct): bool;

    /**
     * Specification:
     * - Filters array of abstract product ids and remove product ids that are restricted.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    public function filterRestrictedAbstractProducts(array $productAbstractIds): array;

    /**
     * Specification:
     * - Filters array of concrete product ids and remove product ids that are restricted.
     *
     * @api
     *
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    public function filterRestrictedConcreteProducts(array $productConcreteIds): array;
}
