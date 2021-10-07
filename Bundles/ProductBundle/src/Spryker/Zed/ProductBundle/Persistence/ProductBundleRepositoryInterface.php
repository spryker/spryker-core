<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence;

use Generated\Shared\Transfer\ProductBundleCollectionTransfer;
use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;

interface ProductBundleRepositoryInterface
{
    /**
     * @param string $sku
     *
     * @return array<\Generated\Shared\Transfer\ProductForBundleTransfer>
     */
    public function findBundledProductsBySku(string $sku): array;

    /**
     * @param \Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBundleCollectionTransfer
     */
    public function getProductBundleCollectionByCriteriaFilter(
        ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer
    ): ProductBundleCollectionTransfer;

    /**
     * @param array<string> $skus
     *
     * @return array<\Generated\Shared\Transfer\ProductForBundleTransfer>
     */
    public function getProductForBundleTransfersByProductConcreteSkus(array $skus): array;

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function getBundleItemsBySalesOrderItemIds(array $salesOrderItemIds): array;

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return array
     */
    public function getProductConcretesRawDataByProductConcreteSkus(array $productConcreteSkus): array;
}
