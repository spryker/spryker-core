<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductAlternativeStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $idProduct
     *
     * @return void
     */
    public function publishAlternative(array $idProduct): void;

    /**
     * Specification:
     *  - Publish replacements for abstract product
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishAbstractReplacements(array $productIds): void;

    /**
     * Specification:
     *  - Publish replacements for concrete product
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishConcreteReplacements(array $productIds): void;

    /**
     * Specification:
     *  - Returns SpyProductAlternativeStorage collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage[]
     */
    public function getAllProductAlternativeStorageByFilter(FilterTransfer $filterTransfer): array;

    /**
     * Specification:
     *  - Returns SpyProductAlternativeStorage collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $ids
     *
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorage[]
     */
    public function getProductAlternativeStorageByFilter(FilterTransfer $filterTransfer, array $ids): array;

    /**
     * Specification:
     * - Returns ProductReplacementForStorage collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage[]
     */
    public function getAllProductReplacementForStorageByFilter(FilterTransfer $filterTransfer): array;

    /**
     * Specification:
     * - Returns ProductReplacementForStorage collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $ids
     *
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorage[]
     */
    public function getProductReplacementForStorageByFilter(FilterTransfer $filterTransfer, array $ids): array;
}
