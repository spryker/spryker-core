<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Persistence;

use Generated\Shared\Transfer\ProductSearchAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeCriteriaTransfer;

interface ProductSearchRepositoryInterface
{
    /**
     * Result format:
     * [
     *     $idProduct => [$idLocale => $count],
     *     ...,
     * ]
     *
     * @param array<int> $productIds
     * @param array<int> $localeIds
     *
     * @return array<int, array<int, int>>
     */
    public function getProductSearchEntitiesCountGroupedByIdProductAndIdLocale(
        array $productIds,
        array $localeIds
    ): array;

    /**
     * @return array<string>
     */
    public function getAllProductAttributeKeys(): array;

    /**
     * @param \Generated\Shared\Transfer\ProductSearchAttributeCriteriaTransfer $productSearchAttributeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeCollectionTransfer
     */
    public function getProductSearchAttributeCollection(
        ProductSearchAttributeCriteriaTransfer $productSearchAttributeCriteriaTransfer
    ): ProductSearchAttributeCollectionTransfer;
}
