<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryStorage\Filter;

use Generated\Shared\Transfer\ProductCategoryStorageTransfer;

class ProductCategoryStorageFilter implements ProductCategoryStorageFilterInterface
{
    /**
     * @param array<int, \Generated\Shared\Transfer\ProductCategoryStorageTransfer> $productCategoryStorageTransfers
     * @param string $httpReferer
     *
     * @return list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer>
     */
    public function filterProductCategoriesByHttpReferer(array $productCategoryStorageTransfers, string $httpReferer): array
    {
        return $this->filterProductCategoriesRecursive($productCategoryStorageTransfers, $httpReferer);
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\ProductCategoryStorageTransfer> $productCategoryStorageTransfers
     * @param string $httpReferer
     * @param list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer> $filteredProductCategoryStorageTransfers
     * @param \Generated\Shared\Transfer\ProductCategoryStorageTransfer|null $relativeProductCategoryStorageTransfer
     *
     * @return list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer>
     */
    protected function filterProductCategoriesRecursive(
        array $productCategoryStorageTransfers,
        string $httpReferer,
        array $filteredProductCategoryStorageTransfers = [],
        ?ProductCategoryStorageTransfer $relativeProductCategoryStorageTransfer = null
    ): array {
        foreach ($productCategoryStorageTransfers as $key => $productCategoryStorageTransfer) {
            unset($productCategoryStorageTransfers[$key]);
            if (!$this->isCategoryApplicableForFiltering($productCategoryStorageTransfer, $httpReferer, $relativeProductCategoryStorageTransfer)) {
                continue;
            }

            $filteredProductCategoryStorageTransfers[] = $productCategoryStorageTransfer;

            $filteredProductCategoryStorageTransfers = $this->filterProductCategoriesRecursive(
                $productCategoryStorageTransfers,
                $httpReferer,
                $filteredProductCategoryStorageTransfers,
                $productCategoryStorageTransfer,
            );

            break;
        }

        return $filteredProductCategoryStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryStorageTransfer $productCategoryStorageTransfer
     * @param string $httpReferer
     * @param \Generated\Shared\Transfer\ProductCategoryStorageTransfer|null $relativeProductCategoryStorageTransfer
     *
     * @return bool
     */
    protected function isCategoryApplicableForFiltering(
        ProductCategoryStorageTransfer $productCategoryStorageTransfer,
        string $httpReferer,
        ?ProductCategoryStorageTransfer $relativeProductCategoryStorageTransfer = null
    ): bool {
        if ($relativeProductCategoryStorageTransfer && !$this->isRelativeCategory($productCategoryStorageTransfer, $relativeProductCategoryStorageTransfer)) {
            return false;
        }

        if ($httpReferer && strpos($httpReferer, $productCategoryStorageTransfer->getUrlOrFail()) === false) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryStorageTransfer $productCategoryStorageTransfer
     * @param \Generated\Shared\Transfer\ProductCategoryStorageTransfer $relativeProductCategoryStorageTransfer
     *
     * @return bool
     */
    protected function isRelativeCategory(
        ProductCategoryStorageTransfer $productCategoryStorageTransfer,
        ProductCategoryStorageTransfer $relativeProductCategoryStorageTransfer
    ): bool {
        if (in_array($relativeProductCategoryStorageTransfer->getCategoryId(), $productCategoryStorageTransfer->getParentCategoryIds(), true)) {
            return true;
        }

        if (in_array($productCategoryStorageTransfer->getCategoryId(), $relativeProductCategoryStorageTransfer->getParentCategoryIds(), true)) {
            return true;
        }

        return false;
    }
}
