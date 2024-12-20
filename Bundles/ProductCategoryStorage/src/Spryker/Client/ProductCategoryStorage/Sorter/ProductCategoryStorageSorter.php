<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryStorage\Sorter;

use Generated\Shared\Transfer\ProductCategoryStorageTransfer;

class ProductCategoryStorageSorter implements ProductCategoryStorageSorterInterface
{
    /**
     * @param array<int, \Generated\Shared\Transfer\ProductCategoryStorageTransfer> $productCategoryStorageTransfers
     *
     * @return list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer>
     */
    public function sortProductCategories(array $productCategoryStorageTransfers): array
    {
        $sortedProductCategoryStorageTransfers = [];
        $alreadySortedCategoryIds = [];

        foreach ($productCategoryStorageTransfers as $productCategoryStorageTransfer) {
            if (in_array($productCategoryStorageTransfer->getCategoryIdOrFail(), $alreadySortedCategoryIds, true)) {
                continue;
            }

            $sortedProductCategoryStorageTransfers = $this->addParentProductCategoryStorageTransfer(
                $productCategoryStorageTransfer,
                $productCategoryStorageTransfers,
                $sortedProductCategoryStorageTransfers,
                $alreadySortedCategoryIds,
            );

            $sortedProductCategoryStorageTransfers = $this->addSortedProductCategoryStorageTransfer(
                $productCategoryStorageTransfer,
                $sortedProductCategoryStorageTransfers,
                $alreadySortedCategoryIds,
            );
        }

        return $sortedProductCategoryStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryStorageTransfer $productCategoryStorageTransfer
     * @param list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer> $productCategoryStorageTransfers
     * @param list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer> $sortedProductCategoryStorageTransfers
     * @param list<int> $alreadySortedCategoryIds
     *
     * @return list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer>
     */
    protected function addParentProductCategoryStorageTransfer(
        ProductCategoryStorageTransfer $productCategoryStorageTransfer,
        array $productCategoryStorageTransfers,
        array $sortedProductCategoryStorageTransfers,
        array &$alreadySortedCategoryIds
    ): array {
        $parentProductCategoryStorageTransfer = $this->getParentProductCategoryStorageTransfer(
            $productCategoryStorageTransfers,
            $productCategoryStorageTransfer,
        );

        if ($parentProductCategoryStorageTransfer) {
            $sortedProductCategoryStorageTransfers = $this->addParentProductCategoryStorageTransfer(
                $parentProductCategoryStorageTransfer,
                $productCategoryStorageTransfers,
                $sortedProductCategoryStorageTransfers,
                $alreadySortedCategoryIds,
            );

            $sortedProductCategoryStorageTransfers = $this->addSortedProductCategoryStorageTransfer(
                $parentProductCategoryStorageTransfer,
                $sortedProductCategoryStorageTransfers,
                $alreadySortedCategoryIds,
            );
        }

        return $sortedProductCategoryStorageTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer> $productCategoryStorageTransfers
     * @param \Generated\Shared\Transfer\ProductCategoryStorageTransfer $childProductCategoryStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryStorageTransfer|null
     */
    protected function getParentProductCategoryStorageTransfer(
        array $productCategoryStorageTransfers,
        ProductCategoryStorageTransfer $childProductCategoryStorageTransfer
    ): ?ProductCategoryStorageTransfer {
        foreach ($productCategoryStorageTransfers as $productCategoryStorageTransfer) {
            if (in_array($productCategoryStorageTransfer->getCategoryIdOrFail(), $childProductCategoryStorageTransfer->getParentCategoryIds(), true)) {
                return $productCategoryStorageTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryStorageTransfer $productCategoryStorageTransfer
     * @param list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer> $sortedProductCategoryStorageTransfers
     * @param list<int> $alreadySortedCategoryIds
     *
     * @return list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer>
     */
    protected function addSortedProductCategoryStorageTransfer(
        ProductCategoryStorageTransfer $productCategoryStorageTransfer,
        array $sortedProductCategoryStorageTransfers,
        array &$alreadySortedCategoryIds
    ): array {
        if (!in_array($productCategoryStorageTransfer->getCategoryIdOrFail(), $alreadySortedCategoryIds, true)) {
            $sortedProductCategoryStorageTransfers[] = $productCategoryStorageTransfer;
            $alreadySortedCategoryIds[] = $productCategoryStorageTransfer->getCategoryIdOrFail();
        }

        return $sortedProductCategoryStorageTransfers;
    }
}
